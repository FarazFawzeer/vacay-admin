@extends('layouts.vertical', ['subtitle' => 'Create Note'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Notes',
        'subtitle' => 'Create',
    ])

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.notes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Audience --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Audience *</label>
                        <select name="audience" class="form-select" required>
                            <option value="global" {{ old('audience') == 'global' ? 'selected' : '' }}>
                                General (All Users)
                            </option>
                            <option value="me" {{ old('audience') == 'me' ? 'selected' : '' }}>
                                Only Me
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                </div>




                <div class="mb-3">
                    <label class="form-label">Note *</label>
                    <textarea name="note" rows="4" class="form-control" required>{{ old('note') }}</textarea>
                </div>

                {{-- 🔹 Attachments --}}
                <div class="mb-3">
                    <label class="form-label">Attachments</label>
                    <input type="file" name="attachments[]" class="form-control" multiple
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <small class="text-muted">
                        You can upload multiple files
                    </small>
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.notes.index') }}" class="btn btn-secondary" style="width: 120px;">Cancel</a>
                    <button type="submit" class="btn btn-primary" style="width: 120px;">
                        Save
                    </button>
                </div>

            </form>


        </div>
    </div>
@endsection
