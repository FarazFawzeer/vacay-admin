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


            {{-- Notes Table --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Note</th>
                            <th>Created At</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($notes as $note)
                            <tr>
                                <td>
                                    <strong>{{ $note->title }}</strong>
                                </td>

                                <td>
                                    {{ Str::limit($note->note, 50) ?? '-' }}
                                </td>

                                <td>
                                    {{ $note->created_at->format('d M Y') }}<br>
                                    <small class="text-muted">
                                        {{ $note->created_at->format('h:i A') }}
                                    </small>
                                </td>

                                <td class="text-center">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.notes.edit', $note) }}" class="icon-btn text-primary"
                                        title="Edit">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.notes.destroy', $note) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Delete this note?')">
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
                                <td colspan="4" class="text-center text-muted">
                                    No notes found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


        </div>
    </div>
@endsection
