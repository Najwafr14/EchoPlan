<x-app-layout>
    <h2>Add Category</h2>

    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf

        <div>
            <label>Category Name</label>
            <input name="category_name" required>
        </div>

        <div>
            <label>Description</label>
            <textarea name="description"></textarea>
        </div>

        <button type="submit">Save</button>
    </form>
</x-app-layout>
