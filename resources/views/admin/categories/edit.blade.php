<x-app-layout>
    <h2>Edit Category</h2>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @csrf
        @method('PUT')

        <div>
            <label>Category Name</label>
            <input name="category_name"
                   value="{{ $category->category_name }}"
                   required>
        </div>

        <div>
            <label>Description</label>
            <textarea name="description">{{ $category->description }}</textarea>
        </div>

        <button type="submit">Update</button>
    </form>
</x-app-layout>
