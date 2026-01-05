@extends('layouts.vertical', ['subtitle' => 'Create Note'])

@section('content')

@include('layouts.partials.page-title', [
    'title' => 'Notes',
    'subtitle' => 'Create'
])

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.notes.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Note *</label>
                <textarea name="note" rows="4"
                          class="form-control" required>{{ old('note') }}</textarea>
            </div>

            <div class="text-end">
                <a href="{{ route('admin.notes.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    Save Note
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
