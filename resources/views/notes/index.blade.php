@extends('layouts.vertical', ['subtitle' => 'Notes'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Notes',
        'subtitle' => 'List',
    ])

    <style>
        .btn-equal {
            width: 80px;
            text-align: center;
        }

        .icon-btn {
            background: none;
            border: none;
            padding: 4px;
            margin: 0 2px;
            cursor: pointer;
            transition: transform 0.2s, color 0.2s;
        }

        .icon-btn:hover {
            transform: scale(1.2);
            opacity: 0.8;
            text-decoration: none;
        }
    </style>

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">Note List</h5>
                <p class="card-subtitle mb-0">All notes in your system.</p>
            </div>
            <div>
                <a href="{{ route('admin.notes.create') }}" class="btn btn-primary">
                    Add Note
                </a>
            </div>
        </div>

        <div class="card-body">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- 🔍 Auto Search --}}
            <form id="filterForm" method="GET" action="{{ route('admin.notes.index') }}"
                class="row g-2 mb-3 justify-content-end">

                <div class="col-md-4">
                    <input type="text" name="search" id="searchInput" class="form-control"
                        placeholder="Search by title or note" value="{{ request('search') }}">
                </div>

            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
    <tr>
        <th>Title</th>
        <th>Note</th>
        <th>Audience</th>
        <th>Attachments</th>
        <th>Created At</th>
        <th class="text-center">Action</th>
    </tr>
</thead>

<tbody>
@forelse($notes as $note)
    @php
        $canManage = ($note->is_global && $note->created_by == auth()->id())
            || (!$note->is_global && $note->user_id == auth()->id());
    @endphp

    <tr>
        <td>
            <strong>{{ $note->title }}</strong>
        </td>

        <td>
            {{ Str::limit($note->note, 50) ?? '-' }}
        </td>

        {{-- Audience --}}
        <td>
            @if($note->is_global)
                <span class="badge bg-info">General</span>
            @else
                <span class="badge bg-secondary">Only Me</span>
            @endif
        </td>

        {{-- 📎 Attachments --}}
        <td>
            @if (!empty($note->attachments))
                <div class="d-flex flex-wrap gap-1">
                    @foreach ($note->attachments as $file)
                        @php $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION)); @endphp

                        <a href="{{ asset('storage/' . $file) }}" target="_blank"
                           class="badge bg-light text-dark border" title="{{ basename($file) }}">

                            @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                <i class="bi bi-image"></i>
                            @elseif ($ext === 'pdf')
                                <i class="bi bi-file-earmark-pdf text-danger"></i>
                            @elseif (in_array($ext, ['doc', 'docx']))
                                <i class="bi bi-file-earmark-word text-primary"></i>
                            @else
                                <i class="bi bi-paperclip"></i>
                            @endif

                            {{ Str::limit(basename($file), 12) }}
                        </a>
                    @endforeach
                </div>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>

        <td>
            {{ $note->created_at->format('d M Y') }}<br>
            <small class="text-muted">{{ $note->created_at->format('h:i A') }}</small>
        </td>

        <td class="text-center">
            {{-- View --}}
            <a href="{{ route('admin.notes.show', $note) }}" class="icon-btn text-info" title="View">
                <i class="bi bi-eye-fill fs-5"></i>
            </a>

            {{-- Edit/Delete only if allowed --}}
            @if($canManage)

                <a href="{{ route('admin.notes.edit', $note) }}" class="icon-btn text-primary" title="Edit">
                    <i class="bi bi-pencil-square fs-5"></i>
                </a>

                <form action="{{ route('admin.notes.destroy', $note) }}" method="POST"
                      class="d-inline" onsubmit="return confirm('Delete this note?')">
                    @csrf
                    @method('DELETE')
                    <button class="icon-btn text-danger" title="Delete">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>
                </form>

            @else
                <span class="text-muted">—</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-muted">
            No notes found.
        </td>
    </tr>
@endforelse
</tbody>

                </table>
            </div>

        </div>
    </div>

    {{-- 🔁 Auto Submit Script --}}
    <script>
        const searchInput = document.getElementById('searchInput');
        const filterForm = document.getElementById('filterForm');

        let typingTimer;
        const delay = 400;

        searchInput.addEventListener('keyup', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                filterForm.submit();
            }, delay);
        });
    </script>

@endsection
