<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="py-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('event.show', $event->event_id) }}"
                class="text-blue-600 hover:text-blue-800 flex items-center">
                <i class="fa-solid fa-chevron-left text-2xl"></i>
            </a>
            <h1 class="text-3xl font-bold">
                Budget {{ $event->event_name }}
            </h1>
        </div>
        <div class="grid grid-cols-1 mt-4 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-green-100 p-6 rounded-lg shadow">
                <p class="text-sm text-green-700 font-medium">Total Income</p>
                <h2 class="text-3xl font-bold text-green-900">
                    Rp {{ number_format($summary['Income'], 0, ',', '.') }}
                </h2>
            </div>
            <div class="bg-red-100 p-6 rounded-lg shadow">
                <p class="text-sm text-red-700 font-medium">Total Expense</p>
                <h2 class="text-3xl font-bold text-red-900">
                    Rp {{ number_format($summary['Expense'], 0, ',', '.') }}
                </h2>
            </div>
            <div class="bg-blue-100 p-6 rounded-lg shadow">
                <p class="text-sm text-blue-700 font-medium">Balance</p>
                <h2 class="text-3xl font-bold {{ $summary['Balance'] >= 0 ? 'text-blue-900' : 'text-red-900' }}">
                    Rp {{ number_format($summary['Balance'], 0, ',', '.') }}
                </h2>
            </div>
            <div class="bg-yellow-100 p-6 rounded-lg shadow">
                <p class="text-sm text-yellow-700 font-medium">Pending</p>
                <h2 class="text-3xl font-bold text-yellow-900">{{ $summary['Pending'] }}</h2>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-bold text-gray-800">Budget List</h2>
                <button data-modal="addBudgetModal" class="btn btn-primary">
                    + Add Budget
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th>Transaction</th>
                            <th>Type</th>
                            <th>Item</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($budgets as $budget)
                            <tr>
                                <td>
                                    @if($budget->transaction_type == 'expense')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Expense
                                        </span>
                                    @elseif($budget->transaction_type == 'income')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Income
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-sm font-medium text-gray-900">{{ $budget->budget_type }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-gray-900">{{ $budget->budget_item }}</span>
                                </td>
                                <td>
                                    <span class="text-sm font-semibold text-gray-900">Rp
                                        {{ number_format($budget->amount, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    @if($budget->status == 'Pending')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($budget->status == 'Approved')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Approved
                                        </span>
                                    @elseif($budget->status == 'Paid')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Paid
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <button data-modal="editBudget{{ $budget->budget_id }}"
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

    <div id="addBudgetModal" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add New Budget</h3>
            <form method="POST" action="{{ route('user.event.budget.store', $event->event_id) }}">
                @csrf
                <div class="grid grid-cols-2 gap-4 ">
                    <div class="form-group">
                        <label>Transaction Type</label>
                        <select name="transaction_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                            <option value="">-- Select Transaction Type --</option>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="budget_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                            <option value="">-- Select Type --</option>
                            <option value="Venue">Venue</option>
                            <option value="Vendor">Vendor</option>
                            <option value="Talent">Talent</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 ">
                    <div class="form-group">
                        <label>Item</label>
                        <input type="text" name="budget_item" placeholder="Enter budget item"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Amount (Rp)</label>
                        <input type="number" name="amount" placeholder="0" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 ">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Optional --</option>
                            <option value="Cash">Cash</option>
                            <option value="Transfer">Transfer</option>
                            <option value="E-Wallet">E-Wallet</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Paid">Paid</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Payment Date</label>
                    <input type="date" name="payment_date"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes" rows="2" placeholder="Optional notes"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    @foreach($budgets as $budget)
        <div id="editBudget{{ $budget->budget_id }}" class="modal hidden">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Budget Details</h3>
                <div class="mb-4 p-4 bg-gray-50 rounded">
                    <div class="mb-2">
                        <span class="text-sm font-medium text-gray-600">Transaction Type:</span>
                        <span class="text-sm text-gray-900 ml-2">{{ ucfirst($budget->transaction_type) }}</span>
                    </div>
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
                        <span class="text-sm font-semibold text-gray-900 ml-2">Rp
                            {{ number_format($budget->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-sm font-medium text-gray-600">Payment Method:</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $budget->payment_method ?? '-' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-sm font-medium text-gray-600">Payment Date:</span>
                        <span class="text-sm text-gray-900 ml-2">
                            {{ $budget->payment_date ? $budget->payment_date->format('d M Y') : '-' }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <span class="text-sm font-medium text-gray-600">Status:</span>
                        <span class="text-sm text-gray-900 ml-2">{{ $budget->status }}</span>
                    </div>
                    @if($budget->notes)
                        <div class="mb-2">
                            <span class="text-sm font-medium text-gray-600">Notes:</span>
                            <span class="text-sm text-gray-900 ml-2">{{ $budget->notes }}</span>
                        </div>
                    @endif
                </div>
                @if($isTreasurer ?? false)
                    <form method="POST"
                        action="{{ route('user.event.budget.update', [$event->event_id, $budget->budget_id]) }}">
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
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select name="payment_method">
                                <option value="">-- Optional --</option>
                                <option value="Cash" {{ $budget->payment_method == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Transfer" {{ $budget->payment_method == 'Transfer' ? 'selected' : '' }}>Transfer
                                </option>
                                <option value="E-Wallet" {{ $budget->payment_method == 'E-Wallet' ? 'selected' : '' }}>E-Wallet
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Payment Date</label>
                            <input type="date" name="payment_date"
                                value="{{ $budget->payment_date ? $budget->payment_date->format('Y-m-d') : '' }}">
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" rows="2">{{ $budget->notes }}</textarea>
                        </div>
                        <div class="flex justify-end mt-4 gap-2">
                            <button type="submit" class="btn btn-primary">Update Status</button>
                            <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                        </div>
                    </form>
                @else
                    <div class="flex justify-end mt-4">
                        <button type="button" class="btn btn-secondary close-modal">Close</button>
                    </div>
                @endif
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