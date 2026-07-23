<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Installment;
use App\Models\InstallmentPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function index()
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $installments = Installment::with('plan.deal')
            ->when($agentId, fn ($q) => $q->whereHas('plan.deal', fn ($dq) => $dq->where('agent_id', $agentId)))
            ->latest()->paginate(15);

        return view('installments.index', compact('installments'));
    }

    public function create()
    {
        $agentId = auth()->user()->isAgent() ? auth()->user()->agent_id : null;
        $deals = Deal::when($agentId, fn ($q) => $q->where('agent_id', $agentId))
            ->orderBy('deal_number')->get();
        $frequencies = ['monthly', 'quarterly', 'semi_annually', 'annually'];

        return view('installments.create', compact('deals', 'frequencies'));
    }

    public function markPaid(Installment $installment)
    {
        $installment->update(['status' => 'paid', 'paid_at' => now()]);
        toastr()->success('Installment marked as paid.');

        return back();
    }

    public function store(Request $request)
    {
        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'total_installments' => 'required|integer|min:1',
            'installment_amount' => 'required|numeric|min:0',
            'frequency' => 'required|string|in:monthly,quarterly,semi_annually,annually',
            'start_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $plan = InstallmentPlan::create([
            'deal_id' => $request->deal_id,
            'total_installments' => $request->total_installments,
            'installment_amount' => $request->installment_amount,
            'frequency' => $request->frequency,
            'start_date' => $request->start_date,
            'notes' => $request->notes,
        ]);

        $frequencyMap = [
            'monthly' => 'months',
            'quarterly' => 'months',
            'semi_annually' => 'months',
            'annually' => 'years',
        ];

        $frequencyCount = [
            'monthly' => 1,
            'quarterly' => 3,
            'semi_annually' => 6,
            'annually' => 1,
        ];

        $startDate = Carbon::parse($request->start_date);

        for ($i = 1; $i <= $request->total_installments; $i++) {
            $dueDate = $startDate->copy();
            if ($request->frequency === 'monthly') {
                $dueDate->addMonths($i - 1);
            } elseif ($request->frequency === 'quarterly') {
                $dueDate->addMonths(($i - 1) * 3);
            } elseif ($request->frequency === 'semi_annually') {
                $dueDate->addMonths(($i - 1) * 6);
            } elseif ($request->frequency === 'annually') {
                $dueDate->addYears($i - 1);
            }

            Installment::create([
                'installment_plan_id' => $plan->id,
                'installment_no' => $i,
                'amount' => $request->installment_amount,
                'due_date' => $dueDate,
                'status' => 'pending',
            ]);
        }

        toastr()->success('Installment plan created successfully.');

        return redirect()->route('installments.index');
    }

    public function edit(InstallmentPlan $installmentPlan)
    {
        $this->authorizeAgentAccess($installmentPlan->deal);
        $deals = Deal::orderBy('deal_number')->get();
        $frequencies = ['monthly', 'quarterly', 'semi_annually', 'annually'];

        return view('installments.edit', compact('installmentPlan', 'deals', 'frequencies'));
    }

    public function update(Request $request, InstallmentPlan $installmentPlan)
    {
        $this->authorizeAgentAccess($installmentPlan->deal);
        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'total_installments' => 'required|integer|min:1',
            'installment_amount' => 'required|numeric|min:0',
            'frequency' => 'required|string|in:monthly,quarterly,semi_annually,annually',
            'start_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $installmentPlan->update($request->all());
        toastr()->success('Installment plan updated successfully.');

        return redirect()->route('installments.index');
    }

    public function destroy(InstallmentPlan $installmentPlan)
    {
        $this->authorizeAgentAccess($installmentPlan->deal);
        $installmentPlan->installments()->delete();
        $installmentPlan->delete();
        toastr()->success('Installment plan deleted successfully.');

        return redirect()->route('installments.index');
    }
}
