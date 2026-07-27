<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Deal;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['agent', 'deal']);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('date_from')) {
            $query->where('expense_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('expense_date', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhere('notes', 'like', "%{$s}%")
                    ->orWhere('reference_no', 'like', "%{$s}%");
            });
        }

        $expenses = $query->latest('expense_date')->paginate(20)->withQueryString();
        $totalAmount = (clone $query)->withoutPagination()->sum('amount');
        $categories = Expense::select('category')->distinct()->pluck('category');

        return view('admin.expenses.index', compact('expenses', 'totalAmount', 'categories'));
    }

    public function create()
    {
        $agents = Agent::orderBy('name')->get();
        $deals = Deal::orderBy('deal_number')->get();
        $categories = [
            'office_rent', 'utilities', 'marketing', 'travel',
            'commission_payout', 'legal', 'maintenance', 'staff', 'other',
        ];

        return view('admin.expenses.create', compact('agents', 'deals', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'agent_id' => 'nullable|exists:agents,id',
            'deal_id' => 'nullable|exists:deals,id',
            'payment_method' => 'nullable|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        Expense::create($request->only((new Expense)->getFillable()));
        toastr()->success('Expense added successfully.');

        return redirect()->route('expenses.index');
    }

    public function edit(Expense $expense)
    {
        $agents = Agent::orderBy('name')->get();
        $deals = Deal::orderBy('deal_number')->get();
        $categories = [
            'office_rent', 'utilities', 'marketing', 'travel',
            'commission_payout', 'legal', 'maintenance', 'staff', 'other',
        ];

        return view('admin.expenses.edit', compact('expense', 'agents', 'deals', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'agent_id' => 'nullable|exists:agents,id',
            'deal_id' => 'nullable|exists:deals,id',
            'payment_method' => 'nullable|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $expense->update($request->only((new Expense)->getFillable()));
        toastr()->success('Expense updated successfully.');

        return redirect()->route('expenses.index');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        toastr()->success('Expense deleted successfully.');

        return redirect()->route('expenses.index');
    }
}
