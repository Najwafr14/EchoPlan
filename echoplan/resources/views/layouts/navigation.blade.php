<div class="sidebar">

    <div class="logo">Echo Plan</div>

    {{-- ================= ADMIN ================= --}}
    @if(auth()->user()->role === 'Admin')
        <a href="{{ route('admin.dashboard') }}" class="menu-item">
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.users') }}" class="menu-item">
            <span>User</span>
        </a>

        <a href="{{ route('admin.categories') }}" class="menu-item">
            <span>Category</span>
        </a>
    @endif


    {{-- ================= USER ================= --}}
    @if(auth()->user()->role === 'User')
        <a href="{{ route('user.dashboard') }}" class="menu-item">
            <span>Dashboard</span>
        </a>

        <a href="{{ route('event.index') }}" class="menu-item">
            <span>Event</span>
        </a>

        <a href="{{ route('task.index') }}" class="menu-item">
            <span>Task</span>
        </a>

        <a href="{{ route('tools.index') }}" class="menu-item">
            <span>Tools</span>
        </a>

        <a href="{{ route('meeting.index') }}" class="menu-item">
            <span>Meeting</span>
        </a>
    @endif


    {{-- ================= OWNER ================= --}}
    @if(auth()->user()->role === 'Owner')
        <a href="{{ route('owner.dashboard') }}" class="menu-item">
            <span>Dashboard</span>
        </a>

        <a href="{{ route('owner.event-report') }}" class="menu-item">
            <span>Event Report</span>
        </a>

        <a href="{{ route('owner.monthly-report') }}" class="menu-item">
            <span>Monthly Report</span>
        </a>

        <a href="{{ route('owner.annual-report') }}" class="menu-item">
            <span>Annual Report</span>
        </a>

        <a href="{{ route('owner.financial-report') }}" class="menu-item">
            <span>Financial Report</span>
        </a>

        <a href="{{ route('owner.team-performance') }}" class="menu-item">
            <span>Team Performance</span>
        </a>
    @endif

    <a href="{{ route('profile.show') }}" class="menu-item">
        <span>Profile</span>
    </a>
    <div style="margin-top: 40px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="menu-item logout">
                <span>Logout</span>
            </button>
        </form>
    </div>

</div>