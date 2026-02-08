@extends('layouts.vertical', ['subtitle' => 'Edit Note'])

@section('content')

    @include('layouts.partials.page-title', [
        'title' => 'Notes',
        'subtitle' => 'Edit',
    ])

    @php
        $isSuper = auth()->user()->type === 'Super Admin';
        $meId = auth()->id();

        $currentAudience = 'me';
        if ($note->is_global) {
            $currentAudience = 'global';
        } elseif (!$note->is_global && $note->user_id && $note->user_id != $meId) {
            $currentAudience = 'user';
        }
    @endphp

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.notes.update', $note) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Audience --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Audience *</label>

                        @if ($isSuper)
                            <select name="audience" id="audience" class="form-select" required>
                                <option value="me" {{ old('audience', $currentAudience) == 'me' ? 'selected' : '' }}>
                                    Only Me
                                </option>
                                <option value="global"
                                    {{ old('audience', $currentAudience) == 'global' ? 'selected' : '' }}>
                                    General (All Users)
                                </option>
                                <option value="user" {{ old('audience', $currentAudience) == 'user' ? 'selected' : '' }}>
                                    Specific User
                                </option>
                            </select>
                        @else
                            <input type="text" class="form-control" readonly value="Only Me">
                            <input type="hidden" name="audience" value="me">
                        @endif
                    </div>

                    @if ($isSuper)
                        <div class="col-md-6 mb-3 {{ old('audience', $currentAudience) == 'user' ? '' : 'd-none' }}"
                            id="userSelectWrap">
                            <label class="form-label">Select User *</label>
                            <select name="user_id" class="form-select">
                                <option value="">-- Select User --</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}"
                                        {{ old('user_id', $note->user_id) == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }} (ID: {{ $u->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $note->title) }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Note *</label>
                    <textarea name="note" rows="4" class="form-control" required>{{ old('note', $note->note) }}</textarea>
                </div>

                {{-- Existing Attachments --}}
                @if (!empty($note->attachments))
                    <div class="mb-3">
                        <label class="form-label">Existing Attachments</label>
                        <div class="border rounded p-2">
                            @foreach ($note->attachments as $file)
                                <div class="form-check d-flex align-items-center mb-2">
                                    <input type="checkbox" name="remove_attachments[]" value="{{ $file }}"
                                        class="form-check-input me-2">
                                    <a href="{{ asset('storage/' . $file) }}" target="_blank">
                                        {{ basename($file) }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted">Select files to remove</small>
                    </div>
                @endif

                {{-- Add New Attachments --}}
                <div class="mb-3">
                    <label class="form-label">Add New Attachments</label>
                    <input type="file" name="attachments[]" class="form-control" multiple
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.notes.index') }}" class="btn btn-secondary" style="width: 120px;">Cancel</a>
                    <button type="submit" class="btn btn-primary" style="width: 120px;">Update</button>
                </div>

            </form>

        </div>
    </div>

    @if ($isSuper)
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
