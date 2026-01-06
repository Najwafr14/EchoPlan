<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('event.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <i class="fa-solid fa-chevron-left text-2xl"></i>
            </a>
            <h1 class="text-3xl font-bold">
                {{ $event->event_name }}
            </h1>
        </div>
        <div class="grid gap-4">

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-gray-500 text-sm">Description</p>
                <p>{{ $event->event_description ?? '-' }}</p>

                <div class="mt-4">
                    <p class="text-gray-500 text-sm">Date</p>
                    <p>{{ $event->event_date ?? '-' }}</p>
                </div>

                <div class="mt-4">
                    <p class="text-gray-500 text-sm">Venue</p>
                    @if($primaryVenue)
                        <p>{{ $primaryVenue->venue_name }}</p>
                        @if($primaryVenue->venue_address)
                            <p class="text-sm text-gray-600">{{ $primaryVenue->venue_address }}</p>
                        @endif
                    @else
                        <p class="text-gray-400 italic">To Be Decided</p>
                    @endif
                </div>

                <div class="mt-4">
                    <p class="text-gray-500 text-sm mb-2">Progress</p>

                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $progressPercent }}%"></div>
                    </div>

                    <p class="mt-2 text-sm">
                        {{ $completedTasks }} of {{ $totalTasks }} tasks completed
                        ({{ $progressPercent }}%)
                    </p>
                </div>

            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-green-100 p-5 rounded-xl shadow">
                <p class="text-sm text-green-700 font-medium">Total Income</p>
                <p class="text-2xl font-bold text-green-900">
                    Rp {{ number_format($totalIncome, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-red-100 p-5 rounded-xl shadow">
                <p class="text-sm text-red-700 font-medium">Total Expense</p>
                <p class="text-2xl font-bold text-red-900">
                    Rp {{ number_format($totalExpense, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-blue-100 p-4 rounded-xl shadow">
                <p class="text-sm text-blue-500">Task Done</p>
                <p class="text-xl font-bold">{{ $completedTasks }}</p>
            </div>
            <div class="bg-yellow-100 p-4 rounded-xl shadow">
                <p class="text-sm text-yellow-500">Needs Revision</p>
                <p class="text-xl font-bold">{{ $revisionTasks }}</p>
            </div>
            <div class="bg-red-100 p-4 rounded-xl shadow">
                <p class="text-sm text-red-500">Need Attention</p>
                <p class="text-xl font-bold">{{ $needAttentionTasks }}</p>
            </div>
            <div class="bg-purple-100 p-4 rounded-xl shadow">
                <p class="text-sm text-purple-500">Next Meeting</p>
                <p class="text-xl font-bold">
                    {{ $nextMeeting
    ? $nextMeeting->meeting_date . ' - ' . $nextMeeting->meeting_name
    : '-' }}
                </p>
            </div>

        </div>
        <div class="event-nav">
            <a href="{{ route('event.committee.index', $event->event_id) }}" class="event-tab">Structure</a>
            <a href="{{ route('event.timeline.index', $event->event_id) }}" class="event-tab">Timeline</a>
            <a href="{{ route('user.event.task.index', $event->event_id) }}" class="event-tab">Task</a>
            <a href="{{ route('user.event.budget.index', $event->event_id) }}" class="event-tab">Budget</a>
            <a href="{{ route('event.talent.index', $event->event_id) }}" class="event-tab">Talent</a>
            <a href="{{ route('event.venue.index', $event->event_id) }}" class="event-tab">Venue</a>
            <a href="{{ route('event.vendor.index', $event->event_id) }}" class="event-tab">Vendor</a>
            <a href="{{ route('event.sponsor.index', $event->event_id) }}" class="event-tab">Sponsor</a>
            <a href="{{ route('event.barang.index', $event->event_id) }}" class="event-tab">Item</a>
        </div>


    </div>

</x-app-layout>