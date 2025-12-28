<?php

namespace App\Http\Controllers\User\Event;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\EventDivMember;
use App\Models\Event;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Event $event)
    {
        $budgets = Budget::where('event_id', $event->event_id)->get();

        // Summary count
        $summary = [
            'Pending' => $budgets->where('status', 'Pending')->count(),
            'Approved' => $budgets->where('status', 'Approved')->count(),
            'Paid' => $budgets->where('status', 'Paid')->count(),
        ];

        // cek apakah user adalah treasurer
        $isTreasurer = EventDivMember::where('user_id', auth()->id())
            ->whereHas('division.divisionType', function ($q) {
                $q->where('type_name', 'Treasurer');
            })
            ->exists();

        return view('user.event.budget.index', compact(
            'event',
            'budgets',
            'summary',
            'isTreasurer'
        ));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'budget_type' => 'required|string',
            'budget_item' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        Budget::create([
            'event_id' => $event->event_id,
            'budget_type' => $request->budget_type,
            'budget_item' => $request->budget_item,
            'amount' => $request->amount,
            'status' => 'Pending', // AUTO
        ]);

        return back()->with('success', 'Budget added 💸');
    }

    public function update(Request $request, Event $event, Budget $budget)
    {
        // SECURITY GATE 🔐
        if (!$this->isTreasurer()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:Pending,Approved,Paid',
        ]);

        $budget->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Budget updated ✅');
    }

    private function isTreasurer()
    {
        return EventDivMember::where('user_id', auth()->id())
            ->whereHas('division.divisionType', function ($q) {
                $q->where('type_name', 'Treasurer');
            })
            ->exists();
    }
}
