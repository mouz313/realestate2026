<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Deal;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Staff use a separate, permission-scoped dashboard.
        if ($user->isStaff()) {
            return redirect()->route('staff.dashboard');
        }

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

        $stats = [
            'total_clients' => Client::count(),
            'total_quotations' => Quotation::count(),
            'pending_quotations' => Quotation::whereIn('status', ['draft', 'sent'])->count(),
            'total_invoices' => Invoice::query()->tap($invoiceScope)->count(),
            'unpaid_invoices' => Invoice::query()->tap($invoiceScope)->where('payment_status', '!=', 'paid')->count(),
            'overdue_invoices' => Invoice::query()->tap($invoiceScope)->overdue()->count(),
            'total_revenue' => $totalRevenue,
            'outstanding' => $outstanding,
            'active_deals' => Deal::whereNotIn('status', ['cancelled', 'completed'])->count(),
            'active_properties' => Property::where('status', 'available')->count(),
            'monthly_quotations' => Quotation::where('created_at', '>=', now()->subMonths(6))
                ->select(DB::raw("{$monthSql} as month"), DB::raw('count(*) as total'))
                ->groupBy('month')->orderBy('month')->pluck('total', 'month'),
            'monthly_invoices' => Invoice::query()->tap($invoiceScope)->where('created_at', '>=', now()->subMonths(6))
                ->select(DB::raw("{$monthSql} as month"), DB::raw('count(*) as total'))
                ->groupBy('month')->orderBy('month')->pluck('total', 'month'),
        ];

        $recentPayments = Payment::with('invoice.client')
            ->when($isAgent, fn ($q) => $q->whereHas('invoice', $invoiceScope))
            ->latest()->take(6)->get();
        $recentQuotations = Quotation::with('client')->latest()->take(6)->get();

        return view('dashboard.index', compact('stats', 'recentPayments', 'recentQuotations'));
    }

    public function staffIndex()
    {
        $user = auth()->user();

        $monthSql = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";

        // Staff see company-wide aggregates; the staff dashboard view uses
        // @can(...) to reveal only what the staff member's permissions allow.
        $stats = [
            'total_clients' => Client::count(),
            'total_quotations' => Quotation::count(),
            'pending_quotations' => Quotation::whereIn('status', ['draft', 'sent'])->count(),
            'total_invoices' => Invoice::count(),
            'unpaid_invoices' => Invoice::where('payment_status', '!=', 'paid')->count(),
            'overdue_invoices' => Invoice::overdue()->count(),
            'total_revenue' => Payment::sum('amount'),
            'outstanding' => Invoice::where('payment_status', '!=', 'paid')->sum(DB::raw('total - paid_amount')),
            'active_deals' => Deal::whereNotIn('status', ['cancelled', 'completed'])->count(),
            'active_properties' => Property::where('status', 'available')->count(),
            'monthly_quotations' => Quotation::where('created_at', '>=', now()->subMonths(6))
                ->select(DB::raw("{$monthSql} as month"), DB::raw('count(*) as total'))
                ->groupBy('month')->orderBy('month')->pluck('total', 'month'),
            'monthly_invoices' => Invoice::where('created_at', '>=', now()->subMonths(6))
                ->select(DB::raw("{$monthSql} as month"), DB::raw('count(*) as total'))
                ->groupBy('month')->orderBy('month')->pluck('total', 'month'),
        ];

        $recentPayments = Payment::with('invoice.client')->latest()->take(6)->get();
        $recentQuotations = Quotation::with('client')->latest()->take(6)->get();
        $recentInvoices = Invoice::with('client')->latest()->take(6)->get();

        return view('dashboard.staff', compact('stats', 'recentPayments', 'recentQuotations', 'recentInvoices'));
    }
}
