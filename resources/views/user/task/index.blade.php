<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="container p-6 bg-white mt-6 rounded-lg shadow-md">
        <div class="max-w-7xl mx-auto">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">My Tasks</h2>
            <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input type="text" id="searchInput" placeholder="Search tasks..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select id="eventFilter"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Events</option>
                        @foreach($userEvents as $event)
                            <option value="{{ $event->event_id }}">{{ $event->event_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select id="statusFilter"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="Assigned">Assigned</option>
                        <option value="Revision">Revision</option>
                        <option value="Blocked">Blocked</option>
                        <option value="Submitted">Submitted</option>
                        <option value="UnderReview_Div">Under Review Div</option>
                        <option value="UnderReview_CC">Under Review CC</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
            </div>

            <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg">
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
                    <tbody class="bg-white divide-y divide-gray-200" id="taskTableBody">
                        @forelse($tasks as $task)
                            <tr class="task-row" data-event="{{ $task->event_id }}" data-status="{{ $task->status }}"
                                data-search="{{ strtolower($task->task_name . ' ' . ($task->division->division_name ?? '') . ' ' . ($task->assignee->full_name ?? '')) }}">
                                <td>
                                    <span class="text-xs font-semibold text-gray-700">{{ $task->event->event_name }}</span>
                                </td>
                                <td>{{ $task->task_name }}</td>
                                <td>{{ $task->division->division_name ?? '-' }}</td>
                                <td>{{ $task->assignee->full_name ?? 'Unassigned' }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                                </td>
                                <td>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                            @if($task->status == 'Assigned') bg-gray-100 text-gray-800
                                            @elseif($task->status == 'Revision') bg-yellow-100 text-yellow-800
                                            @elseif($task->status == 'Blocked') bg-red-100 text-red-800
                                            @elseif($task->status == 'Submitted') bg-purple-100 text-purple-800
                                            @elseif($task->status == 'UnderReview_Div') bg-blue-100 text-blue-800
                                            @elseif($task->status == 'UnderReview_CC') bg-blue-100 text-blue-800
                                            @elseif($task->status == 'Completed') bg-green-100 text-green-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                        {{ $task->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('task.show', $task->task_id) }}" class="btn btn-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr id="noTasksRow">
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    No tasks found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const eventFilter = document.getElementById('eventFilter');
        const statusFilter = document.getElementById('statusFilter');
        const taskRows = document.querySelectorAll('.task-row');

        function filterTasks() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedEvent = eventFilter.value;
            const selectedStatus = statusFilter.value;
            let visibleCount = 0;

            taskRows.forEach(row => {
                const eventId = row.getAttribute('data-event');
                const status = row.getAttribute('data-status');
                const searchData = row.getAttribute('data-search');

                const matchesSearch = searchData.includes(searchTerm);
                const matchesEvent = selectedEvent === '' || eventId === selectedEvent;
                const matchesStatus = selectedStatus === '' || status === selectedStatus;

                if (matchesSearch && matchesEvent && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const noTasksRow = document.getElementById('noTasksRow');
            if (noTasksRow) {
                noTasksRow.style.display = visibleCount === 0 ? '' : 'none';
            }
        }

        searchInput.addEventListener('keyup', filterTasks);
        eventFilter.addEventListener('change', filterTasks);
        statusFilter.addEventListener('change', filterTasks);
    </script>
</x-app-layout>