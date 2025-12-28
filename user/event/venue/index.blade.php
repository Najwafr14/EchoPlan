<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Venue Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800">Venue List</h2>
                    <button
                        data-modal="addVenueModal"
                        class="btn btn-primary">
                        + Add Venue
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Primary</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($venues as $venue)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $venue->venue_name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $venue->venue_capacity ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($venue->venue_price ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($venue->is_primary)
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Primary
                                    </span>
                                    @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                                        Secondary
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button
                                        data-modal="editVenue{{ $venue->venue_id }}"
                                        class="text-blue-600 hover:text-blue-900 font-medium mr-3">
                                        Edit
                                    </button>
                                    <button
                                        data-modal="viewVenue{{ $venue->venue_id }}"
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

    <!-- Add Venue Modal -->
    <div id="addVenueModal" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add New Venue</h3>
            <form method="POST" action="{{ route('event.venue.store', $eventId) }}">
                @csrf
                <div class="form-group">
                    <label>Venue Name</label>
                    <input type="text" name="venue_name" placeholder="Enter venue name" required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="venue_address" placeholder="Enter full address" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label>Capacity (optional)</label>
                    <input type="number" name="venue_capacity" placeholder="Max capacity" min="0">
                </div>
                <div class="form-group">
                    <label>Price (Rp)</label>
                    <input type="number" name="venue_price" placeholder="0" min="0">
                </div>
                <div class="form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" placeholder="Contact person name or phone">
                </div>
                <div class="form-group">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_primary" class="mr-2">
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

    <!-- Edit Venue Modals (One for each venue) -->
    @foreach($venues as $venue)
    <div id="editVenue{{ $venue->venue_id }}" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Edit Venue</h3>
            <form method="POST" action="{{ route('event.venue.update', [$eventId, $venue->venue_id]) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Venue Name</label>
                    <input type="text" name="venue_name" value="{{ $venue->venue_name }}" placeholder="Enter venue name" required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="venue_address" placeholder="Enter full address" rows="3" required>{{ $venue->venue_address }}</textarea>
                </div>
                <div class="form-group">
                    <label>Capacity (optional)</label>
                    <input type="number" name="venue_capacity" value="{{ $venue->venue_capacity }}" placeholder="Max capacity" min="0">
                </div>
                <div class="form-group">
                    <label>Price (Rp)</label>
                    <input type="number" name="venue_price" value="{{ $venue->venue_price }}" placeholder="0" min="0">
                </div>
                <div class="form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" value="{{ $venue->contact_person }}" placeholder="Contact person name or phone">
                </div>
                <div class="form-group">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_primary" class="mr-2" {{ $venue->is_primary ? 'checked' : '' }}>
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

    <!-- View Venue Modals (One for each venue) -->
    @foreach($venues as $venue)
    <div id="viewVenue{{ $venue->venue_id }}" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Venue Details</h3>
            <!-- Venue Info -->
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
                    <span class="text-sm font-semibold text-gray-900 ml-2">Rp {{ number_format($venue->venue_price ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Contact Person:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $venue->contact_person ?? '-' }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Status:</span>
                    @if($venue->is_primary)
                    <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800 ml-2">
                        Primary Venue
                    </span>
                    @else
                    <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-600 ml-2">
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