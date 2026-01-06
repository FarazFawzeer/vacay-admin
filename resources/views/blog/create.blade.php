@extends('layouts.vertical', ['subtitle' => 'Create Blog Post'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Blog Posts', 'subtitle' => 'Create'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">New Blog Post</h5>
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

            <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Blog Info --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Blog Title</label>
                        <input type="text" name="title" id="title" class="form-control"
                            placeholder="Enter blog title" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="posted_time" class="form-label">Posted Time</label>
                        <input type="datetime-local" name="posted_time" id="posted_time" class="form-control"
                            value="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="type" class="form-label">Blog Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="" selected disabled>Select Type</option>
                            <option value="Tour">Tour</option>
                            <option value="Airline Tickets">Airline Tickets</option>
                            <option value="Vehicle Rental">Vehicle Rental</option>
                            <option value="Transportation">Transportation</option>
                            <option value="Visa Assistance">Visa Assistance</option>
                            <option value="Sponsored">Sponsored</option>
                        </select>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Content / Description</label>
                    <textarea name="description" id="description" class="form-control" rows="5"
                        placeholder="Write your blog content here..."></textarea>
                </div>

                {{-- Image Upload --}}
                <div class="mb-3">
                    <label for="image_post" class="form-label">Blog Images</label>
                    <input type="file" name="image_post[]" id="image_post" class="form-control" multiple>
                    <small class="text-muted">You can select multiple images</small>
                </div>

                {{-- Hashtags --}}
                <div class="mb-3">
                    <label for="hashtags" class="form-label">Hashtags</label>
                    <input type="text" name="hashtags[]" id="hashtags" class="form-control"
                        placeholder="e.g. travel, adventure, blog">
                    <small class="text-muted">Separate hashtags with commas or use add button below</small>
                </div>

                {{-- Likes Count --}}
                {{-- <div class="mb-3 col-md-3">
                    <label for="likes_count" class="form-label">Initial Likes</label>
                    <input type="number" name="likes_count" id="likes_count" class="form-control" value="0" min="0">
                </div> --}}

                {{-- Submit --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Publish Blog Post</button>
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
    </script>
@endsection
