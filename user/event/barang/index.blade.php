<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Barang Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800">Items List</h2>
                    <button
                        data-modal="addBarangModal"
                        class="btn btn-primary">
                        + Add Item
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($barangs as $barang)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $barang->item_name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $barang->item_type }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $barang->quantity }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($barang->item_status == 'Sewa')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Sewa
                                    </span>
                                    @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Pinjam
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $barang->vendor->vendor_name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($barang->cost ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button
                                        data-modal="editBarang{{ $barang->item_id }}"
                                        class="text-blue-600 hover:text-blue-900 font-medium mr-3">
                                        Edit
                                    </button>
                                    <button
                                        data-modal="viewBarang{{ $barang->item_id }}"
                                        class="text-green-600 hover:text-green-900 font-medium">
                                        View
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    No items yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Barang Modal -->
    <div id="addBarangModal" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add New Item</h3>
            <form method="POST" action="{{ route('event.barang.store', $eventId) }}">
                @csrf
                <div class="form-group">
                    <label>Item Name</label>
                    <input type="text" name="item_name" placeholder="Enter item name" required>
                </div>
                <div class="form-group">
                    <label>Item Type</label>
                    <input type="text" name="item_type" placeholder="e.g., Sound System, Tables, etc." required>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" placeholder="0" min="1" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="item_status" required>
                        <option value="">-- Select Status --</option>
                        <option value="Sewa">Sewa</option>
                        <option value="Pinjam">Pinjam</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Vendor</label>
                    <select name="vendor_id">
                        <option value="">-- Select Vendor (Optional) --</option>
                        @foreach($vendors as $vendor)
                        <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Cost (Rp)</label>
                    <input type="number" name="cost" placeholder="0" min="0" step="0.01">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Barang Modals (One for each barang) -->
    @foreach($barangs as $barang)
    <div id="editBarang{{ $barang->item_id }}" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Edit Item</h3>
            <form method="POST" action="{{ route('event.barang.update', [$eventId, $barang->item_id]) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Item Name</label>
                    <input type="text" name="item_name" value="{{ $barang->item_name }}" placeholder="Enter item name" required>
                </div>
                <div class="form-group">
                    <label>Item Type</label>
                    <input type="text" name="item_type" value="{{ $barang->item_type }}" placeholder="e.g., Sound System, Tables, etc." required>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" value="{{ $barang->quantity }}" placeholder="0" min="1" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="item_status" required>
                        <option value="">-- Select Status --</option>
                        <option value="Sewa" {{ $barang->item_status == 'Sewa' ? 'selected' : '' }}>Sewa</option>
                        <option value="Pinjam" {{ $barang->item_status == 'Pinjam' ? 'selected' : '' }}>Pinjam</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Vendor</label>
                    <select name="vendor_id">
                        <option value="">-- Select Vendor (Optional) --</option>
                        @foreach($vendors as $vendor)
                        <option value="{{ $vendor->vendor_id }}" {{ $barang->vendor_id == $vendor->vendor_id ? 'selected' : '' }}>
                            {{ $vendor->vendor_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Cost (Rp)</label>
                    <input type="number" name="cost" value="{{ $barang->cost }}" placeholder="0" min="0" step="0.01">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <!-- View Barang Modals (One for each barang) -->
    @foreach($barangs as $barang)
    <div id="viewBarang{{ $barang->item_id }}" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Item Details</h3>
            <!-- Barang Info -->
            <div class="mb-4 p-4 bg-gray-50 rounded">
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Item Name:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $barang->item_name }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Type:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $barang->item_type }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Quantity:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $barang->quantity }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Status:</span>
                    @if($barang->item_status == 'Sewa')
                    <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800 ml-2">
                        Sewa
                    </span>
                    @else
                    <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800 ml-2">
                        Pinjam
                    </span>
                    @endif
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Vendor:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $barang->vendor->vendor_name ?? '-' }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Cost:</span>
                    <span class="text-sm font-semibold text-gray-900 ml-2">Rp {{ number_format($barang->cost ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Created:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $barang->created_at->format('d M Y, H:i') }}</span>
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