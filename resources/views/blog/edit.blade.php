@extends('layouts.vertical', ['subtitle' => 'Edit Blog Post'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Blog Posts', 'subtitle' => 'Edit'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Blog Post</h5>
        </div>

        <div class="card-body">
            {{-- Success / Error Alerts --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Blog Info --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Blog Title</label>
                        <input type="text" name="title" id="title" class="form-control"
                            value="{{ old('title', $blog->title) }}" placeholder="Enter blog title" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="posted_time" class="form-label">Posted Time</label>
                        <input type="datetime-local" name="posted_time" id="posted_time" class="form-control"
                            value="{{ old('posted_time', $blog->posted_time ? \Carbon\Carbon::parse($blog->posted_time)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="type" class="form-label">Blog Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="" disabled>Select Type</option>
                            @foreach (['Tour', 'Airline Tickets', 'Vehicle Rental', 'Transportation', 'Visa Assistance', 'Sponsored'] as $type)
                                <option value="{{ $type }}"
                                    {{ old('type', $blog->type) == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Content / Description</label>
                    <textarea name="description" id="description" class="form-control" rows="5"
                        placeholder="Write your blog content here...">{{ old('description', $blog->description) }}</textarea>
                </div>

                {{-- Existing Images --}}
                {{-- Existing Images --}}
                @php
                    $images = is_array($blog->image_post)
                        ? $blog->image_post
                        : json_decode($blog->image_post, true) ?? [];
                @endphp
                @if (!empty($images))
                    <div class="mb-3">
                        <label class="form-label d-block">Existing Images</label>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach ($images as $index => $img)
                                <div class="position-relative border rounded p-1" style="width: 120px;">
                                    <img src="{{ asset('admin/storage/' . $img) }}" alt="Blog Image" class="img-thumbnail"
                                        style="width: 100%; height: 100px; object-fit: cover;">
                                    <button type="button"
                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-image"
                                        data-index="{{ $index }}" title="Remove Image">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    {{-- Hidden input to keep track of each image --}}
                                    <input type="hidden" name="existing_images[]" value="{{ $img }}">
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="remove_images" id="remove_images" value="">
                    </div>
                @endif

                {{-- Upload New Images --}}
                <div class="mb-3">
                    <label for="image_post" class="form-label">Add More Images</label>
                    <input type="file" name="image_post[]" id="image_post" class="form-control" multiple>
                    <small class="text-muted">You can select multiple images (existing ones will remain unless
                        removed)</small>
                </div>


                {{-- Hashtags --}}
                @php
                    $hashtags = is_array($blog->hashtags) ? $blog->hashtags : json_decode($blog->hashtags, true) ?? [];
                @endphp
                <div class="mb-3">
                    <label for="hashtags" class="form-label">Hashtags</label>
                   <input type="text" name="hashtags" id="hashtags" class="form-control"
       value="{{ old('hashtags', implode(',', $hashtags)) }}"
       placeholder="e.g. travel, adventure, blog">

                    <small class="text-muted">Separate hashtags with commas</small>
                </div>

                {{-- Likes Count --}}
                {{-- <div class="mb-3 col-md-3">
                    <label for="likes_count" class="form-label">Likes Count</label>
                    <input type="number" name="likes_count" id="likes_count" class="form-control"
                        value="{{ old('likes_count', $blog->likes_count ?? 0) }}" min="0">
                </div> --}}

                {{-- Submit --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update Blog Post</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script Section --}}
    <script>
        // Automatically fade alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.classList.remove('show');
                alert.classList.add('hide');
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);

          document.querySelectorAll('.remove-image').forEach(btn => {
        btn.addEventListener('click', function () {
            const index = this.dataset.index;
            const wrapper = this.closest('.position-relative');
            wrapper.remove();

            // Add index to hidden remove_images input
            const removed = document.getElementById('remove_images');
            let current = removed.value ? JSON.parse(removed.value) : [];
            current.push(index);
            removed.value = JSON.stringify(current);
        });
    });
    </script>
@endsection
