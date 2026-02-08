@extends('layouts.vertical', ['subtitle' => 'Create Reminder'])

@section('content')

@include('layouts.partials.page-title', [
    'title' => 'Reminders',
    'subtitle' => 'Create'
])

<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.reminders.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            {{-- Audience --}}
         <div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Audience *</label>
        <select name="audience" id="audience" class="form-select" required>
            <option value="me" {{ old('audience') == 'me' ? 'selected' : '' }}>
                Only Me
            </option>

            @if(auth()->user()->type === 'Super Admin')
                <option value="global" {{ old('audience') == 'global' ? 'selected' : '' }}>
                    General (All Users)
                </option>
                <option value="user" {{ old('audience') == 'user' ? 'selected' : '' }}>
                    Specific User
                </option>
            @endif
        </select>
    </div>

    @if(auth()->user()->type === 'Super Admin')
        <div class="col-md-6 mb-3 d-none" id="userSelectWrap">
            <label class="form-label">Select User *</label>
            <select name="user_id" class="form-select">
                <option value="">-- Select User --</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif
</div>
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

            {{-- Attachments --}}
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

@if(auth()->user()->type === 'Super Admin')
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
