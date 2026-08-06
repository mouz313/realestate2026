<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DealController extends Controller
{
    public function index(Request $request)
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $deals = Deal::with(['property', 'buyer', 'seller', 'agent'])
            ->when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
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

        return view('deals.index', compact('deals', 'statuses'));
    }

    public function create()
    {
        $properties = Property::where('status', 'available')->orderBy('title')->get();
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();
        $statuses = ['inquiry', 'visit_scheduled', 'offer_made', 'token_received', 'agreement_signed', 'in_progress', 'completed', 'cancelled'];
        $defaultAgentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;

        return view('deals.create', compact('properties', 'clients', 'agents', 'statuses', 'defaultAgentId'));
    }

    public function store(Request $request)
    {
        $request->validate([
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

        return view('deals.edit', compact('deal', 'properties', 'clients', 'agents', 'statuses'));
    }

    public function update(Request $request, Deal $deal)
    {
        $this->authorizeAgentAccess($deal);
        $request->validate([
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

        $deal->update($data);
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
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $deals = Deal::with(['property', 'buyer', 'seller', 'agent'])
            ->when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->latest()
            ->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=deals-export-'.date('Y-m-d').'.csv',
        ];

        $callback = function () use ($deals) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Deal #', 'Type', 'Status', 'Property', 'Buyer', 'Seller', 'Agent', 'Sale Price', 'Commission %', 'Commission Amount', 'Agreement Date', 'Possession Date']);

            foreach ($deals as $deal) {
                fputcsv($handle, [
                    $deal->deal_number,
                    $deal->type,
                    str_replace('_', ' ', $deal->status),
                    $deal->property?->title ?? '',
                    $deal->buyer?->name ?? '',
                    $deal->seller?->name ?? '',
                    $deal->agent?->name ?? '',
                    $deal->sale_price,
                    $deal->commission_percentage ?? '',
                    $deal->commission_amount ?? '',
                    $deal->agreement_date?->format('Y-m-d') ?? '',
                    $deal->possession_date?->format('Y-m-d') ?? '',
                ]);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
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
