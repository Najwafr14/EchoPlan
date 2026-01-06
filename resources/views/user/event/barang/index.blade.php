<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <a href="{{ route('event.show', $event -> event_id) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <i class="fa-solid fa-chevron-left text-2xl"></i>
            </a>
            <h1 class="text-3xl font-bold">
                Item {{ $event->event_name }}
            </h1>
        </div>
        <!-- Barang Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-bold text-gray-800"></h2>
                <button data-modal="addBarangModal" class="btn btn-primary">
                    + Add Item
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th>
                                Item Name</th>
                            <th>
                                Type</th>
                            <th>
                                Quantity</th>
                            <th>
                                Status</th>
                            <th>
                                Vendor</th>
                            <th>
                                Cost</th>
                            <th>
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($barangs as $barang)
                            <tr>
                                <td>
                                    <span class="text-sm font-medium text-gray-900">{{ $barang->item_name }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-gray-900">{{ $barang->item_type }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-gray-900">{{ $barang->quantity }}</span>
                                </td>
                                <td>
                                    @if($barang->item_status == 'Sewa')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Sewa
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Pinjam
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-sm text-gray-900">{{ $barang->vendor->vendor_name ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="text-sm font-semibold text-gray-900">Rp
                                        {{ number_format($barang->cost ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td class="text-sm">
                                    <button data-modal="editBarang{{ $barang->item_id }}"
                                        class="text-blue-600 hover:text-blue-900 font-medium mr-3">
                                        Edit
                                    </button>
                                    <button data-modal="viewBarang{{ $barang->item_id }}"
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
    <div id="addBarangModal" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add New Item</h3>
            <form method="POST" action="{{ route('event.barang.store', $eventId) }}">
                @csrf
                <div class="form-group">
                    <label>Item Name</label>
                    <input type="text" name="item_name" placeholder="Enter item name" class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div class="form-group">
                    <label>Item Type</label>
                    <input type="text" name="item_type" placeholder="e.g., Sound System, Tables, etc." class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" placeholder="0" min="1" class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div class="form-group">
                        <label>Cost (Rp)</label>
                        <input type="number" name="cost" placeholder="0" min="0" step="0.01" class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="item_status" class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">-- Select Status --</option>
                        <option value="Sewa">Sewa</option>
                        <option value="Pinjam">Pinjam</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Vendor</label>
                    <select name="vendor_id" class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Select Vendor (Optional) --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    @foreach($barangs as $barang)
        <div id="editBarang{{ $barang->item_id }}" class="modal hidden">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Edit Item</h3>
                <form method="POST" action="{{ route('event.barang.update', [$eventId, $barang->item_id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Item Name</label>
                        <input type="text" name="item_name" value="{{ $barang->item_name }}" placeholder="Enter item name"
                            class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div class="form-group">
                        <label>Item Type</label>
                        <input type="text" name="item_type" value="{{ $barang->item_type }}"
                            placeholder="e.g., Sound System, Tables, etc." class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="quantity" value="{{ $barang->quantity }}" placeholder="0" min="1"
                                class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                        <div class="form-group">
                            <label>Cost (Rp)</label>
                            <input type="number" name="cost" value="{{ $barang->cost }}" placeholder="0" min="0" step="0.01" class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="item_status" class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">-- Select Status --</option>
                            <option value="Sewa" {{ $barang->item_status == 'Sewa' ? 'selected' : '' }}>Sewa</option>
                            <option value="Pinjam" {{ $barang->item_status == 'Pinjam' ? 'selected' : '' }}>Pinjam</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Vendor</label>
                        <select name="vendor_id" class="mr-2 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Select Vendor (Optional) --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->vendor_id }}" {{ $barang->vendor_id == $vendor->vendor_id ? 'selected' : '' }}>
                                    {{ $vendor->vendor_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
    @foreach($barangs as $barang)
        <div id="viewBarang{{ $barang->item_id }}" class="modal hidden">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Item Details</h3>
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
                            <span
                                class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800 ml-2">
                                Sewa
                            </span>
                        @else
                            <span
                                class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800 ml-2">
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
                        <span class="text-sm font-semibold text-gray-900 ml-2">Rp
                            {{ number_format($barang->cost ?? 0, 0, ',', '.') }}</span>
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