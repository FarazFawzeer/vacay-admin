@extends('layouts.vertical', ['subtitle' => 'Reminder Details'])

@section('content')

@include('layouts.partials.page-title', [
    'title' => 'Reminders',
    'subtitle' => 'View Details'
])

<style>
    .detail-label {
        font-weight: 600;
        color: #6c757d;
    }

    .detail-value {
        font-size: 1rem;
    }

    .attachment-thumb {
        height: 130px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .attachment-thumb:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .attachment-name {
        font-size: 0.8rem;
        margin-top: 6px;
        word-break: break-all;
    }

    .section-divider {
        border-top: 1px dashed #dee2e6;
        margin: 20px 0;
    }
</style>

<div class="card shadow-sm">

    {{-- Card Header --}}
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
      Reminder Details
        </h5>

        <span>
            @if ($reminder->status === 'completed')
                <span class="badge bg-success">Completed</span>
            @elseif($reminder->due_date < now())
                <span class="badge bg-danger">Overdue</span>
            @else
                <span class="badge bg-warning">Pending</span>
            @endif
        </span>
    </div>

    <div class="card-body">

        {{-- Title --}}
        <h4 class="mb-2">{{ $reminder->title }}</h4>

        {{-- Description --}}
        <p class="text-muted mb-3">
            {{ $reminder->description ?? 'No description provided.' }}
        </p>

        <div class="section-divider"></div>

        {{-- Details --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="detail-label">Due Date</div>
                <div class="detail-value">
                    {{ $reminder->due_date->format('d M Y') }} <br>
                    <small class="text-muted">{{ $reminder->due_date->format('h:i A') }}</small>
                </div>
            </div>
        </div>

        <div class="section-divider"></div>

        {{-- Attachments --}}
        <h6 class="mb-3">
            <i class="bi bi-paperclip me-1"></i> Attachments
        </h6>

        @if (!empty($reminder->attachments))
            <div class="row">

                @foreach ($reminder->attachments as $file)
                    @php
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        $url = asset('admin/storage/' . $file);
                    @endphp

                    {{-- Image Attachments --}}
                    @if (in_array($ext, ['jpg','jpeg','png']))
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 text-center">
                            <img src="{{ $url }}"
                                 class="img-thumbnail attachment-thumb"
                                 data-bs-toggle="modal"
                                 data-bs-target="#imagePreviewModal"
                                 onclick="previewImage('{{ $url }}')">

                            <div class="attachment-name text-muted">
                                {{ Str::limit(basename($file), 18) }}
                            </div>
                        </div>

                    {{-- Other Files --}}
                    @else
                        <div class="col-12 mb-2">
                            <a href="{{ $url }}" target="_blank" class="text-decoration-none d-flex align-items-center gap-2">
                                @if ($ext === 'pdf')
                                    <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                                @elseif (in_array($ext, ['doc','docx']))
                                    <i class="bi bi-file-earmark-word text-primary fs-4"></i>
                                @else
                                    <i class="bi bi-paperclip fs-4"></i>
                                @endif
                                <span>{{ basename($file) }}</span>
                            </a>
                        </div>
                    @endif
                @endforeach

            </div>
        @else
            <p class="text-muted">No attachments available.</p>
        @endif

        <div class="section-divider"></div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.reminders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>

       
        </div>

    </div>
</div>

{{-- Image Preview Modal --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" class="img-fluid rounded" style="max-height:80vh;">
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(src) {
        document.getElementById('previewImage').src = src;
    }
</script>

@endsection
