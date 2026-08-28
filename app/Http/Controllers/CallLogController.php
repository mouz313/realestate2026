<?php

namespace App\Http\Controllers;

use App\Helpers\Status;
use App\Helpers\WhatsApp;
use App\Models\Agent;
use App\Models\CallLog;
use App\Models\City;
use App\Models\Client;
use App\Models\Company;
use App\Models\Property;
use App\Services\DuplicateDetector;
use Illuminate\Http\Request;

class CallLogController extends Controller
{
    protected array $statuses = ['new', 'contacted', 'callback', 'matched', 'converted', 'lost'];
    protected array $leadSources;
    protected array $categories = ['house', 'plot', 'farmhouse', 'agricultural_land', 'flat', 'studio_apartment', 'office', 'shop'];
    protected array $transactionTypes = ['sale', 'buy', 'rent', 'installment'];

    public function __construct()
    {
        $this->leadSources = array_merge(['phone_call' => 'Phone Call'], Status::leadSources());
    }

    public function index(Request $request)
    {
        $query = CallLog::with(['client', 'property', 'assignedAgent']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($leadSource = $request->input('lead_source')) {
            $query->where('lead_source', $leadSource);
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($transactionType = $request->input('transaction_type')) {
            $query->where('transaction_type', $transactionType);
        }

        if ($request->input('due') == 1) {
            $query->whereNotNull('follow_up_date')
                  ->where('follow_up_date', '<=', today())
                  ->whereNotIn('status', ['converted', 'lost']);
        }

        $callLogs = $query->latest('call_datetime')->paginate(15)->withQueryString();

        return view('call_logs.index', [
            'callLogs' => $callLogs,
            'statuses' => $this->statuses,
            'leadSources' => $this->leadSources,
            'categories' => $this->categories,
            'transactionTypes' => $this->transactionTypes,
        ]);
    }

    public function create(Request $request)
    {
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();
        $cities = City::where('is_active', true)->orderBy('name')->get();

        // Pre-fill from query string (click-to-call / duplicate deep-link)
        $prefill = [
            'name' => $request->old('name') ?? $request->query('name'),
            'phone' => $request->old('phone') ?? $request->query('phone'),
            'category' => $request->old('category') ?? $request->query('category'),
            'transaction_type' => $request->old('transaction_type') ?? $request->query('transaction_type'),
        ];

        // Duplicate check
        $duplicates = [];
        if ($prefill['phone']) {
            $duplicates = DuplicateDetector::sameDay($prefill['phone']);
        }

        return view('call_logs.create', [
            'clients' => $clients,
            'agents' => $agents,
            'cities' => $cities,
            'statuses' => $this->statuses,
            'leadSources' => $this->leadSources,
            'categories' => $this->categories,
            'transactionTypes' => $this->transactionTypes,
            'prefill' => $prefill,
            'duplicates' => $duplicates,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'alternate_phone' => 'nullable|string|max:50',
            'lead_source' => 'nullable|string|max:50',
            'category' => 'nullable|string|in:' . implode(',', $this->categories),
            'transaction_type' => 'nullable|string|in:' . implode(',', $this->transactionTypes),
            'city' => 'nullable|string|max:100',
            'city_id' => 'nullable|exists:cities,id',
            'location' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'call_datetime' => 'nullable|date',
            'follow_up_date' => 'nullable|date',
            'status' => 'nullable|string|in:' . implode(',', $this->statuses),
            'assigned_agent_id' => 'nullable|exists:agents,id',
            'property_id' => 'nullable|exists:properties,id',
            'client_id' => 'nullable|exists:clients,id',
            'caller_role' => 'nullable|in:seller,buyer,rent,installment',
            'force_save' => 'nullable|boolean',
        ]);

        // Duplicate guard (skip if user explicitly forces save)
        if (! $request->boolean('force_save')) {
            $dups = DuplicateDetector::sameDay($data['phone']);
            if (count($dups) > 0) {
                toastr()->warning('Duplicate enquiry detected for this phone today. Review existing record or set force_save=1.');
                return back()
                    ->withInput()
                    ->withErrors(['phone' => 'Same phone already called today ('.count($dups).' record'.(count($dups)>1?'s':'').'). Confirm duplicate to proceed.']);
            }
        }

        $data['company_id'] = current_company_id();
        $data['created_by'] = auth()->user()->agent_id ?? null;
        unset($data['force_save']);

        CallLog::create($data);

        toastr()->success('Call logged successfully.');
        return redirect()->route('call-logs.index');
    }

    public function show(CallLog $callLog)
    {
        // Two-sided matching: a Seller call is matched against Buyer leads (other
        // buyer call logs); a Buyer/Unknown call is matched against available Properties.
        $matchType = $callLog->caller_role === 'seller' ? 'buyer' : 'property';

        if ($matchType === 'buyer') {
            $q = CallLog::where('id', '!=', $callLog->id)
                ->where('caller_role', 'buyer')
                ->where('status', '!=', 'lost');

            if ($callLog->category) {
                $q->where('category', $callLog->category);
            }
            if ($callLog->transaction_type) {
                $q->where('transaction_type', $callLog->transaction_type);
            }
            if ($callLog->city_id) {
                $q->where('city_id', $callLog->city_id);
            } elseif ($callLog->city) {
                $q->where('city', $callLog->city);
            }
            if ($callLog->budget_min) {
                $q->where('budget_max', '>=', $callLog->budget_min);
            }
            if ($callLog->budget_max) {
                $q->where('budget_min', '<=', $callLog->budget_max);
            }

            $buyerLeads = $q->with(['client', 'assignedAgent'])->take(20)->get();

            $buyerClients = Client::where('client_type', 'buyer')
                ->where('id', '!=', $callLog->client_id ?? 0)
                ->orderBy('name')
                ->take(20)
                ->get();

            return view('call_logs.show', compact('callLog', 'buyerLeads', 'buyerClients', 'matchType'));
        } else {
            $matches = \App\Services\PropertyMatcher::forLead($callLog);
        }

        return view('call_logs.show', compact('callLog', 'matches', 'matchType'));
    }

    public function edit(CallLog $callLog)
    {
        $clients = Client::orderBy('name')->get();
        $agents = Agent::orderBy('name')->get();
        $cities = City::where('is_active', true)->orderBy('name')->get();

        return view('call_logs.edit', [
            'callLog' => $callLog,
            'clients' => $clients,
            'agents' => $agents,
            'cities' => $cities,
            'statuses' => $this->statuses,
            'leadSources' => $this->leadSources,
            'categories' => $this->categories,
            'transactionTypes' => $this->transactionTypes,
        ]);
    }

    public function update(Request $request, CallLog $callLog)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'alternate_phone' => 'nullable|string|max:50',
            'lead_source' => 'nullable|string|max:50',
            'category' => 'nullable|string|in:' . implode(',', $this->categories),
            'transaction_type' => 'nullable|string|in:' . implode(',', $this->transactionTypes),
            'city' => 'nullable|string|max:100',
            'city_id' => 'nullable|exists:cities,id',
            'location' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'call_datetime' => 'nullable|date',
            'follow_up_date' => 'nullable|date',
            'status' => 'nullable|string|in:' . implode(',', $this->statuses),
            'assigned_agent_id' => 'nullable|exists:agents,id',
            'property_id' => 'nullable|exists:properties,id',
            'client_id' => 'nullable|exists:clients,id',
            'caller_role' => 'nullable|in:seller,buyer,rent,installment',
        ]);

