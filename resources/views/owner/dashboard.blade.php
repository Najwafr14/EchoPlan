<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">☰</a>
        <p>Dashboard {{ auth()->user()->role }}</p>
        <b>{{ auth()->user()->full_name }}</b>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Owner Dashboard</h1>
                <p class="text-gray-600 mt-1">Overview of all events and financial performance</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-blue-100 p-5 rounded-xl">
                    <p class="text-sm text-gray-600">Total Revenue</p>
                    <h2 class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                </div>
                <div class="bg-purple-100 p-5 rounded-xl">
                    <p class="text-sm text-gray-600">Total Events</p>
                    <h2 class="text-3xl font-bold">{{ $totalEvents }}</h2>
                </div>
                <div class="bg-pink-100 p-5 rounded-xl">
                    <p class="text-sm text-gray-600">Active Events</p>
                    <h2 class="text-3xl font-bold">{{ $activeEvents }}</h2>
                </div>
                <div class="bg-yellow-100 p-5 rounded-xl">
                    <p class="text-sm text-gray-600">Team Members</p>
                    <h2 class="text-3xl font-bold">{{ $teamMembers }}</h2>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Analytics</h3>
                <div class="mb-6">
                    <form method="GET" action="{{ route('owner.dashboard') }}"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input type="hidden" name="table_year" value="{{ $tableYear }}">
                        <input type="hidden" name="table_month" value="{{ $tableMonth }}">
                        <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                            <select name="chart_year"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @foreach($years as $y)
                                    <option value="{{ $y }}" {{ $chartYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                            <select name="chart_month"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Months</option>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $chartMonth == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Event</label>
                            <select name="chart_event_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Events</option>
                                @foreach($allEvents as $event)
                                    <option value="{{ $event->event_id }}" {{ $chartEventId == $event->event_id ? 'selected' : '' }}>
                                        {{ $event->event_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                            <button type="submit"
                                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Apply Filter
                            </button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trend</h3>
                        <canvas id="revenueChart"></canvas>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Distribution by Category</h3>
                        <canvas id="distributionChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Event Performance Report</h3>
                </div>

                <div class="p-6 border-b bg-gray-50">
                    <form method="GET" action="{{ route('owner.dashboard') }}">
                        <input type="hidden" name="chart_year" value="{{ $chartYear }}">
                        <input type="hidden" name="chart_month" value="{{ $chartMonth }}">
                        <input type="hidden" name="chart_event_id" value="{{ $chartEventId }}">

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                                <select name="table_year" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="">All Years</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ $tableYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                                <select name="table_month" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="">All Months</option>
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $tableMonth == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                                <select name="sort_by" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="date" {{ $sortBy == 'date' ? 'selected' : '' }}>Date (Latest)</option>
                                    <option value="revenue" {{ $sortBy == 'revenue' ? 'selected' : '' }}>Revenue (Highest)
                                    </option>
                                    <option value="profit" {{ $sortBy == 'profit' ? 'selected' : '' }}>Profit (Highest)
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    Apply Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th>Event</th>
                                <th>Category</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Budget</th>
                                <th>Revenue</th>
                                <th>Profit</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($eventPerformance as $perf)
                                <tr class="hover:bg-gray-50">
                                    <td>
                                        <div class="text-sm font-medium text-gray-900">{{ $perf['event_name'] }}</div>
                                    </td>
                                    <td>
                                        {{ $perf['category'] }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($perf['date'])->format('d M Y') }}
                                    </td>
                                    <td>
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full 
                                                    {{ $perf['status'] == 'Upcoming' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $perf['status'] }}
                                        </span>
                                    </td>
                                    <td>
                                        Rp {{ number_format($perf['budget'], 0, ',', '.') }}
                                    </td>
                                    <td>
                                        Rp {{ number_format($perf['revenue'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-sm font-semibold 
                                                {{ $perf['profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        Rp {{ number_format($perf['profit'], 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('owner.report.index') }}" class="btn btn-primary">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        No events found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($revenueData, 'month')) !!},
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: {!! json_encode(array_column($revenueData, 'revenue')) !!},
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        const distributionCtx = document.getElementById('distributionChart').getContext('2d');
        new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($eventDistribution->pluck('category')) !!},
                datasets: [{
                    data: {!! json_encode($eventDistribution->pluck('count')) !!},
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(168, 85, 247)',
                        'rgb(34, 197, 94)',
                        'rgb(251, 146, 60)',
                        'rgb(236, 72, 153)',
                        'rgb(14, 165, 233)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</x-app-layout>