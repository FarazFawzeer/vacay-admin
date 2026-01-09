@extends('layouts.vertical', ['subtitle' => 'Note Details'])

@section('content')

@include('layouts.partials.page-title', [
    'title' => 'Notes',
    'subtitle' => 'View Details'
])

<style>
    .detail-label {
        font-weight: 600;
        color: #6c757d;
    }

    .attachment-thumb {
        height: 130px;
        object-fit: cover;
        cursor: pointer;
        transition: transform .2s, box-shadow .2s;
    }

    .attachment-thumb:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0,0,0,.15);
    }

    .attachment-name {
        font-size: .8rem;
        margin-top: 5px;
        word-break: break-word;
    }

    .section-divider {
        border-top: 1px dashed #dee2e6;
        margin: 20px 0;
    }
</style>

<div class="card shadow-sm">

    {{-- Header --}}
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="bi bi-journal-text me-1"></i> Note Details
        </h5>
    </div>

    <div class="card-body">

        {{-- Title --}}
        <h4>{{ $note->title }}</h4>

        {{-- Note Content --}}
        <p class="text-muted mt-2">
            {{ $note->note }}
        </p>

        <div class="section-divider"></div>

        {{-- Meta --}}
        <div class="mb-3">
            <div class="detail-label">Created At</div>
            <div>
                {{ $note->created_at->format('d M Y h:i A') }}
            </div>
        </div>

        <div class="section-divider"></div>

        {{-- Attachments --}}
        <h6 class="mb-3">
            <i class="bi bi-paperclip me-1"></i> Attachments
        </h6>

        @if (!empty($note->attachments))
            <div class="row">

                @foreach ($note->attachments as $file)
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
                            <a href="{{ $url }}"
                               target="_blank"
                               class="text-decoration-none d-flex align-items-center gap-2">
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
            <a href="{{ route('admin.notes.index') }}"
               class="btn btn-outline-secondary">
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
