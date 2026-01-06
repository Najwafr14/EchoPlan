<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('event.show', $event -> event_id) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fa-solid fa-chevron-left text-2xl"></i>
                </a>
                <h1 class="text-3xl font-bold">
                    Talent {{ $event->event_name }}
                </h1>
            </div>
            @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
            @endif

            <div class="grid grid-cols-1 mt-4 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-purple-100 p-6 rounded-lg shadow">
                    <p class="text-sm text-purple-700 font-medium">Solo</p>
                    <h2 class="text-3xl font-bold text-purple-900">{{ $summary['Solo'] }}</h2>
                </div>
                <div class="bg-blue-100 p-6 rounded-lg shadow">
                    <p class="text-sm text-blue-700 font-medium">Band</p>
                    <h2 class="text-3xl font-bold text-blue-900">{{ $summary['Band'] }}</h2>
                </div>
                <div class="bg-pink-100 p-6 rounded-lg shadow">
                    <p class="text-sm text-pink-700 font-medium">Performer</p>
                    <h2 class="text-3xl font-bold text-pink-900">{{ $summary['Performer'] }}</h2>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800"></h2>
                    <button data-modal="addTalentModal" class="btn btn-primary">
                        + Add Talent
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th>Talent Name</th>
                                <th>Type</th>
                                <th>Fee</th>
                                <th>Performance Date</th>
                                <th>Contact</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($talents as $talent)
                            <tr>
                                <td>
                                    <span class="text-sm font-medium text-gray-900">{{ $talent->talent_name }}</span>
                                </td>
                                <td>
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $talent->talent_type == 'Solo' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $talent->talent_type == 'Band' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $talent->talent_type == 'Performer' ? 'bg-pink-100 text-pink-800' : '' }}">
                                        {{ $talent->talent_type }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($talent->talent_fee, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($talent->performance_date)->format('d M Y, H:i') }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-gray-600">{{ $talent->contact_info }}</span>
                                </td>
                                <td>
                                    <button data-modal="viewTalent{{ $talent->talent_id }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                        View
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No talents yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div id="addTalentModal" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add New Talent</h3>
            <form method="POST" action="{{ route('event.talent.store', $eventId) }}">
                @csrf
                <div class="form-group">
                    <label>Talent Name</label>
                    <input type="text" name="talent_name" placeholder="Enter talent name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="talent_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">-- Select Type --</option>
                        <option value="Solo">Solo</option>
                        <option value="Band">Band</option>
                        <option value="Performer">Performer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fee (Rp)</label>
                    <input type="number" name="talent_fee" placeholder="0" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="form-group">
                    <label>Performance Date & Time</label>
                    <input type="datetime-local" name="performance_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="form-group">
                    <label>Contact Info</label>
                    <input type="text" name="contact_info" placeholder="Phone or email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    @foreach($talents as $talent)
    <div id="viewTalent{{ $talent->talent_id }}" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Talent Details</h3>
            
            <div class="mb-4 p-4 bg-gray-50 rounded">
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Name:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $talent->talent_name }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Type:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $talent->talent_type }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Fee:</span>
                    <span class="text-sm font-semibold text-gray-900 ml-2">Rp {{ number_format($talent->talent_fee, 0, ',', '.') }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Performance:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ \Carbon\Carbon::parse($talent->performance_date)->format('d M Y, H:i') }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Contact:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $talent->contact_info }}</span>
                </div>
            </div>

            <div class="flex justify-between mt-4 gap-2">
                <form method="POST" action="{{ route('event.talent.destroy', [$eventId, $talent->talent_id]) }}" onsubmit="return confirm('Are you sure want to delete this talent?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                <div class="flex gap-2">
                    <button type="button" data-modal="editTalent{{ $talent->talent_id }}" class="btn btn-primary close-modal">Edit</button>
                    <button type="button" class="btn btn-secondary close-modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="editTalent{{ $talent->talent_id }}" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Edit Talent</h3>
            <form method="POST" action="{{ route('event.talent.update', [$eventId, $talent->talent_id]) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Talent Name</label>
                    <input type="text" name="talent_name" value="{{ $talent->talent_name }}" required>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="talent_type" required>
                        <option value="Solo" {{ $talent->talent_type == 'Solo' ? 'selected' : '' }}>Solo</option>
                        <option value="Band" {{ $talent->talent_type == 'Band' ? 'selected' : '' }}>Band</option>
                        <option value="Performer" {{ $talent->talent_type == 'Performer' ? 'selected' : '' }}>Performer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fee (Rp)</label>
                    <input type="number" name="talent_fee" value="{{ $talent->talent_fee }}" min="0" required>
                </div>
                <div class="form-group">
                    <label>Performance Date & Time</label>
                    <input type="datetime-local" name="performance_date" value="{{ \Carbon\Carbon::parse($talent->performance_date)->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="form-group">
                    <label>Contact Info</label>
                    <input type="text" name="contact_info" value="{{ $talent->contact_info }}" required>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <script>
        document.querySelectorAll('[data-modal]').forEach(btn => {
            btn.addEventListener('click', () => {
                const modalId = btn.getAttribute('data-modal');
                document.getElementById(modalId).classList.remove('hidden');
            });
        });

        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const modal = btn.closest('.modal');
                modal.classList.add('hidden');
            });
        });

        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>