@extends('layouts.vertical', ['subtitle' => 'Edit Note'])

@section('content')

@include('layouts.partials.page-title', [
    'title' => 'Notes',
    'subtitle' => 'Edit'
])

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.notes.update', $note) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title', $note->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Note *</label>
                <textarea name="note" rows="4"
                          class="form-control" required>{{ old('note', $note->note) }}</textarea>
            </div>

            <div class="text-end">
                <a href="{{ route('admin.notes.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    Update Note
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
