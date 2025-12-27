<x-app-layout>

    <div class="page-header">
        <h2>Create Meeting</h2>
    </div>

    <form method="POST" action="{{ route('meeting.store') }}" class="card">
        @csrf

        <div class="form-group">
            <label>Meeting Name</label>
            <input type="text" name="meeting_name" class="form-input" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="meeting_date" class="form-input" required>
            </div>

            <div class="form-group">
                <label>Time</label>
                <input type="time" name="meeting_time" class="form-input" required>
            </div>
        </div>

        <div class="form-group">
            <label>Meeting Place</label>
            <input type="text" name="meeting_place" class="form-input" required>
        </div>

        <div class="form-group">
            <label>Agenda</label>
            <textarea name="agenda" rows="4" class="form-input"></textarea>
        </div>

        <div class="form-actions">
            <a href="{{ route('meeting.index') }}" class="btn btn-secondary">
                Cancel
            </a>
            <button class="btn btn-primary">
                Save Meeting
            </button>
        </div>
    </form>

</x-app-layout>