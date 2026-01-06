<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="card">
        <div class="flex items-center gap-3 md:mb-6 mb-4">
            <a href="{{ route('event.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <i class="fa-solid fa-chevron-left text-2xl"></i>
            </a>
            <h1 class="text-3xl font-bold">
                Create Event
            </h1>
        </div>

        <form method="POST" action="{{ route('event.store') }}">
            @csrf

            <div class="form-group">
                <label>Event Name</label>
                <input name="event_name" class="form-input" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="event_description" class="form-input"></textarea>
            </div>

            <div class="form-group">
                <label>Event Date</label>
                <input type="datetime-local" name="event_date" class="form-input" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-input" required>
                    <option value="">-- Choose Category --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->category_id }}">
                            {{ $cat->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary mt-4">
                Create Event
            </button>
        </form>
    </div>
</x-app-layout>
