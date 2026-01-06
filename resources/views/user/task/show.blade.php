<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="max-w-5xl mx-auto py-6 space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('task.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <i class="fa-solid fa-chevron-left text-2xl"></i>
            </a>
            <h1 class="text-3xl font-bold">
                Task Detail
            </h1>
        </div>
        
        <div class="bg-white p-6 rounded shadow">
            <h1 class="text-2xl"><b>{{ $task->task_name }}</b> <span class="text-sm px-3 py-1 rounded-full 
                @if($task->status == 'Completed') bg-green-100 text-green-800
                @elseif($task->status == 'Blocked') bg-red-100 text-red-800
                @elseif(str_contains($task->status, 'Review')) bg-blue-100 text-blue-800
                @else bg-yellow-100 text-yellow-800
                @endif">{{ $task->status }}</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $task->event->event_name }}
            </p>
            <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                <div>
                    <strong>Phase:</strong> {{ str_replace('_', ' ', $task->phase) }}
                </div>
                <div>
                    <strong>Division:</strong> {{ $task->division->division_name }}
                </div>
                <div>
                    <strong>Assignee:</strong> {{ $task->assignee->full_name ?? 'Unassigned' }}
                </div>
                <div>
                    <strong>Deadline:</strong> {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                </div>
                <div class="col-span-2">
                    <strong>Description:</strong> {{ $task->description ?? '-' }}
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Progress Update</h3>
                <button data-modal="addProgressModal" class="btn btn-primary">
                    + Add Progress
                </button>
            </div>
            <div class="space-y-4">
                @forelse($task->histories as $history)
                    <div class="border p-4 rounded">
                        <div class="flex justify-between text-sm">
                            <span class="font-semibold">{{ $history->user->full_name }}</span>
                            <span class="text-gray-500">
                                {{ $history->created_at->format('d M Y H:i') }}
                            </span>
                        </div>
                        <p class="mt-2 text-sm">
                            Status: <strong>{{ $history->new_status }}</strong>
                        </p>
                        @if($history->note)
                            <p class="mt-1 text-gray-600">{{ $history->note }}</p>
                        @endif
                        @if($history->document_path)
                            <button data-modal="viewDocumentModal" data-doc="{{ asset('storage/' . $history->document_path) }}"
                                data-user="{{ $history->user->full_name }}"
                                data-date="{{ $history->created_at->format('d M Y H:i') }}"
                                class="text-blue-600 text-sm mt-2">
                                View Document
                            </button>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-400">No progress yet</p>
                @endforelse
            </div>
        </div>

        <div id="addProgressModal" class="modal hidden">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Add Progress Update</h3>
                <form method="POST" action="{{ route('task.progress.store', $task->task_id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Status</label>
                        <select name="new_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @foreach(['Assigned', 'Revision', 'Blocked', 'UnderReview_Div', 'UnderReview_CC', 'Completed'] as $status)
                                <option value="{{ $status }}">
                                    {{ str_replace('_', ' ', $status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="note" rows="3" placeholder="Add progress note (optional)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Document</label>
                        <input type="file" name="document">
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="viewDocumentModal" class="modal hidden">
            <div class="modal-box max-w-3xl">
                <h3 class="font-bold text-lg mb-2">Document Preview</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Uploaded by <span id="docUser"></span> • <span id="docDate"></span>
                </p>
                <div id="docPreview" class="border rounded p-3 mb-4 text-center">
                </div>
                <div class="flex justify-end gap-2">
                    <a id="docDownload" href="#" download class="btn btn-primary">
                        Download
                    </a>
                    <button type="button" class="btn btn-secondary close-modal">
                        Close
                    </button>
                </div>
            </div>
        </div>

        <script>
            document.querySelectorAll('[data-modal]:not([data-doc])').forEach(btn => {
                btn.addEventListener('click', () => {
                    const modalId = btn.getAttribute('data-modal');
                    document.getElementById(modalId).classList.remove('hidden');
                });
            });

            document.querySelectorAll('[data-modal][data-doc]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const modalId = btn.getAttribute('data-modal');
                    const modal = document.getElementById(modalId);
                    const docUrl = btn.getAttribute('data-doc');
                    const user = btn.getAttribute('data-user');
                    const date = btn.getAttribute('data-date');

                    document.getElementById('docUser').innerText = user;
                    document.getElementById('docDate').innerText = date;
                    document.getElementById('docDownload').href = docUrl;

                    const preview = document.getElementById('docPreview');
                    preview.innerHTML = '<p class="text-gray-500">Loading...</p>';

                    setTimeout(() => {
                        if (docUrl.match(/\.(jpg|jpeg|png|gif|webp)$/i)) {
                            preview.innerHTML = `<img src="${docUrl}" class="max-h-[400px] mx-auto rounded shadow">`;
                        } else if (docUrl.match(/\.pdf$/i)) {
                            preview.innerHTML = `<iframe src="${docUrl}" class="w-full h-[500px] rounded" frameborder="0"></iframe>`;
                        } else {
                            preview.innerHTML = `
                                <div class="text-center py-8">
                                    <p class="text-gray-500">Preview not available</p>
                                    <p class="text-sm text-gray-400 mt-2">Click download to view the file</p>
                                </div>
                            `;
                        }
                    }, 100);

                    modal.classList.remove('hidden');
                });
            });

            document.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = btn.closest('.modal');
                    modal.classList.add('hidden');
                    const form = modal.querySelector('form');
                    if (form) form.reset();
                    const preview = document.getElementById('docPreview');
                    if (preview) preview.innerHTML = '';
                });
            });

            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', function (e) {
                    if (e.target === this) {
                        this.classList.add('hidden');
                        const form = this.querySelector('form');
                        if (form) form.reset();
                        const preview = document.getElementById('docPreview');
                        if (preview) preview.innerHTML = '';
                    }
                });
            });
        </script>
    </div>
</x-app-layout>