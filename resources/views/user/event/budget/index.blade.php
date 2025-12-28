<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-yellow-100 p-6 rounded-lg shadow">
                    <p class="text-sm text-yellow-700 font-medium">Pending</p>
                    <h2 class="text-3xl font-bold text-yellow-900">{{ $summary['Pending'] ?? 0 }}</h2>
                </div>
                <div class="bg-blue-100 p-6 rounded-lg shadow">
                    <p class="text-sm text-blue-700 font-medium">Approved</p>
                    <h2 class="text-3xl font-bold text-blue-900">{{ $summary['Approved'] ?? 0 }}</h2>
                </div>
                <div class="bg-green-100 p-6 rounded-lg shadow">
                    <p class="text-sm text-green-700 font-medium">Paid</p>
                    <h2 class="text-3xl font-bold text-green-900">{{ $summary['Paid'] ?? 0 }}</h2>
                </div>
            </div>

            <!-- Budget Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="flex justify-between items-center p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800">Budget List</h2>
                    <button
                        data-modal="addBudgetModal"
                        class="btn btn-primary">
                        + Add Budget
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($budgets as $budget)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $budget->budget_type }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $budget->budget_item }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($budget->amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($budget->status == 'Pending')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                    @elseif($budget->status == 'Approved')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Approved
                                    </span>
                                    @elseif($budget->status == 'Paid')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Paid
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button
                                        data-modal="editBudget{{ $budget->budget_id }}"
                                        class="text-blue-600 hover:text-blue-900 font-medium">
                                        View
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No budget items yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Add Budget Modal -->
    <div id="addBudgetModal" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add New Budget</h3>
            <form method="POST" action="{{ route('user.event.budget.store', $event->event_id) }}">
                @csrf
                <div class="form-group">
                    <label>Type</label>
                    <select name="budget_type" required>
                        <option value="">-- Select Type --</option>
                        <option value="Venue">Venue</option>
                        <option value="Vendor">Vendor</option>
                        <option value="Talent">Talent</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Item</label>
                    <input type="text" name="budget_item" placeholder="Enter budget item" required>
                </div>
                <div class="form-group">
                    <label>Amount (Rp)</label>
                    <input type="number" name="amount" placeholder="0" min="0" required>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Budget Modals (One for each budget) -->
    @foreach($budgets as $budget)
    <div id="editBudget{{ $budget->budget_id }}" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Budget Details</h3>

            <!-- Budget Info -->
            <div class="mb-4 p-4 bg-gray-50 rounded">
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Type:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $budget->budget_type }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Item:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $budget->budget_item }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium text-gray-600">Amount:</span>
                    <span class="text-sm font-semibold text-gray-900 ml-2">Rp {{ number_format($budget->amount, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($isTreasurer ?? false)
            <!-- Update Status Form (Only for Treasurer) -->
            <form method="POST" action="{{ route('user.event.budget.update', [$event->event_id, $budget->budget_id]) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Update Status</label>
                    <select name="status" required>
                        <option value="Pending" {{ $budget->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ $budget->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Paid" {{ $budget->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <div class="flex justify-end mt-4 gap-2">
                    <button type="submit" class="btn btn-primary">Update Status</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
            @else
            <!-- Non-treasurer: Show only close button -->
            <div class="flex justify-end mt-4">
                <button type="button" class="btn btn-secondary close-modal">Close</button>
            </div>
            @endif
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