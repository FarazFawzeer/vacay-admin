@extends('layouts.vertical', ['subtitle' => 'Edit Testimonial'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Testimonials', 'subtitle' => 'Edit'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Testimonial</h5>
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

            <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Testimonial Info --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Customer Name</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name', $testimonial->name) }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="source" class="form-label">Source</label>
                        <select name="source" id="source" class="form-select">
                            <option value="" disabled>Select Source</option>
                            @foreach(['Website', 'Google Review', 'Facebook', 'TripAdvisor', 'Other'] as $source)
                                <option value="{{ $source }}" {{ old('source', $testimonial->source) == $source ? 'selected' : '' }}>
                                    {{ $source }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="postedate" class="form-label">Post Date</label>
                        <input type="date" name="postedate" id="postedate" class="form-control"
                            value="{{ old('postedate', $testimonial->postedate->format('Y-m-d')) }}">
                    </div>
                </div>

                {{-- Rating --}}
                <div class="mb-3 col-md-3">
                    <label for="rating" class="form-label">Rating (1 - 5)</label>
                    <select name="rating" id="rating" class="form-select">
                        <option value="" disabled>Select Rating</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>
                                {{ $i }} ★
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Message --}}
                <div class="mb-3">
                    <label for="message" class="form-label">Testimonial Message</label>
                    <textarea name="message" id="message" class="form-control" rows="4" required>{{ old('message', $testimonial->message) }}</textarea>
                </div>

                {{-- Image Upload --}}
                <div class="mb-3">
                    <label for="image" class="form-label">Customer Image</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    <small class="text-muted">Upload a square image (optional)</small>

                    @if ($testimonial->image)
                        <div class="mt-2">
                            <img src="{{ asset('admin/storage/' . $testimonial->image) }}" alt="Customer Image" width="80" height="80" style="object-fit: cover; border-radius: 6px;">
                        </div>
                    @endif
                </div>

                {{-- Status --}}
                <div class="mb-3 col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="1" {{ old('status', $testimonial->status) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $testimonial->status) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Update Testimonial</button>
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
