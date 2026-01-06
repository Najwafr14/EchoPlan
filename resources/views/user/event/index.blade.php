<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="container p-6 bg-white mt-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">My Events</h2>
            <a href="{{ route('event.create') }}" class="btn btn-primary">
                + Create Event
            </a>
        </div>
        <form method="GET" action="{{ route('event.index') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search event name..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <select name="sort"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Created</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)
                        </option>
                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date (Oldest)
                        </option>
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date (Newest)
                        </option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary px-6">
                        Filter
                    </button>
                </div>
            </div>
        </form>
        @if(request()->hasAny(['search', 'status']))
            <div class="mb-4 flex gap-2 flex-wrap">
                @if(request('search'))
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                        Search: "{{ request('search') }}"
                        <a href="{{ route('event.index', array_merge(request()->except('search'))) }}"
                            class="ml-1 text-blue-600 hover:text-blue-800">×</a>
                    </span>
                @endif
                @if(request('status'))
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                        Status: {{ ucfirst(request('status')) }}
                        <a href="{{ route('event.index', array_merge(request()->except('status'))) }}"
                            class="ml-1 text-green-600 hover:text-green-800">×</a>
                    </span>
                @endif
            </div>
        @endif
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                        @php
                            $progress = $event->total_tasks > 0
                                ? round(($event->completed_tasks / $event->total_tasks) * 100)
                                : 0;
                        @endphp
                        <tr>
                            <td><strong>{{ $event->event_name }}</strong></td>
                            <td>{{ $event->category->category_name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</td>
                            <td style="width:200px">
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $progress }}%">
                                        {{ $progress }}%
                                    </div>
                                </div>
                                <small>{{ $event->completed_tasks }} of {{ $event->total_tasks }} tasks completed</small>
                            </td>
                            <td>
                                @if (\Carbon\Carbon::parse($event->event_date)->isPast())
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Completed
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('event.show', $event->event_id) }}" class="btn btn-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                @if(request()->hasAny(['search', 'status']))
                                    No events found matching your filters.
                                    <a href="{{ route('event.index') }}" class="text-blue-600 hover:underline ml-2">Clear
                                        filters</a>
                                @else
                                    No events found.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>