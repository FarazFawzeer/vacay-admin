@extends('layouts.vertical', ['subtitle' => 'Hotels'])

@section('content')
    {{-- Page Title with Hotel Name --}}
    @include('layouts.partials.page-title', [
        'title' => $hotel->hotel_name,
        'subtitle' => 'Hotel Details',
    ])

    <div class="container-fluid">
        {{-- Action Buttons --}}
        <div class="d-flex justify-content-end align-items-center mb-4">
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">
                <i class="uil-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="row">
            {{-- Main Content Area --}}
            <div class="col-lg-8">
                {{-- Hotel Header/Summary Card --}}
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-body p-4">
                        <h1 class="h3 mb-3 text-truncate">{{ $hotel->hotel_name }}</h1>

                        {{-- Badges/Metadata Row --}}
                        <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
                            {{-- Category Badge --}}
                            <span class="badge bg-info-subtle text-info fw-normal p-2">
                                <i class="uil-building me-1"></i>
                                {{ ucfirst($hotel->hotel_category) ?? 'Hotel' }}
                            </span>

                            {{-- Star Rating Badge --}}
                            <span class="badge bg-warning-subtle text-warning fw-normal p-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="uil-star{{ $i <= $hotel->star ? '' : '-outline' }}"></i>
                                @endfor
                                <span class="ms-1">{{ $hotel->star ?? 0 }}/5 Stars</span>
                            </span>

                            {{-- Status Badge --}}
                            @php
                                $statusClass = [1 => 'success', 0 => 'danger', 2 => 'dark'];
                                $statusIcon = [1 => 'uil-check-circle', 0 => 'uil-times-circle', 2 => 'uil-clock'];
                                $statusText = [1 => 'Active', 0 => 'Inactive', 2 => 'Expired'];
                                $status = $hotel->status ?? 0;
                            @endphp
                            <span
                                class="badge bg-{{ $statusClass[$status] ?? 'secondary' }}-subtle text-{{ $statusClass[$status] ?? 'secondary' }} fw-normal p-2">
                                <i class="{{ $statusIcon[$status] ?? 'uil-question-circle' }} me-1"></i>
                                {{ $statusText[$status] ?? 'Unknown' }}
                            </span>
                        </div>

                        {{-- Location --}}
                        <div class="d-lg-flex justify-content-between align-items-center">
                            <div class="mb-2 mb-lg-0">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="uil-map-marker me-2 fs-5 text-primary"></i>
                                    <strong class="text-dark fs-5 me-2">{{ $hotel->country ?? 'Country N/A' }}</strong>
                                </div>
                                <p class="text-muted small mb-0 ms-4 ps-1 border-start border-2">
                                    {{ $hotel->city ?? 'City N/A' }},
                                    <span class="d-block d-sm-inline">{{ $hotel->address ?? 'Address N/A' }}</span>
                                </p>
                            </div>
                            <div class="text-sm-end text-muted small">
                                Last Updated: <br class="d-lg-none">
                                <strong class="text-dark">
                                    {{ $hotel->updated_at ? $hotel->updated_at->format('d M Y, h:i A') : 'N/A' }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Gallery Section --}}
                @if (!empty($hotel->pictures) && is_array($hotel->pictures))
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-3"><i class="uil-images me-2 text-primary"></i>Hotel Gallery</h4>
                            <div class="row g-3">
                                @foreach ($hotel->pictures as $pic)
                                    <div class="col-md-4 col-sm-6 col-6">
                                        <a href="#"
                                            class="picture-viewer-trigger d-block rounded overflow-hidden shadow-sm hover-zoom"
                                            data-img-src="{{ asset($pic) }}">
                                            <img src="{{ asset($pic) }}" class="img-fluid w-100"
                                                alt="{{ $hotel->hotel_name }}" style="height: 200px; object-fit: cover;">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Room Types & Pricing --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-3"><i class="uil-bed me-2 text-primary"></i>Room Types and Prices</h4>
                        @if (!empty($hotel->room_type) && is_array($hotel->room_type))
                            <ul class="list-unstyled mb-0">
                                @foreach ($hotel->room_type as $room)
                                    <li class="mb-3 p-3 border rounded d-flex align-items-center gap-3">
                                        @if (!empty($room['image']))
                                            <img src="{{ asset($room['image']) }}" alt="{{ $room['type'] ?? '' }}"
                                                class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                        @endif
                                        <div class="flex-grow-1">
                                            <strong class="d-block fs-5 text-dark">
                                                <i
                                                    class="uil-bed me-1 text-primary"></i>{{ $room['type'] ?? 'Not specified' }}
                                            </strong>
                                            <span class="d-block mt-1">
                                                <i class="uil-utensils me-1 text-success"></i>
                                                <strong>Meal Plan:</strong> {{ $room['meal_plan'] ?? 'N/A' }}
                                            </span>
                                            <span class="d-block mt-1">
                                                <i class="uil-usd-circle me-1 text-warning"></i>
                                                <strong>Price:</strong>
                                                <span class="text-success fw-bold">{{ $room['currency'] ?? 'USD' }}
                                                    {{ $room['price'] ?? 0 }}</span>
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No room types added.</p>
                        @endif
                    </div>
                </div>

                {{-- Meal Plans --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-3"><i class="uil-restaurant me-2 text-primary"></i>Meal & Additional Costs
                        </h4>
                        @if (!empty($hotel->meal_costs) && is_array($hotel->meal_costs))
                            <div class="row g-2">
                                @foreach ($hotel->meal_costs as $meal)
                                    <div class="col-sm-6">
                                        <div class="p-2 border rounded d-flex justify-content-between align-items-center">
                                            <div class="text-dark">{{ $meal['name'] ?? 'Unnamed Meal' }}</div>
                                            <div>
                                                <strong class="text-success">
                                                    {{ $meal['currency'] ?? 'USD' }}
                                                    {{ number_format($meal['price'] ?? 0, 2) }}
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">No additional meal costs listed.</p>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-3"><i class="uil-file-alt me-2 text-primary"></i>About Hotel</h4>
                        <p class="text-secondary" style="line-height: 1.8;">
                            {{ $hotel->description ?? 'No detailed description available for this hotel.' }}
                        </p>
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Contact Details --}}
                {{-- Contact Details --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-3"><i class="uil-user-circle me-2 text-primary"></i>Contact Details</h4>

                        {{-- Contact Person --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="uil-user fs-4 text-primary"></i>
                            </div>
                            <div class="w-100 d-flex justify-content-between">
                                <div class="text-dark fw-semibold">1. Contact Person</div>
                                <div class="text-muted">{{ $hotel->contact_person ?? 'N/A' }}</div>
                            </div>
                        </div>

                        {{-- Landline Number --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="uil-phone fs-4 text-success"></i>
                            </div>
                            <div class="w-100 d-flex justify-content-between">
                                <div class="text-dark fw-semibold">2. Landline Number</div>
                                <div class="text-muted">{{ $hotel->landline_number ?? 'N/A' }}</div>
                            </div>
                        </div>

                        {{-- Mobile Number --}}
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="uil-mobile-android fs-4 text-warning"></i>
                            </div>
                            <div class="w-100 d-flex justify-content-between">
                                <div class="text-dark fw-semibold">3. Mobile Number</div>
                                <div class="text-muted">{{ $hotel->mobile_number ?? 'N/A' }}</div>
                            </div>
                        </div>

                    </div>
                </div>


                {{-- Facilities --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-3"><i class="uil-wrench me-2 text-primary"></i>Facilities</h4>
                        @if (!empty($hotel->facilities) && is_array($hotel->facilities))
                            <ul class="ps-4 mb-0">
                                @foreach ($hotel->facilities as $facility)
                                    <li class="mb-2 text-dark">{{ $facility }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No facilities listed.</p>
                        @endif
                    </div>
                </div>

                {{-- Entertainment --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-3"><i class="uil-music me-2 text-primary"></i>Entertainment</h4>
                        @if (!empty($hotel->entertainment) && is_array($hotel->entertainment))
                            <ul class="ps-4 mb-0">
                                @foreach ($hotel->entertainment as $ent)
                                    <li class="mb-2 text-dark">{{ $ent }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No entertainment listed.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Image Viewer Modal --}}
    <div class="modal fade" id="imageViewerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <img id="imageViewerImg" src="" class="img-fluid rounded w-100" alt="Hotel Image">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Picture Viewer
        const pictureTriggers = document.querySelectorAll('.picture-viewer-trigger');
        const imageViewerImg = document.getElementById('imageViewerImg');
        const imageViewerModal = new bootstrap.Modal(document.getElementById('imageViewerModal'));

        pictureTriggers.forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();
                imageViewerImg.src = item.dataset.imgSrc;
                imageViewerModal.show();
            });
        });

        // Hover zoom effect
        document.querySelectorAll('.hover-zoom img').forEach(img => {
            img.addEventListener('mouseenter', () => img.style.transform = 'scale(1.05)');
            img.addEventListener('mouseleave', () => img.style.transform = 'scale(1)');
        });
    </script>
@endsection

@section('style')
    <style>
        .hotel-bullet {
            position: relative;
            padding-left: 18px;
        }

        .hotel-bullet::before {
            content: "•";
            position: absolute;
            left: 0;
            top: 2px;
            font-size: 20px;
            color: #0d6efd;
            line-height: 16px;
        }

        .hover-zoom {
            position: relative;
            display: block;
            cursor: pointer;
        }

        .hover-zoom img {
            transition: transform 0.3s ease-in-out, opacity 0.3s;
        }

        .hover-zoom::after {
            content: '\eb9f';
            font-family: 'Unicons';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2.5rem;
            color: #fff;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .hover-zoom:hover::after {
            opacity: 1;
        }

        .hover-zoom:hover img {
            opacity: 0.75;
        }
    </style>
@endsection
