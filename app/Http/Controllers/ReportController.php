<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Commission;
use App\Models\Deal;
use App\Models\RentAgreement;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private function agentFilter()
    {
        $user = auth()->user();

        return $user->isAgent() ? $user->agent_id : null;
    }

    public function index()
    {
        return view('reports.index');
    }

    public function salesReport(Request $request)
    {
        $start = $request->start ?: now()->startOfMonth()->toDateString();
        $end = $request->end ?: now()->endOfMonth()->toDateString();
        $agentId = $this->agentFilter();

        $deals = Deal::with(['property', 'buyer', 'agent'])
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end.' 23:59:59'])
            ->when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->get();

        $totalVolume = $deals->sum('sale_price');
        $totalCommission = $deals->sum('commission_amount');
        $dealCount = $deals->count();

        $monthlyData = Deal::select(
            DB::raw("strftime('%Y-%m', created_at) as month"),
            DB::raw('count(*) as count'),
            DB::raw('COALESCE(sum(sale_price), 0) as volume'),
            DB::raw('COALESCE(sum(commission_amount), 0) as commission')
        )
            ->where('status', 'completed')
            ->when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return view('reports.sales', compact('deals', 'totalVolume', 'totalCommission', 'dealCount', 'monthlyData', 'start', 'end'));
    }

    public function agentPerformance(Request $request)
    {
        $start = $request->start ?: now()->startOfYear()->toDateString();
        $end = $request->end ?: now()->endOfYear()->toDateString();
        $agentId = $this->agentFilter();

        $agents = Agent::with(['deals' => function ($q) use ($start, $end, $agentId) {
            $q->where('status', 'completed')
                ->whereBetween('created_at', [$start, $end.' 23:59:59']);
            if ($agentId) {
                $q->where('agent_id', $agentId);
            }
        }, 'commissions' => function ($q) use ($agentId) {
            $q->where('status', 'paid');
            if ($agentId) {
                $q->where('agent_id', $agentId);
            }
        }])
            ->where('status', 'active')
            ->when($agentId, fn ($q) => $q->where('id', $agentId))
            ->get()
            ->map(function ($agent) {
                return [
                    'name' => $agent->name,
                    'phone' => $agent->phone,
                    'deals_count' => $agent->deals->count(),
                    'total_volume' => $agent->deals->sum('sale_price'),
                    'commission_earned' => $agent->commissions->sum('amount'),
                    'rating' => $agent->deals->count() > 0 ? min(5, round($agent->commissions->sum('amount') / $agent->deals->count() / 10000, 1)) : 0,
                ];
            })
            ->sortByDesc('deals_count')
            ->values();

        return view('reports.agent-performance', compact('agents', 'start', 'end'));
    }

    public function commissionReport(Request $request)
    {
        $start = $request->start ?: now()->startOfYear()->toDateString();
        $end = $request->end ?: now()->endOfYear()->toDateString();
        $agentId = $this->agentFilter();

        $commissions = Commission::with(['agent', 'deal.property'])
            ->whereBetween('created_at', [$start, $end.' 23:59:59'])
            ->when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->latest()
            ->paginate(20);

        $totalPending = Commission::when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->where('status', 'pending')->sum('amount');
        $totalApproved = Commission::when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->where('status', 'approved')->sum('amount');
        $totalPaid = Commission::when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->where('status', 'paid')->sum('amount');

        return view('reports.commissions', compact('commissions', 'totalPending', 'totalApproved', 'totalPaid', 'start', 'end'));
    }

    public function rentRoll(Request $request)
    {
        $status = $request->status ?: 'active';
        $agentId = $this->agentFilter();

        $agreements = RentAgreement::with(['property', 'tenant', 'owner'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->when($agentId, fn ($q) => $q->whereHas('property', fn ($pq) => $pq->where('assigned_agent_id', $agentId)))
            ->latest()
            ->paginate(20);

        $totalMonthlyRent = RentAgreement::where('status', 'active')
            ->when($agentId, fn ($q) => $q->whereHas('property', fn ($pq) => $pq->where('assigned_agent_id', $agentId)))
            ->sum('rent_amount');
        $totalDeposits = RentAgreement::where('status', 'active')
            ->when($agentId, fn ($q) => $q->whereHas('property', fn ($pq) => $pq->where('assigned_agent_id', $agentId)))
            ->sum('security_deposit');
        $activeCount = RentAgreement::where('status', 'active')
            ->when($agentId, fn ($q) => $q->whereHas('property', fn ($pq) => $pq->where('assigned_agent_id', $agentId)))
            ->count();

        return view('reports.rent-roll', compact('agreements', 'totalMonthlyRent', 'totalDeposits', 'activeCount', 'status'));
    }

    public function exportSalesPdf(Request $request)
    {
        $start = $request->start ?: now()->startOfMonth()->toDateString();
        $end = $request->end ?: now()->endOfMonth()->toDateString();
        $settings = Setting::pluck('value', 'key')->toArray();
        $agentId = $this->agentFilter();

        $deals = Deal::with(['property', 'buyer', 'agent'])
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end.' 23:59:59'])
            ->when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->get();

        $totalVolume = $deals->sum('sale_price');
        $totalCommission = $deals->sum('commission_amount');

        $pdf = Pdf::loadView('pdf.sales-report', compact('deals', 'totalVolume', 'totalCommission', 'start', 'end', 'settings'));

        return $pdf->download('sales-report.pdf');
    }
}
