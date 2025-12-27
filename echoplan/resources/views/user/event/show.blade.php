<x-app-layout>
    <div class="space-y-6">

        {{-- Event Title --}}
        <h1 class="text-3xl font-bold">
            {{ $event->event_name }}
        </h1>
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
                    <p>{{ $event->venue->venue_name ?? '-' }}</p>
                </div>

                <div class="mt-4">
                    <p class="text-gray-500 text-sm mb-2">Progress</p>

                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $progressPercent }}%"></div>
                    </div>

                    <p class="mt-2 text-sm">
                        {{ $completedTasks }} of {{ $totalTasks }} tasks
                        ({{ $progressPercent }}%)
                    </p>
                </div>

            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-gray-500 text-sm">Total Income</p>
                <p class="text-xl font-semibold">-</p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <p class="text-gray-500 text-sm">Total Expense</p>
                <p class="text-xl font-semibold">-</p>
            </div>

        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="bg-white p-4 rounded-xl shadow">
                <p class="text-sm text-gray-500">Task Done</p>
                <p class="text-xl font-bold">{{ $completedTasks }}</p>
            </div>

            <div class="bg-white p-4 rounded-xl shadow">
                <p class="text-sm text-gray-500">Needs Revision</p>
                <p class="text-xl font-bold">{{ $revisionTasks }}</p>
            </div>

            <div class="bg-white p-4 rounded-xl shadow">
                <p class="text-sm text-gray-500">Need Attention</p>
                <p class="text-xl font-bold">{{ $needAttentionTasks }}</p>
            </div>

            <div class="bg-white p-4 rounded-xl shadow">
                <p class="text-sm text-gray-500">Next Meeting</p>
                <p class="text-sm">
                    {{ $nextMeeting
    ? $nextMeeting->meeting_date . ' - ' . $nextMeeting->meeting_name
    : '-' }}
                </p>
            </div>

        </div>
        <div class="event-nav">
            <a href="{{ route ('event.committee.index', $event->event_id) }}" class="event-tab">Structure</a>
            <a href="#" class="event-tab">Timeline</a>
            <a href="#" class="event-tab">Task</a>
            <a href="#" class="event-tab">Budget</a>
            <a href="#" class="event-tab">Talent</a>
            <a href="#" class="event-tab">Venue</a>
            <a href="#" class="event-tab">Vendor</a>
            <a href="#" class="event-tab">Sponsor</a>
        </div>


    </div>

</x-app-layout>