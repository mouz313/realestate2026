<?php

namespace App\Http\Controllers;

use App\Exports\DealExport;
use App\Helpers\Status;
use App\Models\Agent;
use App\Models\CallLog;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Property;
use App\Models\PropertyVisit;
use App\Notifications\DealStatusChanged;
use App\Services\ExcelWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $prefill = [];
        if ($callLog = CallLog::find(request('call_log_id') ?? request('call_log'))) {
            $prefill = [
                'call_log_id' => $callLog->id,
                'lead_source' => $callLog->lead_source,
                'property_id' => $callLog->property_id,
            ];
            if ($callLog->caller_role === 'seller') {
                $prefill['seller_name'] = $callLog->name;
                $prefill['seller_phone'] = $callLog->phone;
            } else {
                $prefill['buyer_name'] = $callLog->name;
                $prefill['buyer_phone'] = $callLog->phone;
            }
        }

        if ($visit = PropertyVisit::find(request('visit_id'))) {
            $prefill['visit_id'] = $visit->id;
            $prefill['property_id'] = $prefill['property_id'] ?? $visit->property_id;
            if ($visit->agent_id) {
                $prefill['agent_id'] = $visit->agent_id;
            }
            if ($visit->client_id && ! isset($prefill['buyer_id'])) {
                $prefill['buyer_id'] = $visit->client_id;
                $prefill['buyer_name'] = optional($visit->client)->name;
            } elseif ($visit->client_name && ! isset($prefill['buyer_id'])) {
                $prefill['buyer_name'] = $visit->client_name;
                $prefill['buyer_phone'] = $visit->client_phone;
            }
        }

        return view('deals.create', compact('properties', 'clients', 'agents', 'statuses', 'defaultAgentId', 'leadSources', 'prefill'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lead_source' => ['nullable', 'string', Rule::in(array_keys(Status::leadSources()))],
            'call_log_id' => 'nullable|exists:call_logs,id',
            'visit_id' => 'nullable|exists:property_visits,id',
            'property_id' => 'required|exists:properties,id',
            'buyer_id' => 'nullable|exists:clients,id',
            'buyer_name' => 'nullable|string|max:255',
            'buyer_phone' => 'nullable|string|max:50',
            'seller_id' => 'nullable|exists:clients,id',
            'seller_name' => 'nullable|string|max:255',
            'seller_phone' => 'nullable|string|max:50',
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

        $data['buyer_id'] = $this->resolveClient(
            $request->input('buyer_id'),
            $request->input('buyer_name'),
            $request->input('buyer_phone'),
            'buyer'
        );
        $data['seller_id'] = $this->resolveClient(
            $request->input('seller_id'),
            $request->input('seller_name'),
            $request->input('seller_phone'),
            'seller'
        );

        if (auth()->user()->isAgent() && empty($data['agent_id'])) {
            $data['agent_id'] = auth()->user()->agent_id;
        }

        $data['deal_number'] = DB::transaction(function () {
            $last = Deal::withTrashed()->lockForUpdate()->orderBy('id', 'desc')->first();
            $nextId = $last ? $last->id + 1 : 1;

            return 'DL-'.str_pad($nextId, 5, '0', STR_PAD_LEFT);
        });

        if (! empty($data['sale_price']) && ! empty($data['commission_percentage']) && empty($data['commission_amount'])) {
            $data['commission_amount'] = $data['sale_price'] * $data['commission_percentage'] / 100;
        }

        $deal = Deal::create($data);

        if ($callLogId = $request->input('call_log_id')) {
            CallLog::where('id', $callLogId)->update(['deal_id' => $deal->id, 'status' => 'converted', 'matched_at' => now()]);
        }

        $this->syncDealExtras($request, $deal);

        toastr()->success('Deal added successfully.');

        return redirect()->route('deals.index');
    }

    /**
     * Resolve a client from either an existing id or inline name/phone.
     * A client is created only when an actual deal happens (no id given but name present).
     */
    protected function resolveClient(?string $id, ?string $name, ?string $phone, string $type): ?int
    {
        if (! empty($id)) {
            return (int) $id;
        }

        if (! empty($name)) {
            $client = Client::create([
                'name' => $name,
                'phone' => $phone,
                'client_type' => $type,
                'company_id' => current_company_id(),
            ]);

            return $client->id;
        }

        return null;
    }

    /**
     * Link a deal to its originating enquiry / visit and auto-create the
     * closing agent's commission.
     *
     * Commission model (see project notes):
     *  - The agency's fee = commission_rate% of the deal value (sale price, or
     *    monthly rent for rentals), collected 50% from landlord + 50% from tenant.
     *  - The agency keeps 90% and pays the closing agent 10% of the total
     *    collected commission. commission_amount = full fee, agent_commission
     *    = 10%, agency_share = 90%.
     */
    protected function syncDealExtras(Request $request, Deal $deal): void
    {
        if ($visitId = $request->input('visit_id')) {
            PropertyVisit::where('id', $visitId)->update([
                'deal_id' => $deal->id,
                'call_log_id' => $deal->call_log_id ?? null,
            ]);
        }

        if (! $deal->agent_id) {
            return;
        }

        $split = \App\Services\CommissionCalculator::forDeal($deal);

        if ($split['commission_amount'] <= 0) {
            return;
        }

        $deal->commission_amount = $split['commission_amount'];
        $deal->agent_commission = $split['agent_amount'];
        $deal->agency_share = $split['agency_amount'];
        $deal->save();

        $deal->commissions()->updateOrCreate(
            ['agent_id' => $deal->agent_id],
            [
                'company_id' => $deal->company_id,
                'type' => $deal->type ?? 'sale',
                'percentage' => $split['rate'],
                'amount' => $split['agent_amount'],
                'agency_amount' => $split['agency_amount'],
                'agent_amount' => $split['agent_amount'],
                'source' => $deal->type ?? 'sale',
                'status' => 'pending',
            ]
        );
    }

    public function show(Deal $deal)
    {
        $this->authorizeAgentAccess($deal);
        $deal->load(['property', 'buyer', 'seller', 'agent', 'tokens', 'invoices', 'commissions']);

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
            'visit_id' => 'nullable|exists:property_visits,id',
            'property_id' => 'required|exists:properties,id',
            'buyer_id' => 'nullable|exists:clients,id',
            'buyer_name' => 'nullable|string|max:255',
            'buyer_phone' => 'nullable|string|max:50',
            'seller_id' => 'nullable|exists:clients,id',
            'seller_name' => 'nullable|string|max:255',
            'seller_phone' => 'nullable|string|max:50',
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

        $data['buyer_id'] = $this->resolveClient(
            $request->input('buyer_id'),
            $request->input('buyer_name'),
            $request->input('buyer_phone'),
            'buyer'
        );
        $data['seller_id'] = $this->resolveClient(
            $request->input('seller_id'),
            $request->input('seller_name'),
            $request->input('seller_phone'),
            'seller'
        );

        if (! empty($data['sale_price']) && ! empty($data['commission_percentage']) && empty($data['commission_amount'])) {
            $data['commission_amount'] = $data['sale_price'] * $data['commission_percentage'] / 100;
        }

        $oldStatus = $deal->status;
        $deal->update($data);

        $this->syncDealExtras($request, $deal);

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
