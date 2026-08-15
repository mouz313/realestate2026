<?php

namespace App\Http\Controllers;

use App\Exports\DealExport;
use App\Helpers\Status;
use App\Models\Agent;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Property;
use App\Notifications\DealStatusChanged;
use App\Services\ExcelWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class DealController extends Controller
{
    public function index(Request $request)
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $deals = Deal::with(['property', 'buyer', 'seller', 'agent'])
            ->when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->when($request->lead_source, fn ($q) => $q->where('lead_source', $request->lead_source))
            ->when($request->search, function ($q) use ($request) {
                $s = $request->search;
                $q->where(function ($q2) use ($s) {
                    $q2->where('deal_number', 'like', "%{$s}%")
                        ->orWhereHas('property', fn ($pq) => $pq->where('title', 'like', "%{$s}%"))
                        ->orWhereHas('buyer', fn ($cq) => $cq->where('name', 'like', "%{$s}%"))
                        ->orWhereHas('seller', fn ($cq) => $cq->where('name', 'like', "%{$s}%"));
                });
            })
            ->latest()->paginate(15)->withQueryString();

        $statuses = ['inquiry', 'visit_scheduled', 'offer_made', 'token_received', 'agreement_signed', 'in_progress', 'completed', 'cancelled'];
        $leadSources = Status::leadSources();

        return view('deals.index', compact('deals', 'statuses', 'leadSources'));
    }

    public function create()
    {
        $properties = Property::where('status', 'available')->orderBy('title')->get();
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();
        $statuses = ['inquiry', 'visit_scheduled', 'offer_made', 'token_received', 'agreement_signed', 'in_progress', 'completed', 'cancelled'];
        $defaultAgentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $leadSources = Status::leadSources();

        return view('deals.create', compact('properties', 'clients', 'agents', 'statuses', 'defaultAgentId', 'leadSources'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lead_source' => ['nullable', 'string', Rule::in(array_keys(Status::leadSources()))],
            'property_id' => 'required|exists:properties,id',
            'buyer_id' => 'required|exists:clients,id',
            'seller_id' => 'required|exists:clients,id',
            'agent_id' => 'nullable|exists:agents,id',
            'sale_price' => 'required|numeric|min:0',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'commission_amount' => 'nullable|numeric|min:0',
            'agent_commission' => 'nullable|numeric|min:0',
            'agency_share' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:inquiry,visit_scheduled,offer_made,token_received,agreement_signed,in_progress,completed,cancelled',
            'agreement_date' => 'nullable|date',
            'possession_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data = $request->all();

        if (auth()->user()->isAgent() && empty($data['agent_id'])) {
            $data['agent_id'] = auth()->user()->agent_id;
        }

        $lastDeal = Deal::withTrashed()->orderBy('id', 'desc')->first();
        $nextId = $lastDeal ? $lastDeal->id + 1 : 1;
        $data['deal_number'] = 'DL-'.str_pad($nextId, 5, '0', STR_PAD_LEFT);

        if (! empty($data['sale_price']) && ! empty($data['commission_percentage']) && empty($data['commission_amount'])) {
            $data['commission_amount'] = $data['sale_price'] * $data['commission_percentage'] / 100;
        }

        Deal::create($data);
        toastr()->success('Deal added successfully.');

        return redirect()->route('deals.index');
    }

    public function show(Deal $deal)
    {
        $this->authorizeAgentAccess($deal);
        $deal->load(['property', 'buyer', 'seller', 'agent', 'tokens', 'invoices', 'commissions', 'installmentPlan']);

        return view('deals.show', compact('deal'));
    }

    public function edit(Deal $deal)
    {
        $this->authorizeAgentAccess($deal);
        $properties = Property::orderBy('title')->get();
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();
        $statuses = ['inquiry', 'visit_scheduled', 'offer_made', 'token_received', 'agreement_signed', 'in_progress', 'completed', 'cancelled'];
        $leadSources = Status::leadSources();

        return view('deals.edit', compact('deal', 'properties', 'clients', 'agents', 'statuses', 'leadSources'));
    }

    public function update(Request $request, Deal $deal)
    {
        $this->authorizeAgentAccess($deal);
        $request->validate([
            'lead_source' => ['nullable', 'string', Rule::in(array_keys(Status::leadSources()))],
            'property_id' => 'required|exists:properties,id',
            'buyer_id' => 'required|exists:clients,id',
            'seller_id' => 'required|exists:clients,id',
            'agent_id' => 'nullable|exists:agents,id',
            'sale_price' => 'required|numeric|min:0',
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'commission_amount' => 'nullable|numeric|min:0',
            'agent_commission' => 'nullable|numeric|min:0',
            'agency_share' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:inquiry,visit_scheduled,offer_made,token_received,agreement_signed,in_progress,completed,cancelled',
            'agreement_date' => 'nullable|date',
            'possession_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data = $request->all();

        if (! empty($data['sale_price']) && ! empty($data['commission_percentage']) && empty($data['commission_amount'])) {
            $data['commission_amount'] = $data['sale_price'] * $data['commission_percentage'] / 100;
        }

        $oldStatus = $deal->status;
        $deal->update($data);

        if ($oldStatus !== $deal->status) {
            $recipients = [];
            if ($deal->agent && $deal->agent->user) {
                $recipients[] = $deal->agent->user;
            }
            notify_company($deal->company_id, DealStatusChanged::class, [$deal, $oldStatus], $recipients);
        }

        toastr()->success('Deal updated successfully.');

        return redirect()->route('deals.index');
    }

    public function destroy(Deal $deal)
    {
        $this->authorizeAgentAccess($deal);
        $deal->delete();
        toastr()->success('Deal deleted successfully.');

        return redirect()->route('deals.index');
    }

    public function export(Request $request)
    {
        [$headers, $rows] = DealExport::build();

        $headers = [$headers];
        $callback = function () use ($rows, $headers) {
            $handle = fopen('php://output', 'w');
            foreach ($headers as $header) {
                fputcsv($handle, $header);
            }
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        };

        return Response::stream($callback, 200, [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=deals-export-'.date('Y-m-d').'.csv',
        ]);
    }

    public function exportExcel(Request $request)
    {
        [$headers, $rows] = DealExport::build();

        return ExcelWriter::stream('deals-'.date('Y-m-d').'.xlsx', $headers, $rows);
    }

    public function trash()
    {
        $deals = Deal::onlyTrashed()->with(['property', 'buyer', 'seller', 'agent'])
            ->latest('deleted_at')->paginate(15);

        return view('deals.trash', compact('deals'));
    }

    public function restore(Deal $deal)
    {
        $deal->restore();
        toastr()->success('Deal restored successfully.');

        return redirect()->route('deals.trash');
    }

    public function forceDelete(Deal $deal)
    {
        $deal->forceDelete();
        toastr()->success('Deal permanently deleted.');

        return redirect()->route('deals.trash');
    }
}
