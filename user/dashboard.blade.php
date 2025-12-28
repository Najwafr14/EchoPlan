<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
        <p>Dashboard {{ auth()->user()->role }}</p>
        <b>{{ auth()->user()->full_name }}</b>
    </div>
    <div class="p-6 space-y-8">

        <!-- ===== SUMMARY CARDS ===== -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-blue-100 p-5 rounded-xl">
                <p class="text-sm text-gray-600">Total Event</p>
                <h2 class="text-3xl font-bold">{{ $totalEvents }}</h2>
            </div>

            <div class="bg-purple-100 p-5 rounded-xl">
                <p class="text-sm text-gray-600">Task Done</p>
                <h2 class="text-3xl font-bold">{{ $taskDone }}</h2>
            </div>

            <div class="bg-pink-100 p-5 rounded-xl">
                <p class="text-sm text-gray-600">Needs Revision</p>
                <h2 class="text-3xl font-bold">{{ $needsRevision }}</h2>
            </div>

            <div class="bg-yellow-100 p-5 rounded-xl">
                <p class="text-sm text-gray-600">Next Meeting</p>
                <h2 class="text-lg font-semibold">
                    {{ $nextMeeting?->meeting_date?->format('l, d F Y') ?? 'Belum ada' }}
                </h2>
            </div>
        </div>

        <!-- ===== ACTIVE EVENT PROGRESS ===== -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-semibold mb-4">Active Event Progress</h3>

            <div class="space-y-4">
                @foreach ($activeEvents as $event)
                <div>
                    <div class="flex justify-between mb-1">
                        <p class="font-medium">{{ $event['name'] }}</p>
                        <p class="text-sm">{{ $event['progress'] }}%</p>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full"
                            style="width: {{ $event['progress'] }}%"></div>
                    </div>

                    <p class="text-xs text-gray-500 mt-1">
                        Deadline: {{ \Carbon\Carbon::parse($event['deadline'])->format('d M Y') }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- ===== RECENT TASK ===== -->
        <div class="bg-white p-6 rounded-xl shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">
                    Recent Task
                    <span class="ml-2 bg-gray-200 text-sm px-2 py-1 rounded">
                        {{ $recentTasks->count() }}
                    </span>
                </h3>

                <a href=""
                    class="text-blue-600 text-sm font-medium">
                    See All
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-left text-gray-500 border-b">
                        <tr>
                            <th class="py-2">Task</th>
                            <th>Event</th>
                            <th>Division</th>
                            <th>Status</th>
                            <th>Deadline</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($recentTasks as $task)
                        <tr>
                            <td class="py-2">{{ $task->task_name }}</td>
                            <td>{{ $task->event->event_name ?? '-' }}</td>
                            <td>{{ $task->division->division_name ?? '-' }}</td>
                            <td>
                                <span class="px-2 py-1 rounded text-xs
                                    @if ($task->status === 'Completed') bg-green-100 text-green-700
                                    @elseif ($task->status === 'In Progress') bg-blue-100 text-blue-700
                                    @elseif ($task->status === 'Needs Revision') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700
                                    @endif">
                                    {{ $task->status }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>