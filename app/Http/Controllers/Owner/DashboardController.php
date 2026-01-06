<?php
// app/Http/Controllers/Owner/DashboardController.php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Budget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $chartYear = $request->get('chart_year', now()->year);
        $chartMonth = $request->get('chart_month');
        $chartEventId = $request->get('chart_event_id');

        $tableYear = $request->get('table_year');
        $tableMonth = $request->get('table_month');
        $sortBy = $request->get('sort_by', 'date'); // date, revenue, profit

        $totalRevenue = $this->getTotalRevenue();
        $totalEvents = Event::count();
        $activeEvents = Event::where('event_date', '>=', now())->count();
        $teamMembers = User::where('role', 'User')->count();

        $allEvents = Event::orderBy('event_name')->get();
        
        $years = Event::selectRaw('EXTRACT(YEAR FROM event_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        if ($years->isEmpty()) {
            $years = collect([now()->year]);
        }

        $revenueData = $this->getRevenueChartData($chartYear, $chartMonth, $chartEventId);

        $eventDistribution = $this->getEventDistribution($chartYear, $chartMonth, $chartEventId);

        $eventPerformance = $this->getEventPerformance($tableYear, $tableMonth, $sortBy);

        return view('owner.dashboard', compact(
            'totalRevenue',
            'totalEvents',
            'activeEvents',
            'teamMembers',
            'allEvents',
            'years',
            'chartYear',
            'chartMonth',
            'chartEventId',
            'tableYear',
            'tableMonth',
            'sortBy',
            'revenueData',
            'eventDistribution',
            'eventPerformance'
        ));
    }

    private function getTotalRevenue()
    {
        return Budget::where('transaction_type', 'income')
            ->sum('amount');
    }

    private function getRevenueChartData($year, $month, $eventId)
    {
        $query = Budget::where('transaction_type', 'income')
            ->where('status', 'Paid')
            ->whereYear('payment_date', $year);

        if ($month) {
            $query->whereMonth('payment_date', $month);
        }

        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        $data = $query->selectRaw('EXTRACT(MONTH FROM payment_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = [
                'month' => date('M', mktime(0, 0, 0, $i, 1)),
                'revenue' => $data[$i] ?? 0
            ];
        }

        return $chartData;
    }

    private function getEventDistribution($year, $month, $eventId)
    {
        $query = Event::with('category')
            ->whereYear('event_date', $year);

        if ($month) {
            $query->whereMonth('event_date', $month);
        }

        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        $events = $query->get();

        $distribution = $events->groupBy('category_id')
            ->map(function($group) {
                return [
                    'category' => $group->first()->category->category_name ?? 'Uncategorized',
                    'count' => $group->count()
                ];
            })
            ->values();

        return $distribution;
    }

    private function getEventPerformance($year, $month, $sortBy)
    {
        $query = Event::with(['category']);

        if ($year) {
            $query->whereYear('event_date', $year);
        }

        if ($month) {
            $query->whereMonth('event_date', $month);
        }

        $events = $query->get()->map(function($event) {
            $totalExpense = Budget::where('event_id', $event->event_id)
                ->where('transaction_type', 'expense')
                ->sum('amount');

            $totalRevenue = Budget::where('event_id', $event->event_id)
                ->where('transaction_type', 'income')
                ->sum('amount');

            $profit = $totalRevenue - $totalExpense;

            $status = $event->event_date > now() ? 'Upcoming' : 'Completed';

            return [
                'event_id' => $event->event_id,
                'event_name' => $event->event_name,
                'category' => $event->category->category_name ?? '-',
                'date' => $event->event_date,
                'status' => $status,
                'budget' => $totalExpense,
                'revenue' => $totalRevenue,
                'profit' => $profit,
            ];
        });

        if ($sortBy === 'revenue') {
            $events = $events->sortByDesc('revenue')->values();
        } elseif ($sortBy === 'profit') {
            $events = $events->sortByDesc('profit')->values();
        } else {
            $events = $events->sortByDesc('date')->values();
        }

        return $events;
    }
}