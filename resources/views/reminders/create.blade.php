@extends('layouts.vertical', ['subtitle' => 'Create Testimonial'])

@section('content')

@include('layouts.partials.page-title', [
    'title' => 'Reminders',
    'subtitle' => 'Create'
])

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.reminders.store') }}" method="POST">
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


            <div class="text-end">
                <a href="{{ route('admin.reminders.index') }}"
                   class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    Save Reminder
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
