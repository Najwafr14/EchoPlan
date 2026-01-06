<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <a href="{{ route('event.show', $event->event_id) }}"
                    class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fa-solid fa-chevron-left text-2xl"></i>
                </a>
                <h1 class="text-3xl font-bold">
                    Venue {{ $event->event_name }}
                </h1>
            </div>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800"></h2>
                    <button data-modal="addVenueModal" class="btn btn-primary">
                        + Add Venue
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th>Venue</th>
                                <th>Capacity</th>
                                <th>Price</th>
                                <th>Primary</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($venues as $venue)
                                <tr>
                                    <td>
                                        <span class="text-sm font-medium text-gray-900">{{ $venue->venue_name }}</span>
                                    </td>
                                    <td>
                                        <span class="text-sm text-gray-900">{{ $venue->venue_capacity ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-sm font-semibold text-gray-900">Rp
                                            {{ number_format($venue->venue_price ?? 0, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        @if($venue->is_primary)
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Primary
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                                                Secondary
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <button data-modal="editVenue{{ $venue->venue_id }}"
                                            class="text-blue-600 hover:text-blue-900 font-medium mr-3">
                                            Edit
                                        </button>
                                        <button data-modal="viewVenue{{ $venue->venue_id }}"
                                            class="text-green-600 hover:text-green-900 font-medium">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        No venues yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="addVenueModal" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add New Venue</h3>
            <form method="POST" action="{{ route('event.venue.store', $eventId) }}">
                @csrf
                <div class="form-group">
                    <label>Venue Name</label>
                    <input type="text" name="venue_name" placeholder="Enter venue name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="venue_address" placeholder="Enter full address" rows="3" required></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4 ">
                    <div class="form-group">
                        <label>Capacity (optional)</label>
                        <input type="number" name="venue_capacity" placeholder="Max capacity" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="form-group">
                        <label>Price (Rp)</label>
                        <input type="number" name="venue_price" placeholder="0" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <div class="form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" placeholder="Contact person name or phone"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="form-group">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_primary" value="1" class="mr-2 w-4 h-4 rounded">
                        <span>Set as Primary Venue</span>
                    </label>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    @foreach($venues as $venue)
        <div id="editVenue{{ $venue->venue_id }}" class="modal hidden">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Edit Venue</h3>
                <form method="POST" action="{{ route('event.venue.update', [$eventId, $venue->venue_id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Venue Name</label>
                        <input type="text" name="venue_name" value="{{ $venue->venue_name }}" placeholder="Enter venue name"
                            class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="venue_address" placeholder="Enter full address" rows="3"
                            class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>{{ $venue->venue_address }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4 ">
                        <div class="form-group">
                            <label>Capacity (optional)</label>
                            <input type="number" name="venue_capacity" value="{{ $venue->venue_capacity }}"
                                placeholder="Max capacity" min="0"
                                class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="form-group">
                            <label>Price (Rp)</label>
                            <input type="number" name="venue_price" value="{{ $venue->venue_price }}" placeholder="0"
                                min="0"
                                class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Contact Person</label>
                        <input type="text" name="contact_person" value="{{ $venue->contact_person }}"
                            placeholder="Contact person name or phone"
                            class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="form-group">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_primary" value="1" class="mr-2 w-4 h-4 rounded" {{ $venue->is_primary ? 'checked' : '' }}>
                            <span>Set as Primary Venue</span>
                        </label>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    @foreach($venues as $venue)
        <div id="viewVenue{{ $venue->venue_id }}" class="modal hidden">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Venue Details</h3>
                <div class="mb-4 p-4 bg-gray-50 rounded">
                    <div class="mb-2">
                        <span class="text-sm font-medium text-gray-600">Venue Name:</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $venue->venue_name }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-sm font-medium text-gray-600">Address:</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $venue->venue_address }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-sm font-medium text-gray-600">Capacity:</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $venue->venue_capacity ?? '-' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-sm font-medium text-gray-600">Price:</span>
                        <span class="text-sm font-semibold text-gray-900 ml-2">Rp
                            {{ number_format($venue->venue_price ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-sm font-medium text-gray-600">Contact Person:</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $venue->contact_person ?? '-' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-sm font-medium text-gray-600">Status:</span>
                        @if($venue->is_primary)
                            <span
                                class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800 ml-2">
                                Primary Venue
                            </span>
                        @else
                            <span
                                class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-600 ml-2">
                                Secondary Venue
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" class="btn btn-secondary close-modal">Close</button>
                </div>
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
                const form = modal.querySelector('form');
                if (form) form.reset();
            });
        });
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function (e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                    const form = this.querySelector('form');
                    if (form) form.reset();
                }
            });
        });
    </script>
</x-app-layout>