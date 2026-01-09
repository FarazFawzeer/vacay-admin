@extends('layouts.vertical', ['subtitle' => 'View Blog Post'])

@section('content')
@include('layouts.partials.page-title', [
    'title' => 'Blog Posts',
    'subtitle' => 'View'
])

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Blog Details</h5>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-sm btn-secondary">
            Back
        </a>
    </div>

    <div class="card-body">
        <div class="row">

            {{-- Title --}}
            <div class="col-md-12 mb-3">
                <h4>{{ $blog->title }}</h4>
                <span class="badge bg-info">{{ $blog->type }}</span>
            </div>

            {{-- Posted Time --}}
            <div class="col-md-6 mb-3">
                <strong>Posted On:</strong><br>
                {{ \Carbon\Carbon::parse($blog->posted_time)->format('d M Y, h:i A') }}
            </div>

            {{-- Status --}}
            <div class="col-md-6 mb-3">
                <strong>Status:</strong><br>
                @if ($blog->status)
                    <span class="badge bg-success">Published</span>
                @else
                    <span class="badge bg-warning">Unpublished</span>
                @endif
            </div>

            {{-- Description --}}
            <div class="col-md-12 mb-4">
                <strong>Content:</strong>
                <div class="border rounded p-3 mt-2">
                    {!! nl2br(e($blog->description)) !!}
                </div>
            </div>

            {{-- Hashtags --}}
            <div class="col-md-12 mb-4">
                <strong>Hashtags:</strong><br>
                @if (!empty($blog->hashtags))
                    @foreach ($blog->hashtags as $tag)
                        <span class="badge bg-light text-dark me-1">{{ $tag }}</span>
                    @endforeach
                @else
                    <span class="text-muted">No hashtags</span>
                @endif
            </div>

            {{-- Images --}}
            <div class="col-md-12">
                <strong>Images:</strong>
                <div class="row mt-2">
                    @if (!empty($blog->image_post) && is_array($blog->image_post))
                        @foreach ($blog->image_post as $image)
                            <div class="col-md-3 mb-3">
                                <img src="{{ asset('admin/storage/' . $image) }}"
                                     class="img-fluid rounded shadow-sm"
                                     alt="Blog Image">
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No images uploaded</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
