<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Meeting Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800">Meeting List</h2>
                    <button
                        data-modal="addMeetingModal"
                        class="btn btn-primary">
                        + Create Meeting
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meeting Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Place</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agenda</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($meetings as $meeting)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $meeting->meeting_name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $meeting->event->event_name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $meeting->meeting_time }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $meeting->meeting_place }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">{{ Str::limit($meeting->agenda, 40) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button
                                        data-modal="editMeeting{{ $meeting->meeting_id }}"
                                        class="text-blue-600 hover:text-blue-900 font-medium mr-3">
                                        Edit
                                    </button>
                                    <button
                                        data-modal="viewMeeting{{ $meeting->meeting_id }}"
                                        class="text-green-600 hover:text-green-900 font-medium">
                                        View
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    No meetings yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Meeting Modal -->
    <div id="addMeetingModal" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Create New Meeting</h3>
            <form method="POST" action="{{ route('meetings.store') }}">
                @csrf
                <div class="form-group">
                    <label>Event (Optional)</label>
                    <select name="event_id">
                        <option value="">-- No Event --</option>
                        @foreach($events as $event)
                        <option value="{{ $event->event_id }}">{{ $event->event_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Meeting Name</label>
                    <input type="text" name="meeting_name" placeholder="Enter meeting name" required>
                </div>
                <div class="form-group">
                    <label>Meeting Date</label>
                    <input type="date" name="meeting_date" required>
                </div>
                <div class="form-group">
                    <label>Meeting Time</label>
                    <input type="time" name="meeting_time" required>
                </div>
                <div class="form-group">
                    <label>Meeting Place</label>
                    <input type="text" name="meeting_place" placeholder="Enter meeting location" required>
                </div>
                <div class="form-group">
                    <label>Agenda</label>
                    <textarea name="agenda" placeholder="Enter meeting agenda" rows="3"></textarea>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Meeting Modals (One for each meeting) -->
    @foreach($meetings as $meeting)
    <div id="editMeeting{{ $meeting->meeting_id }}" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Edit Meeting</h3>
            <form method="POST" action="{{ route('meetings.update', $meeting->meeting_id) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Event (Optional)</label>
                    <select name="event_id">
                        <option value="">-- No Event --</option>
                        @foreach($events as $event)
                        <option value="{{ $event->event_id }}" {{ $meeting->event_id == $event->event_id ? 'selected' : '' }}>
                            {{ $event->event_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Meeting Name</label>
                    <input type="text" name="meeting_name" value="{{ $meeting->meeting_name }}" placeholder="Enter meeting name" required>
                </div>
                <div class="form-group">
                    <label>Meeting Date</label>
                    <input type="date" name="meeting_date" value="{{ $meeting->meeting_date }}" required>
                </div>
                <div class="form-group">
                    <label>Meeting Time</label>
                    <input type="time" name="meeting_time" value="{{ $meeting->meeting_time }}" required>
                </div>
                <div class="form-group">
                    <label>Meeting Place</label>
                    <input type="text" name="meeting_place" value="{{ $meeting->meeting_place }}" placeholder="Enter meeting location" required>
                </div>
                <div class="form-group">
                    <label>Agenda</label>
                    <textarea name="agenda" placeholder="Enter meeting agenda" rows="3">{{ $meeting->agenda }}</textarea>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <!-- View Meeting Modals (One for each meeting) -->
    @foreach($meetings as $meeting)
    <div id="viewMeeting{{ $meeting->meeting_id }}" class="modal hidden">
        <div class="modal-box" style="max-width: 800px;">
            <h3 class="font-bold text-lg mb-4">{{ $meeting->meeting_name }}</h3>
            
            <!-- Meeting Info -->
            <div class="mb-4 p-4 bg-gray-50 rounded">
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Event:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $meeting->event->event_name ?? 'No Event' }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Date:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d F Y') }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Time:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $meeting->meeting_time }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Place:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $meeting->meeting_place }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Agenda:</span>
                    <p class="text-sm text-gray-900 mt-1">{{ $meeting->agenda ?? '-' }}</p>
                </div>
            </div>

            <!-- Meeting Notes Section -->
            <div class="border-t pt-4">
                <h4 class="font-semibold text-gray-800 mb-3">Meeting Notes</h4>
                <form method="POST" action="{{ route('meetings.updateNotes', $meeting->meeting_id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <textarea name="notes" rows="6" placeholder="Write meeting notes here...">{{ $meeting->notes }}</textarea>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Save Notes</button>
                        <button type="button" class="btn btn-secondary close-modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <script>
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
                // Reset form if exists
                const form = modal.querySelector('form');
                if (form) form.reset();
            });
        });
        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                    const form = this.querySelector('form');
                    if (form) form.reset();
                }
            });
        });
    </script>
</x-app-layout>