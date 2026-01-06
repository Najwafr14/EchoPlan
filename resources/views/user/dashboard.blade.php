<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
        <p>Dashboard {{ auth()->user()->role }}</p>
        <b>{{ auth()->user()->full_name }}</b>
    </div>
    <div class="p-6 space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-blue-100 p-5 rounded-xl">
                <p class="text-sm text-gray-600">Total Event</p>
                <h2 class="text-3xl font-bold">{{ $totalEvents }}</h2>
            </div>

            <div class="bg-purple-100 p-5 rounded-xl">
                <p class="text-sm text-gray-600">Task Done</p>
                <h2 class="text-3xl font-bold">{{ $completedTasks }}</h2>
            </div>

            <div class="bg-pink-100 p-5 rounded-xl">
                <p class="text-sm text-gray-600">Needs Revision</p>
                <h2 class="text-3xl font-bold">{{ $revisionTasks }}</h2>
            </div>

            <div class="bg-yellow-100 p-5 rounded-xl">
                <p class="text-sm text-gray-600">Next Meeting</p>
                @if($nextMeeting)
                    <h2 class="text-lg font-semibold mt-1">{{ $nextMeeting->meeting_name }}</h2>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ \Carbon\Carbon::parse($nextMeeting->meeting_date)->format('d M Y, H:i') }}
                    </p>
                @else
                    <h2 class="text-lg font-semibold mt-1">No upcoming meeting</h2>
                @endif
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">
                    My Events
                </h3>

                <a href="{{ route('event.index') }}" class="text-blue-600 text-sm font-medium">
                    See All
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($events as $event)
                            @php
                                $progress = $event->total_tasks > 0
                                    ? round(($event->completed_tasks / $event->total_tasks) * 100)
                                    : 0;
                            @endphp
                            <tr>
                                <td><strong>{{ $event->event_name }}</strong></td>

                                <td>{{ $event->category->category_name ?? '-' }}</td>

                                <td>{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</td>

                                <td style="width:200px">
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ $progress }}%">
                                            {{ $progress }}%
                                        </div>
                                    </div>
                                    <small>{{ $event->completed_tasks }} of {{ $event->total_tasks }} tasks
                                        completed</small>
                                </td>

                                <td>
                                    @if (\Carbon\Carbon::parse($event->event_date)->isPast())
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Complete
                                            d</span>
                                    @else
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('event.show', $event->event_id) }}" class="btn btn-primary">
                                        View
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    No events found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">
                    Recent Tasks
                </h3>

                <a href="{{ route('task.index') }}" class="text-blue-600 text-sm font-medium">
                    See All
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th>Event</th>
                            <th>Task</th>
                            <th>Division</th>
                            <th>Assigned To</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tasks as $task)
                            <tr data-event="{{ $task->event_id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs font-semibold text-gray-700">{{ $task->event->event_name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $task->task_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $task->division->division_name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $task->assignee->full_name ?? 'Unassigned' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                            @if($task->status == 'Completed') bg-green-100 text-green-800
                                            @elseif($task->status == 'Blocked') bg-red-100 text-red-800
                                            @elseif(str_contains($task->status, 'Review')) bg-blue-100 text-blue-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                        {{ $task->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('task.show', $task->task_id) }}" class="btn btn-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    No tasks found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>