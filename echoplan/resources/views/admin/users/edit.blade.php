<x-app-layout>
    <h2>Edit User</h2>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <input name="full_name" value="{{ $user->full_name }}" required>

        <select name="role">
            <option {{ $user->role === 'Admin' ? 'selected' : '' }}>Admin</option>
            <option {{ $user->role === 'User' ? 'selected' : '' }}>User</option>
            <option {{ $user->role === 'Owner' ? 'selected' : '' }}>Owner</option>
        </select>

        <select name="status">
            <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>

        <button class="btn btn-primary">Update</button>
    </form>
</x-app-layout>
