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
                    Vendor {{ $event->event_name }}
                </h1>
            </div>
            <!-- Vendor Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800"></h2>
                    <button
                        data-modal="addVendorModal"
                        class="btn btn-primary">
                        + Add Vendor
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th>Vendor Name</th>
                                <th>Service</th>
                                <th>Cost</th>
                                <th>Contact</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($vendors as $vendor)
                            <tr>
                                <td>
                                    <span class="text-sm font-medium text-gray-900">{{ $vendor->vendor_name }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-gray-900">{{ $vendor->vendor_service }}</span>
                                </td>
                                <td>
                                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($vendor->cost ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-gray-900">{{ $vendor->contact_info ?? '-' }}</span>
                                </td>
                                <td class="text-sm">
                                    <button
                                        data-modal="editVendor{{ $vendor->vendor_id }}"
                                        class="text-blue-600 hover:text-blue-900 font-medium mr-3">
                                        Edit
                                    </button>
                                    <button
                                        data-modal="viewVendor{{ $vendor->vendor_id }}"
                                        class="text-green-600 hover:text-green-900 font-medium">
                                        View
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No vendors yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="addVendorModal" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add New Vendor</h3>
            <form method="POST" action="{{ route('event.vendor.store', $eventId) }}">
                @csrf
                <div class="form-group">
                    <label>Vendor Name</label>
                    <input type="text" name="vendor_name" placeholder="Enter vendor name" class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div class="form-group">
                    <label>Service</label>
                    <input type="text" name="vendor_service" placeholder="e.g., Catering, Photography, etc." class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div class="form-group">
                    <label>Cost (Rp)</label>
                    <input type="number" name="cost" placeholder="0" min="0" step="0.01" class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="form-group">
                    <label>Contact Info</label>
                    <input type="text" name="contact_info" placeholder="Phone number or email" class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    @foreach($vendors as $vendor)
    <div id="editVendor{{ $vendor->vendor_id }}" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Edit Vendor</h3>
            <form method="POST" action="{{ route('event.vendor.update', [$eventId, $vendor->vendor_id]) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Vendor Name</label>
                    <input type="text" name="vendor_name" value="{{ $vendor->vendor_name }}" placeholder="Enter vendor name" class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div class="form-group">
                    <label>Service</label>
                    <input type="text" name="vendor_service" value="{{ $vendor->vendor_service }}" placeholder="e.g., Catering, Photography, etc." class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div class="form-group">
                    <label>Cost (Rp)</label>
                    <input type="number" name="cost" value="{{ $vendor->cost }}" placeholder="0" min="0" step="0.01" class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="form-group">
                    <label>Contact Info</label>
                    <input type="text" name="contact_info" value="{{ $vendor->contact_info }}" placeholder="Phone number or email" class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    @foreach($vendors as $vendor)
    <div id="viewVendor{{ $vendor->vendor_id }}" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Vendor Details</h3>
            <!-- Vendor Info -->
            <div class="mb-4 p-4 bg-gray-50 rounded">
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Vendor Name:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $vendor->vendor_name }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Service:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $vendor->vendor_service }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Cost:</span>
                    <span class="text-sm font-semibold text-gray-900 ml-2">Rp {{ number_format($vendor->cost ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Contact Info:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $vendor->contact_info ?? '-' }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Created:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $vendor->created_at->format('d M Y, H:i') }}</span>
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
                // Reset form if exists
                const form = modal.querySelector('form');
                if (form) form.reset();
            });
        });
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