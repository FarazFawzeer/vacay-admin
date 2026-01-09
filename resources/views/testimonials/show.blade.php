@extends('layouts.vertical', ['subtitle' => 'View Testimonial'])

@section('content')
@include('layouts.partials.page-title', [
    'title' => 'Testimonials',
    'subtitle' => 'View'
])

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Testimonial Details</h5>
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-sm btn-secondary">
         Back
        </a>
    </div>

    <div class="card-body">
        <div class="row">

            {{-- Customer Info --}}
            <div class="col-md-8 mb-3">
                <h4 class="mb-1">{{ $testimonial->name }}</h4>

                @if ($testimonial->source)
                    <span class="badge bg-info">{{ $testimonial->source }}</span>
                @endif
            </div>

            {{-- Status --}}
            <div class="col-md-4 mb-3 text-md-end">
                @if ($testimonial->status)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </div>

            {{-- Posted Date --}}
            <div class="col-md-4 mb-3">
                <strong>Posted On:</strong><br>
                {{ \Carbon\Carbon::parse($testimonial->postedate)->format('d M Y') }}
            </div>

            {{-- Rating --}}
            <div class="col-md-4 mb-3">
                <strong>Rating:</strong><br>
                @if ($testimonial->rating)
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="bi {{ $i <= $testimonial->rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                    @endfor
                @else
                    <span class="text-muted">N/A</span>
                @endif
            </div>

            {{-- Source Link --}}
            <div class="col-md-4 mb-3">
                <strong>Link:</strong><br>
                @if ($testimonial->link)
                    <a href="{{ $testimonial->link }}" target="_blank">
                        View Source
                    </a>
                @else
                    <span class="text-muted">N/A</span>
                @endif
            </div>

            {{-- Message --}}
            <div class="col-md-12 mb-4">
                <strong>Message:</strong>
                <div class="border rounded p-3 mt-2 bg-light">
                    {!! nl2br(e($testimonial->message)) !!}
                </div>
            </div>

            {{-- Customer Image --}}
            <div class="col-md-12">
                <strong>Customer Image:</strong>
                <div class="mt-2">
                    @if ($testimonial->image)
                        <img src="{{ asset('admin/storage/' . $testimonial->image) }}"
                             class="img-thumbnail"
                             style="max-width:200px;">
                    @else
                        <p class="text-muted">No image uploaded</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
