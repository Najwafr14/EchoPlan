<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-center mb-6">Timeline: {{ $event->name }}</h1>

        <!-- Kanban Grid (Horizontal) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach(['pre event', 'preparation', 'd day', 'post event'] as $phase)
            <div class="border-2 border-gray-300 rounded-lg p-4 bg-white">
                <!-- Phase Header -->
                <h2 class="text-lg font-semibold capitalize mb-3 pb-2 border-b-2 border-gray-300">
                    {{ str_replace('_', ' ', $phase) }}
                </h2>

                <!-- Tasks List -->
                <div class="space-y-3">
                    @if(isset($tasks[$phase]) && count($tasks[$phase]) > 0)
                    @foreach($tasks[$phase] as $task)
                    <div class="border border-gray-300 p-3 rounded {{ $task->status === 'Completed' ? 'bg-green-50' : 'bg-gray-50' }}">
                        <!-- Task Header with Checkbox -->
                        <div class="flex items-start gap-2 mb-2">
                            <div class="mt-0.5">
                                @if($task->status === 'Completed')
                                <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center text-white text-xs">✓</div>
                                @else
                                <div class="w-5 h-5 rounded-full border-2 border-gray-400"></div>
                                @endif
                            </div>
                            <h3 class="font-medium text-sm {{ $task->status === 'Completed' ? 'line-through text-gray-500' : '' }}">
                                {{ $task->task_name }}
                            </h3>
                        </div>

                        <!-- Task Info -->
                        <p class="text-xs text-gray-600 mb-2">{{ $task->description }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $task->assignedUser->name ?? 'Unassigned' }} |
                            {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                        </p>
                        <span class="inline-block mt-2 px-2 py-1 text-xs rounded border border-gray-300 bg-white">
                            {{ $task->status }}
                        </span>
                    </div>
                    @endforeach
                    @else
                    <p class="text-gray-400 text-sm text-center py-8">No tasks</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>