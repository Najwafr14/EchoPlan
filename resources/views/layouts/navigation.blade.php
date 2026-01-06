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

        <a href="{{ route('document.index') }}" class="menu-item">
            <span>Document</span>
        </a>

        <a href="{{ route('meetings.index') }}" class="menu-item">
            <span>Meeting</span>
        </a>
    @endif


    {{-- ================= OWNER ================= --}}
    @if(auth()->user()->role === 'Owner')
        <a href="{{ route('owner.dashboard') }}" class="menu-item">
            <span>Dashboard</span>
        </a>

        <a href="{{ route('owner.report.index') }}" class="menu-item">
            <span>Event Report</span>
        </a>
    @endif

    <a href="{{ route('profile.show') }}" class="menu-item">
        <span>Profile</span>
    </a>
    {{-- Spacer untuk push logout ke bawah --}}
    <div style="flex-grow: 1;"></div>
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="menu-item logout-btn">
            <span>Logout</span>
        </button>
    </form>
</div>

<style>
/* Logout Button - RED */
.logout-btn {
    width: 100%;
    border: none;
    text-align: left;
    cursor: pointer;
    color: #fff;
    transition: all 0.3s ease;
    margin: 2px 0;
    z-index: 10;
}

.logout-btn:hover {
    background: #ef4444;
    color: #fff;
}

.logout-btn span {
    display: flex;
    align-items: center;
    font-size: 15px;
}

</style>

</div>