<x-app-layout>
    <div class="card">

        {{-- HEADER --}}
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h2 class="card-title">{{ $meeting->meeting_name }}</h2>
                <p style="color:#666; margin-top:4px;">
                    {{ \Carbon\Carbon::parse($meeting->meeting_date)->translatedFormat('d F Y') }}
                    • {{ $meeting->meeting_time }}
                </p>
            </div>

            <a href="{{ route('meeting.index') }}" class="btn btn-secondary">
                ← Back
            </a>
        </div>

        {{-- DETAIL INFO --}}
        <div class="profile-grid">

            <div class="profile-field">
                <label>Meeting Place</label>
                <p>{{ $meeting->meeting_place }}</p>
            </div>

            <div class="profile-field">
                <label>Agenda</label>
                <p>{{ $meeting->agenda ?? '-' }}</p>
            </div>

        </div>
    </div>

    {{-- NOTES / NOTULENSI --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Meeting Notes</h3>
        </div>

        <form method="POST" action="#">
            @csrf

            <textarea name="notes" rows="8" class="form-input" placeholder="Tulis notulensi meeting di sini..."
                style="width:100%; resize:vertical;">{{ old('notes', $meeting->notes) }}</textarea>

            <div style="margin-top:15px; text-align:right;">
                <button class="btn btn-primary">
                    Save Notes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>