<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
        <p>Dashboard - {{ auth()->user()->role }}</p>
        <b>{{ auth()->user()->full_name }}</b>
    </div>
    <div class="stats-grid">
        <div class="stat-card blue">
            <h3>Total Users</h3>
            <div class="number">{{ $totalUsers }}</div>
        </div>

        <div class="stat-card purple">
            <h3>Active Users</h3>
            <div class="number">{{ $activeUsers }}</div>
        </div>

        <div class="stat-card pink">
            <h3>Inactive Users</h3>
            <div class="number">{{ $inactiveUsers }}</div>
        </div>
    </div>


    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        + Add New User
    </a>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        <span class="status-badge {{ $user->status === 'active' ? 'status-active' : 'status-inactive' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-small">Edit</a>

                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-small">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


</x-app-layout>