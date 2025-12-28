<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tasks</h2>
            <div class="flex justify-end mb-4">
                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700" data-modal="addTaskModal">
                    + Add Task
                </button>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Task</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Division</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned To</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phase</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deadline</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tasks as $task)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $task->task_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $task->division->division_name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $task->assignee->full_name ?? 'Unassigned' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ str_replace('_',' ',$task->phase) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $task->status }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('user.event.task.show', $task->task_id) }}"
                                    class="text-blue-600 hover:text-blue-800">
                                    View Detail →
                                </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="addTaskModal" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add New Task</h3>
            <form method="POST" action="{{ route('user.event.task.store', $eventId) }}">
                @csrf

                <div class="form-group">
                    <label>Event</label>
                    <select name="event_id" required>
                        <option value="">-- Select Event --</option>
                        @foreach($events as $event)
                        <option value="{{ $event->event_id }}">{{ $event->event_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Division</label>
                    <select name="division_id" id="divisionSelect" required>
                        <option value="">-- Select Division --</option>
                        @foreach($divisions as $division)
                        <option value="{{ $division->division_id }}">{{ $division->division_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Assign To</label>
                    <select name="assigned_to" id="assigneeSelect" disabled>
                        <option value="">-- Select Division First --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Task Name</label>
                    <input type="text" name="task_name" placeholder="Enter task name" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Enter task description"></textarea>
                </div>

                <input type="hidden" name="status" value="Assigned">

                <div class="form-group">
                    <label>Deadline</label>
                    <input type="date" name="deadline" required>
                </div>

                <div class="form-group">
                    <label>Phase</label>
                    <select name="phase" required>
                        <option value="pre event">Pre Event</option>
                        <option value="preparation">Preparation</option>
                        <option value="d day">D Day</option>
                        <option value="post event">Post Event</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Data users by division dari backend
        const usersByDivision = @json($usersByDivision);

        // Open modal
        document.querySelectorAll('[data-modal]').forEach(btn => {
            btn.addEventListener('click', () => {
                const modalId = btn.getAttribute('data-modal');
                document.getElementById(modalId).classList.remove('hidden');
            });
        });

        // Close modal
        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const modal = btn.closest('.modal');
                modal.classList.add('hidden');
                // Reset form
                const form = modal.querySelector('form');
                if (form) form.reset();
                // Reset assignee dropdown
                document.getElementById('assigneeSelect').disabled = true;
                document.getElementById('assigneeSelect').innerHTML = '<option value="">-- Select Division First --</option>';
            });
        });

        // Dynamic Assignee Dropdown based on Division
        document.getElementById('divisionSelect').addEventListener('change', function() {
            const divisionId = this.value;
            const assigneeSelect = document.getElementById('assigneeSelect');

            // Reset assignee dropdown
            assigneeSelect.innerHTML = '<option value="">-- Select Assignee --</option>';

            if (divisionId && usersByDivision[divisionId]) {
                // Enable dropdown
                assigneeSelect.disabled = false;

                // Populate users from selected division
                usersByDivision[divisionId].forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.user_id;
                    option.textContent = user.full_name;
                    assigneeSelect.appendChild(option);
                });
            } else {
                // Disable if no division selected or no users
                assigneeSelect.disabled = true;
                assigneeSelect.innerHTML = '<option value="">-- No members in this division --</option>';
            }
        });
    </script>
</x-app-layout>