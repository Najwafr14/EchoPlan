<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('event.show', $event->event_id) }}"
                    class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fa-solid fa-chevron-left text-2xl"></i>
                </a>
                <h1 class="text-3xl font-bold">
                    Task {{ $event->event_name }}
                </h1>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
                <form method="GET" action="{{ route('user.event.task.index', $eventId) }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search task name..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <select name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Status</option>
                                @foreach($allStatuses as $statusOption)
                                    <option value="{{ $statusOption }}" {{ request('status') == $statusOption ? 'selected' : '' }}>
                                        {{ str_replace('_', ' ', $statusOption) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <select name="sort"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="deadline_asc" {{ request('sort', 'deadline_asc') == 'deadline_asc' ? 'selected' : '' }}>Deadline (Nearest)</option>
                                <option value="deadline_desc" {{ request('sort') == 'deadline_desc' ? 'selected' : '' }}>
                                    Deadline (Farthest)</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Filter
                            </button>

                            @if(request()->hasAny(['search', 'status', 'sort']))
                                <a href="{{ route('user.event.task.index', $eventId) }}"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                @if(request()->hasAny(['search', 'status']))
                    <div class="mt-3 flex gap-2 flex-wrap">
                        @if(request('search'))
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm flex items-center gap-2">
                                Search: "{{ request('search') }}"
                                <a href="{{ route('user.event.task.index', array_merge([$eventId], request()->except('search'))) }}"
                                    class="text-blue-600 hover:text-blue-800 font-bold">×</a>
                            </span>
                        @endif
                        @if(request('status'))
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm flex items-center gap-2">
                                Status: {{ str_replace('_', ' ', request('status')) }}
                                <a href="{{ route('user.event.task.index', array_merge([$eventId], request()->except('status'))) }}"
                                    class="text-green-600 hover:text-green-800 font-bold">×</a>
                            </span>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex justify-end mb-4">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                    data-modal="addTaskModal">
                    + Add Task
                </button>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Task</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Division</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Assigned To</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Phase</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deadline</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($tasks as $task)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $task->task_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $task->division->division_name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $task->assignee->full_name ?? 'Unassigned' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ ucwords(str_replace('_', ' ', $task->phase)) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                                        @if(\Carbon\Carbon::parse($task->deadline)->isPast() && $task->status !== 'Completed')
                                            <span class="ml-2 text-xs text-red-600 font-semibold">Overdue</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('user.event.task.show', $task->task_id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        @if(request()->hasAny(['search', 'status']))
                                            No tasks found matching your filters.
                                            <a href="{{ route('user.event.task.index', $eventId) }}"
                                                class="text-blue-600 hover:underline ml-2">Clear filters</a>
                                        @else
                                            No tasks found.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="addTaskModal"
        class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col"
            onclick="event.stopPropagation()">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="font-bold text-xl text-gray-800">Add New Task</h3>
                <button type="button" class="close-modal text-gray-400 hover:text-gray-600 text-2xl leading-none">
                    &times;
                </button>
            </div>

            <div class="overflow-y-auto flex-1 p-6">
                <form method="POST" action="{{ route('user.event.task.store', $eventId) }}" id="addTaskForm">
                    @csrf
                    <div class="space-y-4">
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                            <select name="event_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                                <option value="">-- Select Event --</option>
                                @foreach($events as $evt)
                                    <option value="{{ $evt->event_id }}" {{ $evt->event_id == $event->event_id ? 'selected' : '' }}>
                                        {{ $evt->event_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                                <select name="division_id" id="divisionSelect"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                    <option value="">-- Select Division --</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->division_id }}">
                                            {{ $division->divisionType->type_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                                <select name="assigned_to" id="assigneeSelect"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    disabled>
                                    <option value="">-- Select Division First --</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Task Name</label>
                            <input type="text" name="task_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter task name" required>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter task description"></textarea>
                        </div>

                        <input type="hidden" name="status" value="Assigned">

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                                <input type="date" name="deadline"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phase</label>
                                <select name="phase"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                    <option value="pre event">Pre Event</option>
                                    <option value="preparation">Preparation</option>
                                    <option value="d day">D Day</option>
                                    <option value="post event">Post Event</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="flex justify-end gap-2 p-6 border-t border-gray-200 bg-gray-50">
                <button type="button"
                    class="close-modal px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button type="submit" form="addTaskForm"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Save Task
                </button>
            </div>
        </div>
    </div>

    <script>
        const usersByDivision = @json($usersByDivision);

        document.querySelectorAll('[data-modal]').forEach(btn => {
            btn.addEventListener('click', () => {
                const modalId = btn.getAttribute('data-modal');
                const modal = document.getElementById(modalId);
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        function closeModal(modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';

            const form = modal.querySelector('form');
            if (form) form.reset();

            const assigneeSelect = document.getElementById('assigneeSelect');
            if (assigneeSelect) {
                assigneeSelect.disabled = true;
                assigneeSelect.innerHTML = '<option value="">-- Select Division First --</option>';
            }
        }

        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const modal = btn.closest('.modal');
                closeModal(modal);
            });
        });

        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal(modal);
                }
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal:not(.hidden)').forEach(modal => {
                    closeModal(modal);
                });
            }
        });

        document.getElementById('divisionSelect').addEventListener('change', function () {
            const divisionId = this.value;
            const assigneeSelect = document.getElementById('assigneeSelect');

            assigneeSelect.innerHTML = '<option value="">-- Select Assignee --</option>';

            if (divisionId && usersByDivision[divisionId]) {
                assigneeSelect.disabled = false;

                usersByDivision[divisionId].forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.user_id;
                    option.textContent = user.full_name;
                    assigneeSelect.appendChild(option);
                });
            } else {
                assigneeSelect.disabled = true;
                assigneeSelect.innerHTML = '<option value="">-- No members in this division --</option>';
            }
        });
    </script>
</x-app-layout>