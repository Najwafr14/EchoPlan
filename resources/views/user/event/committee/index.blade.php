<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <a href="{{ route('event.show', $event->event_id) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <i class="fa-solid fa-chevron-left text-2xl"></i>
            </a>
            <h2 class="text-xl font-bold">
                Committee Structure - {{ $event->event_name }}
            </h2>

            @if($isChief)
            <button class="btn btn-primary" data-modal="addMemberModal">
                + Add Member
            </button>
            @endif
        </div>

        <div class="division-grid mt-6">
            @foreach ($divisionTypes as $type)
            @php
            $division = $divisions->get($type->division_type_id);
            @endphp

            <div class="division-card">
                <h3>{{ $type->type_name }}</h3>

                <div class="member-list">
                    @if ($division && $division->members->count())
                    @foreach ($division->members as $member)
                    <div class="member-item">
                        <strong>{{ $member->user->full_name }}</strong><br>
                        <small>{{ $member->role_in_division }}</small>
                    </div>
                    @endforeach
                    @else
                    <small class="text-muted">No members yet</small>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

    </div>
    @if($isChief)
    <div id="addMemberModal" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add Committee Member</h3>
            <form method="POST" action="{{ route('event.committee.store', $event) }}">
                @csrf
                <div class="form-group">
                    <label>Division</label>
                    <select name="division_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @foreach ($divisionTypes as $type)
                        <option value="{{ $type->division_type_id }}">
                            {{ $type->type_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Position</label>
                    <select name="role_in_division" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="Leader">Leader</option>
                        <option value="Member">Member</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Member</label>
                    <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @foreach ($users as $user)
                        <option value="{{ $user->user_id }}">
                            {{ $user->full_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary close-modal">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <script>
        document.querySelectorAll('[data-modal]').forEach(btn => {
            btn.addEventListener('click', () => {
                const modalId = btn.getAttribute('data-modal');
                document.getElementById(modalId).classList.remove('hidden');
            });
        });
        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('.modal').classList.add('hidden');
            });
        });
    </script>

</x-app-layout>