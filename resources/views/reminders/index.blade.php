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
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Due Date</th>
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

                        <td>
                            {{ $reminder->due_date->format('d M Y') }}<br>
                            <small class="text-muted">
                                {{ $reminder->due_date->format('h:i A') }}
                            </small>
                        </td>

                        <td>
                            @if($reminder->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($reminder->due_date < now())
                                <span class="badge bg-danger">Overdue</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>

                        <td class="text-center">

                            {{-- Edit --}}
                            <a href="{{ route('admin.reminders.edit', $reminder) }}"
                               class="icon-btn text-primary" title="Edit">
                                <i class="bi bi-pencil-square fs-5"></i>
                            </a>

                            {{-- Complete --}}
                            @if($reminder->status !== 'completed')
                                <form action="{{ route('admin.reminders.complete', $reminder) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button class="icon-btn text-success" title="Mark Complete">
                                        <i class="bi bi-check-circle-fill fs-5"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Delete --}}
                            <form action="{{ route('admin.reminders.destroy', $reminder) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this reminder?')">
                                @csrf
                                @method('DELETE')
                                <button class="icon-btn text-danger" title="Delete">
                                    <i class="bi bi-trash-fill fs-5"></i>
                                </button>
                            </form>

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

@endsection
