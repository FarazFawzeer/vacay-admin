@extends('layouts.vertical', ['subtitle' => 'Reminders'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Reminders', 'subtitle' => 'View'])

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
                <h5 class="card-title mb-0">Reminder List</h5>
                <p class="card-subtitle mb-0">All reminders in your system.</p>
            </div>
            <div>
                <a href="{{ route('admin.reminders.create') }}" class="btn btn-primary">
                    Add Reminder
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
            {{-- 🔍 Auto Filters --}}
            <form id="filterForm" method="GET" action="{{ route('admin.reminders.index') }}"
                class="row g-2 mb-3 justify-content-end">

                <div class="col-md-4">
                    <input type="text" name="search" id="searchInput" class="form-control"
                        placeholder="Search by title or description" value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <select name="status" id="statusSelect" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>
                            Overdue
                        </option>
                    </select>
                </div>

            </form>


            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Audience</th>
                            <th>Due Date</th>
                            <th>Attachments</th>
                            <th>Status</th>

                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($reminders as $reminder)
                            <tr>

                                <td>
                                    <strong>{{ $reminder->title }}</strong>
                                </td>

                                <td>
                                    {{ $reminder->description ?? '-' }}
                                </td>

                                @php
                                    $isSuper = auth()->user()->type === 'Super Admin';

                                    $canManage =
                                        ($reminder->is_global && $reminder->created_by == auth()->id()) ||
                                        (!$reminder->is_global && $reminder->user_id == auth()->id()) ||
                                        ($isSuper && !$reminder->is_global && $reminder->created_by == auth()->id()); // ✅ super admin managing assigned reminders
                                @endphp
                                <td>
                                    @if ($reminder->is_global)
                                        <span class="badge bg-info">
                                            General
                                        </span>
                                    @elseif ($reminder->user_id == auth()->id())
                                        <span class="badge bg-secondary">
                                            Only Me
                                        </span>
                                    @else
                                        <span class="badge bg-dark">
                                            Specific User
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            {{ $reminder->user->name ?? 'Unknown User' }}
                                            (ID: {{ $reminder->user_id }})
                                        </small>
                                    @endif
                                </td>



                                <td>
                                    {{ $reminder->due_date->format('d M Y') }}<br>
                                    <small class="text-muted">
                                        {{ $reminder->due_date->format('h:i A') }}
                                    </small>
                                </td>

                                <td>
                                    @if (!empty($reminder->attachments))
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($reminder->attachments as $file)
                                                @php
                                                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                                @endphp

                                                <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                                    class="badge bg-light text-dark border" title="{{ basename($file) }}">

                                                    {{-- Icons --}}
                                                    @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                                        <i class="bi bi-image"></i>
                                                    @elseif($ext === 'pdf')
                                                        <i class="bi bi-file-earmark-pdf text-danger"></i>
                                                    @elseif(in_array($ext, ['doc', 'docx']))
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
                                    @if ($reminder->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($reminder->due_date < now())
                                        <span class="badge bg-danger">Overdue</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="text-center">

                                    @php
                                        $isOwner = $reminder->user_id === auth()->id();
                                        $isCreator = $reminder->created_by === auth()->id();
                                        $isGlobalOwn = $reminder->is_global && $isCreator;

                                        $canManage = $isOwner || $isCreator || $isGlobalOwn;
                                    @endphp

                                    @if ($canManage)
                                        {{-- View --}}
                                        <a href="{{ route('admin.reminders.show', $reminder) }}" class="icon-btn text-info"
                                            title="View">
                                            <i class="bi bi-eye-fill fs-5"></i>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.reminders.edit', $reminder) }}"
                                            class="icon-btn text-primary" title="Edit">
                                            <i class="bi bi-pencil-square fs-5"></i>
                                        </a>

                                        {{-- Complete --}}
                                        @if ($reminder->status !== 'completed')
                                            <form action="{{ route('admin.reminders.complete', $reminder) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button class="icon-btn text-success" title="Mark Complete">
                                                    <i class="bi bi-check-circle-fill fs-5"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.reminders.destroy', $reminder) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this reminder?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="icon-btn text-danger" title="Delete">
                                                <i class="bi bi-trash-fill fs-5"></i>
                                            </button>
                                        </form>
                                    @endif

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    No reminders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const statusSelect = document.getElementById('statusSelect');
        const filterForm = document.getElementById('filterForm');

        let typingTimer;
        const delay = 400; // ms delay while typing

        // Auto submit when typing (with debounce)
        searchInput.addEventListener('keyup', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                filterForm.submit();
            }, delay);
        });

        // Auto submit when status changes
        statusSelect.addEventListener('change', () => {
            filterForm.submit();
        });
    </script>


@endsection
