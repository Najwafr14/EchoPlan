<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Sponsor Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800">Sponsor List</h2>
                    <button
                        data-modal="addSponsorModal"
                        class="btn btn-primary">
                        + Add Sponsor
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sponsor Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contribution</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($sponsors as $sponsor)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $sponsor->sponsor_name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($sponsor->sponsor_type == 'Utama')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Utama
                                    </span>
                                    @elseif($sponsor->sponsor_type == 'Pendukung')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Pendukung
                                    </span>
                                    @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                                        Lainnya
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($sponsor->contribution_amount ?? 0, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $sponsor->contact_info ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button
                                        data-modal="editSponsor{{ $sponsor->sponsor_id }}"
                                        class="text-blue-600 hover:text-blue-900 font-medium mr-3">
                                        Edit
                                    </button>
                                    <button
                                        data-modal="viewSponsor{{ $sponsor->sponsor_id }}"
                                        class="text-green-600 hover:text-green-900 font-medium">
                                        View
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No sponsors yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Sponsor Modal -->
    <div id="addSponsorModal" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add New Sponsor</h3>
            <form method="POST" action="{{ route('event.sponsor.store', $eventId) }}">
                @csrf
                <div class="form-group">
                    <label>Sponsor Name</label>
                    <input type="text" name="sponsor_name" placeholder="Enter sponsor name" required>
                </div>
                <div class="form-group">
                    <label>Sponsor Type</label>
                    <select name="sponsor_type" required>
                        <option value="">-- Select Type --</option>
                        <option value="Utama">Utama</option>
                        <option value="Pendukung">Pendukung</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Contribution Amount (Rp)</label>
                    <input type="number" name="contribution_amount" placeholder="0" min="0" step="0.01">
                </div>
                <div class="form-group">
                    <label>Contact Info</label>
                    <input type="text" name="contact_info" placeholder="Phone number or email">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Sponsor Modals (One for each sponsor) -->
    @foreach($sponsors as $sponsor)
    <div id="editSponsor{{ $sponsor->sponsor_id }}" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Edit Sponsor</h3>
            <form method="POST" action="{{ route('event.sponsor.update', [$eventId, $sponsor->sponsor_id]) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Sponsor Name</label>
                    <input type="text" name="sponsor_name" value="{{ $sponsor->sponsor_name }}" placeholder="Enter sponsor name" required>
                </div>
                <div class="form-group">
                    <label>Sponsor Type</label>
                    <select name="sponsor_type" required>
                        <option value="">-- Select Type --</option>
                        <option value="Utama" {{ $sponsor->sponsor_type == 'Utama' ? 'selected' : '' }}>Utama</option>
                        <option value="Pendukung" {{ $sponsor->sponsor_type == 'Pendukung' ? 'selected' : '' }}>Pendukung</option>
                        <option value="Lainnya" {{ $sponsor->sponsor_type == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Contribution Amount (Rp)</label>
                    <input type="number" name="contribution_amount" value="{{ $sponsor->contribution_amount }}" placeholder="0" min="0" step="0.01">
                </div>
                <div class="form-group">
                    <label>Contact Info</label>
                    <input type="text" name="contact_info" value="{{ $sponsor->contact_info }}" placeholder="Phone number or email">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <!-- View Sponsor Modals (One for each sponsor) -->
    @foreach($sponsors as $sponsor)
    <div id="viewSponsor{{ $sponsor->sponsor_id }}" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Sponsor Details</h3>
            <!-- Sponsor Info -->
            <div class="mb-4 p-4 bg-gray-50 rounded">
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Sponsor Name:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $sponsor->sponsor_name }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Type:</span>
                    @if($sponsor->sponsor_type == 'Utama')
                    <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-purple-100 text-purple-800 ml-2">
                        Utama
                    </span>
                    @elseif($sponsor->sponsor_type == 'Pendukung')
                    <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800 ml-2">
                        Pendukung
                    </span>
                    @else
                    <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-600 ml-2">
                        Lainnya
                    </span>
                    @endif
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Contribution Amount:</span>
                    <span class="text-sm font-semibold text-gray-900 ml-2">Rp {{ number_format($sponsor->contribution_amount ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Contact Info:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $sponsor->contact_info ?? '-' }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Created:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $sponsor->created_at->format('d M Y, H:i') }}</span>
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