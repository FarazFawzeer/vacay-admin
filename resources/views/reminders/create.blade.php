@extends('layouts.vertical', ['subtitle' => 'Create Testimonial'])

@section('content')

@include('layouts.partials.page-title', [
    'title' => 'Reminders',
    'subtitle' => 'Create'
])

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.reminders.store') }}"
              method="POST"
              enctype="multipart/form-data"> {{-- 👈 REQUIRED --}}
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control"
                           value="{{ old('title') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Due Date & Time *</label>
                    <input type="datetime-local" name="due_date"
                           class="form-control"
                           value="{{ old('due_date') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3"
                          class="form-control">{{ old('description') }}</textarea>
            </div>

            {{-- 🔹 Attachments --}}
            <div class="mb-3">
                <label class="form-label">Attachments</label>
                <input type="file"
                       name="attachments[]"
                       class="form-control"
                       multiple
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <small class="text-muted">
                    You can upload multiple files (PDF, images, documents)
                </small>
            </div>

            <div class="text-end">
                <a href="{{ route('admin.reminders.index') }}"
                   class="btn btn-secondary" style="width: 120px;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="width: 120px;">
                    Save
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
