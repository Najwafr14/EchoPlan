<?php
// app/Http/Controllers/User/Event/BudgetController.php

namespace App\Http\Controllers\User\Event;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index($eventId)
    {
        $event = Event::findOrFail($eventId);
        $budgets = Budget::where('event_id', $eventId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalIncome = Budget::where('event_id', $eventId)
            ->where('transaction_type', 'income')
            ->sum('amount');
            
        $totalExpense = Budget::where('event_id', $eventId)
            ->where('transaction_type', 'expense')
            ->sum('amount');
            
        $balance = $totalIncome - $totalExpense;
        
        $summary = [
            'Income' => $totalIncome,
            'Expense' => $totalExpense,
            'Balance' => $balance,
            'Pending' => Budget::where('event_id', $eventId)->where('status', 'Pending')->count(),
        ];
        
        return view('user.event.budget.index', compact('event', 'budgets', 'summary', 'eventId'));
    }

    public function store(Request $request, $eventId)
    {
        $validated = $request->validate([
            'transaction_type' => 'required|in:income,expense',
            'budget_type' => 'required|string|max:255',
            'budget_item' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:100',
            'payment_date' => 'nullable|date',
            'status' => 'required|in:Pending,Approved,Paid',
            'notes' => 'nullable|string',
        ]);

        $validated['event_id'] = $eventId;
        $validated['created_by'] = Auth::id();

        Budget::create($validated);

        return redirect()->route('user.event.budget.index', $eventId)
            ->with('success', 'Budget item added successfully!');
    }

    public function update(Request $request, $eventId, $budgetId)
    {
        $budget = Budget::findOrFail($budgetId);

        $validated = $request->validate([
            'status' => 'required|in:Pending,Approved,Paid',
            'payment_method' => 'nullable|string|max:100',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['approved_by'] = Auth::id();

        $budget->update($validated);

        return redirect()->route('user.event.budget.index', $eventId)
            ->with('success', 'Budget updated successfully!');
    }
}