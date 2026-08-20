<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Agent as AgentModel;
use App\Models\Client;
use App\Models\Commission;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Property;
use App\Models\PropertyVisit;
use App\Models\Quotation;
use App\Models\Referral;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isAgent = $user->isAgent();

        $monthSql = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";

        // Financial aggregates: agents only see amounts tied to their own invoices/deals.
        $invoiceScope = $isAgent
            ? fn ($q) => $q->where('agent_id', $user->agent_id)
            : fn ($q) => $q;

        $totalRevenue = Payment::whereHas('invoice', $invoiceScope)->sum('amount');
        $outstanding = Invoice::query()->tap($invoiceScope)->where('payment_status', '!=', 'paid')->sum(DB::raw('total - paid_amount'));
        $totalExpenses = $isAgent ? 0 : Expense::sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;

        $stats = [
            'total_clients' => Client::count(),
            'total_quotations' => Quotation::count(),
            'pending_quotations' => Quotation::whereIn('status', ['draft', 'sent'])->count(),
            'total_invoices' => Invoice::query()->tap($invoiceScope)->count(),
            'unpaid_invoices' => Invoice::query()->tap($invoiceScope)->where('payment_status', '!=', 'paid')->count(),
            'overdue_invoices' => Invoice::query()->tap($invoiceScope)->overdue()->count(),
            'total_revenue' => $totalRevenue,
            'outstanding' => $outstanding,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
            'conversion_rate' => Quotation::count() > 0
                ? round((Quotation::where('status', 'invoiced')->count() / Quotation::count()) * 100, 1)
                : 0,
            'avg_deal_size' => round(Invoice::query()->tap($invoiceScope)->avg('total') ?? 0, 0),
            'monthly_quotations' => Quotation::where('created_at', '>=', now()->subMonths(6))
                ->select(DB::raw("{$monthSql} as month"), DB::raw('count(*) as total'))
                ->groupBy('month')->orderBy('month')->pluck('total', 'month'),
            'monthly_invoices' => Invoice::query()->tap($invoiceScope)->where('created_at', '>=', now()->subMonths(6))
                ->select(DB::raw("{$monthSql} as month"), DB::raw('count(*) as total'))
                ->groupBy('month')->orderBy('month')->pluck('total', 'month'),
            'monthly_revenue' => Payment::whereHas('invoice', $invoiceScope)->where('created_at', '>=', now()->subMonths(6))
                ->select(DB::raw("{$monthSql} as month"), DB::raw('sum(amount) as total'))
                ->groupBy('month')->orderBy('month')->pluck('total', 'month'),
            'monthly_expenses' => $isAgent ? collect() : Expense::where('created_at', '>=', now()->subMonths(6))
                ->select(DB::raw("{$monthSql} as month"), DB::raw('sum(amount) as total'))
                ->groupBy('month')->orderBy('month')->pluck('total', 'month'),
            'active_properties' => Property::where('status', 'available')->count(),
            'active_agents' => $isAgent ? 0 : AgentModel::where('status', 'active')->count(),
            'active_deals' => Deal::whereNotIn('status', ['cancelled', 'completed'])->count(),
            'pending_commissions' => Commission::where('status', 'pending')->sum('amount'),
            'total_commission_paid' => Commission::where('status', 'paid')->sum('amount'),
            'upcoming_visits' => PropertyVisit::where('scheduled_date', '>=', now())->where('status', 'scheduled')->count(),
            'new_enquiries' => Contact::whereNull('read_at')->count(),
            'lead_sources' => Contact::select('lead_source', DB::raw('count(*) as total'))
                ->groupBy('lead_source')->orderByDesc('total')->pluck('total', 'lead_source'),
            'properties_by_status' => Property::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')->pluck('total', 'status'),
            'deals_by_status' => Deal::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')->pluck('total', 'status'),
            'total_deal_value' => Deal::whereNotIn('status', ['cancelled'])->sum('sale_price'),
        ];

        $recentPayments = Payment::with('invoice.client')
            ->when($isAgent, fn ($q) => $q->whereHas('invoice', $invoiceScope))
            ->latest()->take(5)->get();
        $recentQuotations = Quotation::with('client')->latest()->take(5)->get();
        $recentDeals = Deal::with(['property', 'buyer', 'agent'])->latest()->take(5)->get();
        $upcomingVisits = PropertyVisit::with(['property', 'client', 'agent'])
            ->where('scheduled_date', '>=', now())->where('status', 'scheduled')->orderBy('scheduled_date')->take(5)->get();
        $recentActivities = $isAgent ? collect() : Activity::with('causer')->latest()->take(8)->get();

        $newEnquiries = Contact::with('property')->latest()->take(6)->get();
        $recentReferrals = Referral::latest()->take(5)->get();

        return view('dashboard.index', compact(
            'stats', 'recentPayments', 'recentQuotations', 'recentDeals', 'upcomingVisits', 'recentActivities',
            'newEnquiries', 'recentReferrals'
        ));
    }
}
