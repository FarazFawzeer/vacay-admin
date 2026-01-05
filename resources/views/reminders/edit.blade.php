@extends('layouts.vertical', ['subtitle' => 'Create Testimonial'])

@section('content')

@include('layouts.partials.page-title', [
    'title' => 'Reminders',
    'subtitle' => 'Edit'
])

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.reminders.update', $reminder) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title', $reminder->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3"
                          class="form-control">{{ old('description', $reminder->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Due Date & Time *</label>
                <input type="datetime-local" name="due_date"
                       class="form-control"
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

            <div class="text-end">
                <a href="{{ route('admin.reminders.index') }}"
                   class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    Update Reminder
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
