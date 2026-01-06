<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Documents</h2>
                <button data-modal="uploadDocumentModal" class="btn btn-primary">
                    Upload Document
                </button>
            </div>

            <div class="mb-4 flex gap-2 flex-wrap">
                <button class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs" data-filter="all"
                    data-type="event">
                    All Events
                </button>
                @foreach($userEvents as $event)
                    <button class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs"
                        data-filter="{{ $event->event_id }}" data-type="event">
                        {{ $event->event_name }}
                    </button>
                @endforeach
            </div>

            <div class="mb-4 flex gap-2 flex-wrap">
                <button class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs" data-filter="all"
                    data-type="doctype">
                    All Types
                </button>
                @foreach(['PDF', 'Word', 'Excel', 'Image', 'Power Point', 'Other'] as $type)
                    <button class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs"
                        data-filter="{{ $type }}" data-type="doctype">
                        {{ $type }}
                    </button>
                @endforeach
            </div>

            <div class="mb-4 flex gap-2 flex-wrap">
                <button class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs" data-filter="all"
                    data-type="category">
                    All Categories
                </button>
                @foreach(['Budget', 'Talent', 'Venue', 'Vendor', 'Sponsor', 'Item'] as $category)
                    <button class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs"
                        data-filter="{{ $category }}" data-type="category">
                        {{ $category }}
                    </button>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse($documents as $doc)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4 document-card"
                        data-event="{{ $doc->event_id }}" data-doctype="{{ $doc->document_type }}"
                        data-category="{{ $doc->entity_type ?? 'Uncategorized' }}">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center justify-between text-xs text-gray-400">
                                
                            </div>
                            <div class="flex text-xs text-gray-400">
                                
                            </div>
                            <div class="flex gap-1">
                                @if($doc->uploaded_by == Auth::id())
                                    <form method="POST" action="{{ route('document.destroy', $doc->document_id) }}"
                                        onsubmit="return confirm('Delete this document?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger btn-danger:hover text-xs">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <h3 class="font-semibold text-sm text-gray-900 mb-1 truncate" title="{{ $doc->document_name }}">
                            {{ $doc->document_name }}
                        </h3>
                        <p class="text-xs text-gray-500 mb-2">
                            {{ $doc->event->event_name }}
                        </p>
                        <div class="mt-2 text-xs text-gray-400">
                            <span>{{ $doc->uploader->full_name }}</span>
                            <span class="mx-1">•</span>
                            <span>{{ $doc->created_at->diffForHumans() }}</span>
                            <p>{{ $doc->document_type }} - {{ $doc->getFileSize() }}</p>
                            <a href="{{ route('document.download', $doc->document_id) }}"
                                    class="text-blue-600 hover:text-blue-800 text-l">
                                    Download
                            </a>
                        </div>
                        <div class="flex gap-1 mt-2 flex-wrap">
                            @if($doc->division)
                                <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                    {{ $doc->division->divisionType->type_name ?? 'Division' }}
                                </span>
                            @endif
                            @if($doc->entity_type)
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                    {{ $doc->entity_type }}
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        <p>No documents yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div id="uploadDocumentModal" class="modal hidden">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Upload Document</h3>
            <form method="POST" action="{{ route('document.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Event *</label>
                    <select name="event_id" id="eventSelect" required>
                        <option value="">-- Select Event --</option>
                        @foreach($userEvents as $event)
                            <option value="{{ $event->event_id }}">{{ $event->event_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Document Name *</label>
                    <input type="text" name="document_name" placeholder="Enter document name" required>
                </div>

                <div class="form-group">
                    <label>File * (Max: 10MB)</label>
                    <input type="file" name="file" required
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif">
                    <small class="text-gray-500 text-xs">Supported: PDF, Word, Excel, Power Point, Gambar</small>
                </div>

                <div class="form-group">
                    <label>Division (Optional)</label>
                    <select name="division_id" id="divisionSelect" disabled>
                        <option value="">-- Select Event First --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Category (Optional)</label>
                    <select name="entity_type">
                        <option value="None">-- Not Related --</option>
                        <option value="Budget">Budget</option>
                        <option value="Sponsor">Sponsor</option>
                        <option value="Venue">Venue</option>
                        <option value="Vendor">Vendor</option>
                        <option value="Talent">Talent</option>
                        <option value="Item">Item</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const divisionsByEvent = @json($divisions->groupBy('event_id'));

        document.querySelectorAll('[data-modal]').forEach(btn => {
            btn.addEventListener('click', () => {
                const modalId = btn.getAttribute('data-modal');
                document.getElementById(modalId).classList.remove('hidden');
            });
        });

        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const modal = btn.closest('.modal');
                modal.classList.add('hidden');
                const form = modal.querySelector('form');
                if (form) form.reset();
            });
        });

        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function (e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                    const form = this.querySelector('form');
                    if (form) form.reset();
                }
            });
        });

        document.getElementById('eventSelect').addEventListener('change', function () {
            const eventId = this.value;
            const divisionSelect = document.getElementById('divisionSelect');

            divisionSelect.innerHTML = '<option value="">-- Select Division --</option>';

            if (eventId && divisionsByEvent[eventId]) {
                divisionSelect.disabled = false;
                divisionsByEvent[eventId].forEach(division => {
                    const option = document.createElement('option');
                    option.value = division.division_id;
                    option.textContent = division.division_type?.type_name || division.division_name;
                    divisionSelect.appendChild(option);
                });
            } else {
                divisionSelect.disabled = true;
            }
        });

        let currentEventFilter = 'all';
        let currentTypeFilter = 'all';
        let currentCategoryFilter = 'all';

        document.querySelectorAll('[data-filter]').forEach(btn => {
            btn.addEventListener('click', function () {
                const filter = this.getAttribute('data-filter');
                const type = this.getAttribute('data-type');

                if (type === 'event') {
                    currentEventFilter = filter;
                    document.querySelectorAll('[data-type="event"]').forEach(b => {
                        b.classList.remove('bg-blue-600', 'text-white');
                        b.classList.add('bg-blue-200', 'text-blue-700');
                    });
                    this.classList.remove('bg-blue-200', 'text-blue-700');
                    this.classList.add('bg-blue-600', 'text-white');
                } else if (type === 'doctype') {
                    currentTypeFilter = filter;
                    document.querySelectorAll('[data-type="doctype"]').forEach(b => {
                        b.classList.remove('bg-blue-600', 'text-white');
                        b.classList.add('bg-blue-100', 'text-blue-700');
                    });
                    this.classList.remove('bg-blue-100', 'text-blue-700');
                    this.classList.add('bg-blue-600', 'text-white');
                } else if (type === 'category') {
                    currentCategoryFilter = filter;
                    document.querySelectorAll('[data-type="category"]').forEach(b => {
                        b.classList.remove('bg-blue-600', 'text-white');
                        b.classList.add('bg-blue-100', 'text-blue-700');
                    });
                    this.classList.remove('bg-blue-100', 'text-blue-700');
                    this.classList.add('bg-blue-600', 'text-white');
                }

                document.querySelectorAll('.document-card').forEach(card => {
                    const eventMatch = currentEventFilter === 'all' || card.getAttribute('data-event') === currentEventFilter;
                    const typeMatch = currentTypeFilter === 'all' || card.getAttribute('data-doctype') === currentTypeFilter;
                    const categoryMatch = currentCategoryFilter === 'all' || card.getAttribute('data-category') === currentCategoryFilter;

                    if (eventMatch && typeMatch && categoryMatch) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-app-layout>