<x-app-layout>
    <div class="page-container">

        {{-- HEADER --}}
        <div class="page-header">
            <h1 class="page-title">My Events</h1>

            <a href="{{ route('event.create') }}"
               class="btn btn-primary">
                + Create Event
            </a>
        </div>

        {{-- TABLE --}}
        <div class="card">
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
                            <small>{{ $event->completed_tasks }} of {{ $event->total_tasks }} tasks</small>
                        </td>

                        <td>
                            @if (\Carbon\Carbon::parse($event->event_date)->isPast())
                                <span class="badge badge-inactive">Completed</span>
                            @else
                                <span class="badge badge-active">Active</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('event.show', $event->event_id) }}"
                            class="btn btn-primary">
                                View
                            </a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            No events found.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

    </div>
</x-app-layout>
