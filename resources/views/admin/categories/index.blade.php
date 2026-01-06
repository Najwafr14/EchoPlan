<x-app-layout>
    <h2>Event Categories</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        + Add Category
    </a>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
                <tr>
                    <td>{{ $cat->category_name }}</td>
                    <td>{{ $cat->description }}</td>
                    <td>{{ $cat->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $cat) }}">Edit</a>

                        <form action="{{ route('admin.categories.destroy', $cat) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Delete category?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>