        $callLog->update($data);

        toastr()->success('Call log updated successfully.');
        return redirect()->route('call-logs.index');
    }

    public function destroy(CallLog $callLog)
    {
        $callLog->delete();

        toastr()->success('Call log deleted successfully.');
        return redirect()->route('call-logs.index');
    }

    /**
     * Kanban board: enquiries grouped by status.
     */
    public function kanban(Request $request)
    {
        $columns = [
            'new' => ['label' => 'New', 'color' => 'primary'],
            'contacted' => ['label' => 'Contacted', 'color' => 'info'],
            'callback' => ['label' => 'Callback', 'color' => 'warning'],
            'matched' => ['label' => 'Matched', 'color' => 'secondary'],
            'converted' => ['label' => 'Converted', 'color' => 'success'],
            'lost' => ['label' => 'Lost', 'color' => 'danger'],
        ];

        $query = CallLog::with(['assignedAgent', 'property']);

        if ($request->input('due') == 1) {
            $query->whereNotNull('follow_up_date')
                  ->where('follow_up_date', '<=', today())
                  ->whereNotIn('status', ['converted', 'lost']);
        }

        if ($agentId = $request->input('agent_id')) {
            $query->where('assigned_agent_id', $agentId);
        }

        $all = $query->latest('call_datetime')->limit(500)->get();

        $board = [];
        foreach ($columns as $key => $meta) {
            $board[$key] = [
                'meta' => $meta,
                'cards' => $all->where('status', $key)->take(50)->values(),
            ];
        }

        $agents = Agent::orderBy('name')->get();

        return view('call_logs.kanban', compact('board', 'agents'));
    }

    /**
     * AJAX: update status (drag-drop on kanban).
     */
    public function updateStatus(Request $request, CallLog $callLog)
    {
        $request->validate([
            'status' => 'required|string|in:' . implode(',', $this->statuses),
        ]);

        $callLog->update(['status' => $request->input('status')]);

        return response()->json(['ok' => true, 'status' => $callLog->status]);
    }

    /**
     * AJAX: find properties matching a lead's city + category + transaction type,
     * so an agent can attach a relevant property to the call log before saving.
     */
    public function matchProperties(Request $request)
    {
        $cityId = $request->input('city_id');
        $category = $request->input('category');
        $leadType = $request->input('transaction_type');

        if (! $cityId || ! $category) {
            return response()->json(['matches' => []]);
        }

        $matches = \App\Services\PropertyMatcher::buildQuery([
            'city_id' => $cityId,
            'category' => $category,
            'transaction_type' => \App\Services\PropertyMatcher::propertyType($leadType),
        ])->latest()->limit(8)->get([
            'id', 'property_code', 'title', 'price', 'currency',
            'city', 'sector_town', 'category', 'transaction_type', 'status',
        ]);

        return response()->json([
            'matches' => $matches->map(function ($p) {
                return [
                    'id' => $p->id,
                    'label' => $p->title ?: $p->property_code,
                    'price' => $p->price,
                    'currency' => $p->currency ?? 'PKR',
                    'city' => $p->city,
                    'sector_town' => $p->sector_town,
                    'category' => $p->category,
                    'transaction_type' => $p->transaction_type,
                    'url' => route('properties.show', $p),
                ];
            }),
        ]);
    }
}
