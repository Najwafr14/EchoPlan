<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="max-w-5xl mx-auto py-6 space-y-6">
        <h2 class="font-semibold text-xl">Task Detail</h2>
        <!-- Task Summary -->
        <div class="bg-white p-6 rounded shadow">
            <h1 class="text-2xl"><b>{{ $task->task_name }}</b> <a>{{ $task->status }}</a></h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $task->event->event_name }}
            </p>

            <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                <div>
                    <strong>Phase :</strong> {{ str_replace('_',' ',$task->phase) }}
                </div>
                <div>
                    <strong>Division :</strong> {{ $task->division->division_name }}
                </div>
                <div>
                    <strong>Assignee :</strong> {{ $task->assignee->full_name ?? 'Unassigned' }}
                </div>
                <div>
                    <strong>Description :</strong> {{ $task->description }}
                </div>
            </div>
        </div>
        <!-- Progress -->
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
                    <button
                        data-modal="viewDocumentModal"
                        data-doc="{{ asset('storage/'.$history->document_path) }}"
                        data-user="{{ $history->user->full_name }}"
                        data-date="{{ $history->created_at->format('d M Y H:i') }}"
                        class="text-blue-600 text-sm underline mt-2">
                        View Document
                    </button>
                    @endif
                </div>
                @empty
                <p class="text-gray-400">No progress yet</p>
                @endforelse
            </div>
        </div>
        {{-- MODAL PROGRESS UPDATE --}}
        <div id="addProgressModal" class="modal hidden">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Add Progress Update</h3>

                <form method="POST"
                    action="{{ route('user.event.task.progress.store', $task->task_id) }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>Status</label>
                        <select name="new_status" required>
                            @foreach(['Assigned','Revision','Blocked','UnderReview_Div','UnderReview_CC','Completed'] as $status)
                            <option value="{{ $status }}">
                                {{ str_replace('_',' ', $status) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="note" rows="3" placeholder="Add progress note (optional)"></textarea>
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
        {{-- MODAL VIEW DOCUMENT --}}
        <div id="viewDocumentModal" class="modal hidden">
            <div class="modal-box max-w-3xl">
                <h3 class="font-bold text-lg mb-2">Document Preview</h3>

                <p class="text-sm text-gray-500 mb-4">
                    Uploaded by <span id="docUser"></span> • <span id="docDate"></span>
                </p>

                <div id="docPreview" class="border rounded p-3 mb-4 text-center">
                    {{-- preview injected by JS --}}
                </div>

                <div class="flex justify-end gap-2">
                    <a id="docDownload"
                        href="#"
                        download
                        class="btn btn-primary">
                        Download
                    </a>
                    <button type="button" class="btn btn-secondary close-modal">
                        Close
                    </button>
                </div>
            </div>
        </div>

        <script>
            // Open modal
            document.querySelectorAll('[data-modal]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const modalId = btn.getAttribute('data-modal');
                    document.getElementById(modalId).classList.remove('hidden');
                });
            });

            // Close modal
            document.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = btn.closest('.modal');
                    modal.classList.add('hidden');

                    const form = modal.querySelector('form');
                    if (form) form.reset();
                });
            });

            document.querySelectorAll('[data-modal]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const modalId = btn.getAttribute('data-modal');
                    const modal = document.getElementById(modalId);

                    // HANDLE DOCUMENT PREVIEW
                    if (modalId === 'viewDocumentModal') {
                        const docUrl = btn.getAttribute('data-doc');
                        const user = btn.getAttribute('data-user');
                        const date = btn.getAttribute('data-date');

                        document.getElementById('docUser').innerText = user;
                        document.getElementById('docDate').innerText = date;
                        document.getElementById('docDownload').href = docUrl;

                        const preview = document.getElementById('docPreview');
                        preview.innerHTML = '';

                        if (docUrl.match(/\.(jpg|jpeg|png)$/i)) {
                            preview.innerHTML = `<img src="${docUrl}" class="max-h-[400px] mx-auto rounded">`;
                        } else if (docUrl.match(/\.pdf$/i)) {
                            preview.innerHTML = `
                        <iframe src="${docUrl}" class="w-full h-[400px]" frameborder="0"></iframe>
                    `;
                        } else {
                            preview.innerHTML = `
                        <p class="text-gray-500">
                            Preview not available.
                        </p>
                    `;
                        }
                    }

                    modal.classList.remove('hidden');
                });
            });

            // Close modal
            document.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    const modal = btn.closest('.modal');
                    modal.classList.add('hidden');
                });
            });
        </script>


</x-app-layout>