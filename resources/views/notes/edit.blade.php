@extends('layouts.vertical', ['subtitle' => 'Edit Note'])

@section('content')

@include('layouts.partials.page-title', [
    'title' => 'Notes',
    'subtitle' => 'Edit'
])

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.notes.update', $note) }}"
              method="POST"
              enctype="multipart/form-data">
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

            {{-- 🔹 Existing Attachments --}}
            @if(!empty($note->attachments))
                <div class="mb-3">
                    <label class="form-label">Existing Attachments</label>
                    <div class="border rounded p-2">
                        @foreach($note->attachments as $file)
                            <div class="form-check d-flex align-items-center mb-2">
                                <input type="checkbox"
                                       name="remove_attachments[]"
                                       value="{{ $file }}"
                                       class="form-check-input me-2">

                                <a href="{{ asset('storage/'.$file) }}"
                                   target="_blank">
                                    {{ basename($file) }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <small class="text-muted">
                        Select files to remove
                    </small>
                </div>
            @endif

            {{-- 🔹 Add New Attachments --}}
            <div class="mb-3">
                <label class="form-label">Add New Attachments</label>
                <input type="file"
                       name="attachments[]"
                       class="form-control"
                       multiple
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
            </div>

            <div class="text-end">
                <a href="{{ route('admin.notes.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Update Note
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
