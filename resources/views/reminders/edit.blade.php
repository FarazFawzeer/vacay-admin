@extends('layouts.vertical', ['subtitle' => 'Edit Reminder'])

@section('content')

@include('layouts.partials.page-title', [
    'title' => 'Reminders',
    'subtitle' => 'Edit',
])

@php
    $isSuper = auth()->user()->type === 'Super Admin';
    $meId = auth()->id();

    // Determine current audience based on DB values
    $currentAudience = 'me';
    if ($reminder->is_global) {
        $currentAudience = 'global';
    } elseif (!$reminder->is_global && $reminder->user_id && $reminder->user_id != $meId) {
        $currentAudience = 'user';
    }
@endphp

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.reminders.update', $reminder) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Audience --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Audience *</label>

                    @if($isSuper)
                        <select name="audience" id="audience" class="form-select" required>
                            <option value="me" {{ old('audience', $currentAudience) == 'me' ? 'selected' : '' }}>
                                Only Me
                            </option>
                            <option value="global" {{ old('audience', $currentAudience) == 'global' ? 'selected' : '' }}>
                                General (All Users)
                            </option>
                            <option value="user" {{ old('audience', $currentAudience) == 'user' ? 'selected' : '' }}>
                                Specific User
                            </option>
                        </select>
                    @else
                        {{-- Normal admin cannot change audience --}}
                        <input type="text" class="form-control" readonly value="Only Me">
                        <input type="hidden" name="audience" value="me">
                    @endif
                </div>

                {{-- User select only when Specific User (Super Admin only) --}}
                @if($isSuper)
                    <div class="col-md-6 mb-3 {{ old('audience', $currentAudience) == 'user' ? '' : 'd-none' }}" id="userSelectWrap">
                        <label class="form-label">Select User *</label>
                        <select name="user_id" class="form-select">
                            <option value="">-- Select User --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('user_id', $reminder->user_id) == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }} (ID: {{ $u->id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

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
                <input type="datetime-local" name="due_date" class="form-control"
                       value="{{ $reminder->due_date->format('Y-m-d\TH:i') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending" {{ $reminder->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $reminder->status == 'completed' ? 'selected' : '' }}>Completed</option>
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
                    <small class="text-muted">Check files you want to remove</small>
                </div>
            @endif

            {{-- Add New Attachments --}}
            <div class="mb-3">
                <label class="form-label">Add New Attachments</label>
                <input type="file" name="attachments[]" class="form-control" multiple
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
            </div>

            <div class="text-end">
                <a href="{{ route('admin.reminders.index') }}" class="btn btn-secondary" style="width: 120px;">Cancel</a>
                <button type="submit" class="btn btn-primary" style="width: 120px;">Update</button>
            </div>
        </form>

    </div>
</div>

@if($isSuper)
<script>
    const audience = document.getElementById('audience');
    const userWrap = document.getElementById('userSelectWrap');

    function toggleUserSelect() {
        const isUser = audience.value === 'user';
        userWrap.classList.toggle('d-none', !isUser);

        const userSelect = userWrap.querySelector('select[name="user_id"]');
        if (userSelect) userSelect.required = isUser;
    }

    audience.addEventListener('change', toggleUserSelect);
    toggleUserSelect();
</script>
@endif

@endsection
