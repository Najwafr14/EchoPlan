<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="card">
        <h2>Create Event</h2>

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
                    <option value="">-- pilih kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->category_id }}">
                            {{ $cat->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary">
                Create Event
            </button>
        </form>
    </div>
</x-app-layout>
