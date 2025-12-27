<x-app-layout>

    <div class="header">
        <h2>Meeting List</h2>

    </div>

    <a href="{{ route('meeting.create') }}" class="btn btn-primary" style="justify-self:end ; grid-row :3">+ Create Meeting</a>
    <table>
        <thead>
            <tr>
                <th>Meeting Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Place</th>
                <th>Agenda</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($meetings as $meeting)
                <tr>
                    <td>{{ $meeting->meeting_name }}</td>
                    <td>{{ $meeting->meeting_date }}</td>
                    <td>{{ $meeting->meeting_time }}</td>
                    <td>{{ $meeting->meeting_place ?? '-' }}</td>
                    <td>{{ Str::limit($meeting->agenda, 40) }}</td>
                    <td>
                        <a href="#" class="btn-sm">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">
                        No meetings found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</x-app-layout>