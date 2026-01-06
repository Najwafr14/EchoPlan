<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">☰</a>
    </div>
    <div class="page-container">
        <div class="card mb-6">
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-bold">
                    Event Report
                </h1>
            </div>
            <form method="GET" action="{{ route('owner.report.index') }}" class="p-4">
                <div class="flex gap-4 items-end">
                    <div class="flex-1">
                        <label class="form-label">Select Event</label>
                        <select name="event_id" class="form-control px-7 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">-- Choose Event --</option>
                            @foreach($events as $evt)
                                <option value="{{ $evt->event_id }}" 
                                    {{ isset($selectedEventId) && $selectedEventId == $evt->event_id ? 'selected' : '' }}>
                                    {{ $evt->event_name }} - {{ \Carbon\Carbon::parse($evt->event_date)->format('d M Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        Show Report
                    </button>
                </div>
            </form>
        </div>

        @if(isset($event))
        <div class="card mb-6">
            <div class="card-header flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold">{{ $event->event_name }}</h2>
                    <div class="flex gap-2 mt-2">
                        @if(\Carbon\Carbon::parse($event->event_date)->isPast())
                            <span class="badge badge-inactive">Completed</span>
                        @else
                            <span class="badge badge-active">Active</span>
                        @endif
                        <span class="badge" style="background-color: #e0e7ff; color: #4338ca;">
                            {{ $event->category->category_name ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Chief of Committee</p>
                    <p class="font-medium">{{ $event->creator->full_name ?? '-' }}</p>
                </div>
            </div>

            <div class="card-body space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Description</p>
                    <p class="text-gray-800">{{ $event->event_description ?? 'No description provided' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Date</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}</p>
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->event_date)->format('H:i') }} WIB</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Venue</p>
                    <p class="font-medium">{{ $event->primaryVenue->venue_name ?? 'To Be Decided' }}</p>
                    @if($event->primaryVenue)
                        <p class="text-sm text-gray-600">{{ $event->primaryVenue->venue_address ?? '-' }}</p>
                    @endif
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-sm text-gray-500">Overall Progress</p>
                        <p class="text-sm font-semibold">{{ $progressPercent }}%</p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-blue-600 h-4 rounded-full transition-all duration-300" 
                             style="width: {{ $progressPercent }}%"></div>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ $completedTasks }} of {{ $totalTasks }} tasks completed
                    </p>
                </div>
            </div>
        </div>

        <div class="card mb-6">
            <div class="card-header">
                <h3 class="text-xl font-semibold">Financial Summary</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                        <p class="text-2xl font-bold text-green-700">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Income from sponsors & tickets</p>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Total Expense</p>
                        <p class="text-2xl font-bold text-red-700">
                            Rp {{ number_format($totalExpense, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Venue, talent, vendor, etc</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Current Balance</p>
                        <p class="text-2xl font-bold {{ $currentBalance >= 0 ? 'text-blue-700' : 'text-red-700' }}">
                            Rp {{ number_format($currentBalance, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $currentBalance >= 0 ? 'Surplus' : 'Deficit' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="text-xl font-semibold">👥 Division Progress</h3>
            </div>
            <div class="card-body space-y-4">
                @forelse($divisions as $division)
                <div class="border-b pb-4 last:border-b-0 last:pb-0">
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $division['division_name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $division['members_count'] }} members</p>
                        </div>
                        <span class="text-sm font-medium">{{ $division['progress_percent'] }}%</span>
                    </div>
                    
                    <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                        <div class="h-3 rounded-full transition-all duration-300 
                            @if($division['progress_percent'] >= 80) bg-green-500
                            @elseif($division['progress_percent'] >= 50) bg-blue-500
                            @elseif($division['progress_percent'] >= 25) bg-yellow-500
                            @else bg-red-500
                            @endif"
                            style="width: {{ $division['progress_percent'] }}%">
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-600">
                        {{ $division['completed_tasks'] }} of {{ $division['total_tasks'] }} tasks completed
                    </p>
                </div>
                @empty
                <p class="text-center text-gray-500 py-4">No divisions created yet</p>
                @endforelse
            </div>
        </div>
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="text-xl font-semibold">📈 Task Status Summary</h3>
            </div>
            <div class="card-body">
                <div class="flex gap-4">
                    <div class="flex-1 bg-gray-100 p-4 rounded-lg">
                        <p class="text-xs text-gray-600 mb-1">Assigned</p>
                        <p class="text-2xl font-bold">{{ $statusCounts['assigned'] }}</p>
                    </div>
                    <div class="flex-1 bg-orange-100 p-4 rounded-lg">
                        <p class="text-xs text-gray-600 mb-1">Revision</p>
                        <p class="text-2xl font-bold">{{ $statusCounts['revision'] }}</p>
                    </div>
                    <div class="flex-1 bg-red-100 p-4 rounded-lg">
                        <p class="text-xs text-gray-600 mb-1">Blocked</p>
                        <p class="text-2xl font-bold">{{ $statusCounts['blocked'] }}</p>
                    </div>
                    <div class="flex-1 bg-blue-100 p-4 rounded-lg">
                        <p class="text-xs text-gray-600 mb-1">Under Review</p>
                        <p class="text-2xl font-bold">{{ $statusCounts['under_review'] }}</p>
                    </div>
                    <div class="flex-1 bg-green-100 p-4 rounded-lg">
                        <p class="text-xs text-gray-600 mb-1">Completed</p>
                        <p class="text-2xl font-bold">{{ $statusCounts['completed'] }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="text-xl font-semibold">Task List</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Division</th>
                            <th>Assigned To</th>
                            <th>Phase</th>
                            <th>Deadline</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                        <tr class="hover:bg-gray-50">
                            <td>
                                <p class="font-medium">{{ $task->task_name }}</p>
                                @if($task->description)
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($task->description, 50) }}</p>
                                @endif
                            </td>
                            <td>{{ $task->division->divisionType->type_name ?? $task->division->division_name ?? '-' }}</td>
                            <td>{{ $task->assignee->full_name ?? 'Unassigned' }}</td>
                            <td>
                                <span class="text-xs px-2 py-1 bg-gray-100 rounded">
                                    {{ ucwords(str_replace('_', ' ', $task->phase)) }}
                                </span>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                                @if(\Carbon\Carbon::parse($task->deadline)->isPast() && $task->status !== 'Completed')
                                    <span class="block text-xs text-red-600 font-semibold mt-1">Overdue</span>
                                @endif
                            </td>
                            <td>
                                <span class="px-2 py-1 text-xs rounded-full font-semibold
                                    @if($task->status == 'Assigned') bg-gray-100 text-gray-800
                                    @elseif($task->status == 'InProgress') bg-yellow-100 text-yellow-800
                                    @elseif($task->status == 'Revision') bg-orange-100 text-orange-800
                                    @elseif($task->status == 'Blocked') bg-red-100 text-red-800
                                    @elseif($task->status == 'UnderReview_Div' || $task->status == 'UnderReview_CC') bg-blue-100 text-blue-800
                                    @elseif($task->status == 'Completed') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ str_replace('_', ' ', $task->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                No tasks created yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @else
        <div class="card">
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📊</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Select an Event</h3>
                <p class="text-gray-600">Choose an event from the dropdown above to view its report</p>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>