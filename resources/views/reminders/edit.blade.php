@extends('layouts.vertical', ['subtitle' => 'Edit Reminder'])

@section('content')

@include('layouts.partials.page-title', [
    'title' => 'Reminders',
    'subtitle' => 'Edit',
])

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.reminders.update', $reminder) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Audience (readonly) --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Audience</label>
                    <input type="text" class="form-control" readonly
                           value="{{ $reminder->is_global ? 'General (All Users)' : 'Only Me' }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title', $reminder->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description', $reminder->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Due Date & Time *</label>
                <input type="datetime-local" name="due_date" class="form-control"
                       value="{{ $reminder->due_date->format('Y-m-d\TH:i') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending" {{ $reminder->status == 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>
                    <option value="completed" {{ $reminder->status == 'completed' ? 'selected' : '' }}>
                        Completed
                    </option>
                </select>
            </div>

            {{-- Existing Attachments --}}
            @if (!empty($reminder->attachments))
                <div class="mb-3">
                    <label class="form-label">Existing Attachments</label>
                    <div class="border rounded p-2">
                        @foreach ($reminder->attachments as $file)
                            <div class="form-check d-flex align-items-center mb-2">
                                <input type="checkbox" name="remove_attachments[]" value="{{ $file }}"
                                       class="form-check-input me-2">
                                <a href="{{ asset('storage/' . $file) }}" target="_blank">
                                    {{ basename($file) }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <small class="text-muted">
                        Check files you want to remove
                    </small>
                </div>
            @endif

            {{-- Add New Attachments --}}
            <div class="mb-3">
                <label class="form-label">Add New Attachments</label>
                <input type="file" name="attachments[]" class="form-control" multiple
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
            </div>

            <div class="text-end">
                <a href="{{ route('admin.reminders.index') }}" class="btn btn-secondary" style="width: 120px;">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" style="width: 120px;">
                    Update
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
