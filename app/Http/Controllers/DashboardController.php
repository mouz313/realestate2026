<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Quotation;
use App\Models\Property;
use App\Models\Agent as AgentModel;
use App\Models\Commission;
use App\Models\PropertyVisit;
use App\Models\RentAgreement;
use App\Models\Deal;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $agentId = $user->isAgent() ? $user->agent_id : null;

        $totalRevenue = Payment::sum('amount');
        $outstanding = Invoice::where('payment_status', '!=', 'paid')->sum(\Illuminate\Support\Facades\DB::raw('total - paid_amount'));

        $stats = [
            'total_clients' => Client::count(),
            'total_items' => Item::count(),
            'total_quotations' => Quotation::count(),
            'pending_quotations' => Quotation::whereIn('status', ['draft', 'sent'])->count(),
            'total_invoices' => Invoice::count(),
            'unpaid_invoices' => Invoice::where('payment_status', '!=', 'paid')->count(),
            'overdue_invoices' => Invoice::overdue()->count(),
            'total_revenue' => $totalRevenue,
            'outstanding' => $outstanding,
            'conversion_rate' => Quotation::count() > 0
                ? round((Quotation::where('status', 'invoiced')->count() / Quotation::count()) * 100, 1)
                : 0,
            'active_properties' => Property::when($agentId, fn($q) => $q->where('assigned_agent_id', $agentId))
                ->where('status', 'available')->count(),
            'active_agents' => AgentModel::where('status', 'active')->count(),
            'active_deals' => Deal::when($agentId, fn($q) => $q->where('agent_id', $agentId))
                ->whereNotIn('status', ['cancelled', 'completed'])->count(),
            'pending_commissions' => Commission::when($agentId, fn($q) => $q->where('agent_id', $agentId))
                ->where('status', 'pending')->sum('amount'),
            'total_commission_paid' => Commission::when($agentId, fn($q) => $q->where('agent_id', $agentId))
                ->where('status', 'paid')->sum('amount'),
            'upcoming_visits' => PropertyVisit::when($agentId, fn($q) => $q->where('agent_id', $agentId))
                ->where('scheduled_date', '>=', now())->where('status', 'scheduled')->count(),
            'active_rentals' => RentAgreement::when($agentId, fn($q) => $q->whereHas('property', fn($pq) => $pq->where('assigned_agent_id', $agentId)))
                ->where('status', 'active')->count(),
        ];

        $recentPayments = Payment::with('invoice.client')->latest()->take(5)->get();
        $recentQuotations = Quotation::with('client')->latest()->take(5)->get();
        $recentDeals = Deal::with(['property', 'buyer', 'agent'])
            ->when($agentId, fn($q) => $q->where('agent_id', $agentId))
            ->latest()->take(5)->get();
        $upcomingVisits = PropertyVisit::with(['property', 'client', 'agent'])
            ->when($agentId, fn($q) => $q->where('agent_id', $agentId))
            ->where('scheduled_date', '>=', now())->where('status', 'scheduled')->orderBy('scheduled_date')->take(5)->get();

        return view('dashboard.index', compact('stats', 'recentPayments', 'recentQuotations', 'recentDeals', 'upcomingVisits'));
    }
}
