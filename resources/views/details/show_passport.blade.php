@extends('layouts.vertical', ['subtitle' => 'Passport Details'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Passport Details', 'subtitle' => 'Passport'])

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold text-dark">Passport Information</h5>
                        <a href="{{ route('admin.passports.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="mdi mdi-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Customer Section -->
                    <!-- Customer Section -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Customer Information
                        </h6>
                        <div class="d-flex align-items-center mb-3">
                            <div
                                class="avatar-sm rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3">
                                <i class="bi bi-person text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold text-dark">
                                    {{ $passport->customer->name ?? $passport->customer->first_name }}
                                </p>
                                <small class="text-muted">Account Holder</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Customer Code:</strong> {{ $passport->customer->customer_code ?? '-' }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Email:</strong> {{ $passport->customer->email ?? '-' }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Contact:</strong> {{ $passport->customer->contact ?? '-' }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Address:</strong> {{ $passport->customer->address ?? '-' }}
                            </div>

                            <div class="col-md-6 mb-2">
                                <strong>Country:</strong> {{ $passport->customer->country ?? '-' }}
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Type:</strong> {{ $passport->customer->type ?? '-' }}
                            </div>

                        </div>
                    </div>


                    <!-- Personal Details -->
                    <!-- Personal Details -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Personal Details</h6>
                        <div class="row g-4">
                            <div class="col-md-6 col-lg-4">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-file-text text-primary me-2 mt-1 fs-5"></i>
                                    <div class="flex-grow-1">
                                        <label class="text-muted small mb-1">Passport Number</label>
                                        <p class="mb-0 fw-semibold text-dark">{{ $passport->passport_number }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-file-text text-primary me-2 mt-1 fs-5"></i>
                                    <div class="flex-grow-1">
                                        <label class="text-muted small mb-1">First Name</label>
                                        <p class="mb-0 fw-semibold text-dark">{{ $passport->first_name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-file-text text-primary me-2 mt-1 fs-5"></i>
                                    <div class="flex-grow-1">
                                        <label class="text-muted small mb-1">Second Name</label>
                                        <p class="mb-0 fw-semibold text-dark">{{ $passport->second_name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-file-text text-primary me-2 mt-1 fs-5"></i>
                                    <div class="flex-grow-1">
                                        <label class="text-muted small mb-1">Nationality</label>
                                        <p class="mb-0 fw-semibold text-dark">{{ $passport->nationality }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-file-text text-primary me-2 mt-1 fs-5"></i>
                                    <div class="flex-grow-1">
                                        <label class="text-muted small mb-1">Sex</label>
                                        <p class="mb-0">
                                            <span class="badge bg-info bg-opacity-10 text-info px-2 py-1">
                                                {{ ucfirst($passport->sex ?? 'N/A') }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-file-text text-primary me-2 mt-1 fs-5"></i>
                                    <div class="flex-grow-1">
                                        <label class="text-muted small mb-1">Date of Birth</label>
                                        <p class="mb-0 fw-semibold text-dark">{{ $passport->dob }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-file-text text-primary me-2 mt-1 fs-5"></i>
                                    <div class="flex-grow-1">
                                        <label class="text-muted small mb-1">ID Number</label>
                                        <p class="mb-0 fw-semibold text-dark">{{ $passport->id_number ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Passport Dates -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Passport Dates</h6>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-file-text text-primary me-2 mt-1 fs-5"></i>
                                    <div class="flex-grow-1">
                                        <label class="text-muted small mb-1">Issue Date</label>
                                        <p class="mb-0 fw-semibold text-dark">{{ $passport->issue_date ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-file-text text-primary me-2 mt-1 fs-5"></i>
                                    <div class="flex-grow-1">
                                        <label class="text-muted small mb-1">Expiry Date</label>
                                        <p class="mb-0 fw-semibold">
                                            @php
                                                $expiryDate = \Carbon\Carbon::parse($passport->passport_expire_date);
                                                $isExpiringSoon =
                                                    $expiryDate->diffInDays(now()) <= 180 && $expiryDate->isFuture();
                                                $isExpired = $expiryDate->isPast();
                                            @endphp

                                            <span
                                                class="badge px-2 py-1 {{ $isExpired ? 'bg-danger' : ($isExpiringSoon ? 'bg-warning' : 'bg-success') }}">
                                                {{ $passport->passport_expire_date }}
                                            </span>

                                            @if ($isExpired)
                                                <small class="text-danger d-block mt-1">Expired</small>
                                            @elseif($isExpiringSoon)
                                                <small class="text-warning d-block mt-1">Expiring Soon</small>
                                            @else
                                                <small class="text-success d-block mt-1">Valid</small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- ID Photo -->
                    <!-- ID Photo -->
                    <div>
                        <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Identification Proof
                        </h6>
                        <div class="d-flex align-items-start flex-wrap">
                            <i class="mdi mdi-image text-primary me-2 mt-1" style="font-size: 1.2rem;"></i>
                            <div class="flex-grow-1 d-flex flex-wrap gap-2">

                                @if ($passport->id_photo && is_array($passport->id_photo))
                                    @foreach ($passport->id_photo as $index => $file)
                                        @php
                                            $ext = pathinfo($file, PATHINFO_EXTENSION);
                                        @endphp

                                        <div class="position-relative d-inline-block">
                                            @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                                                <img src="{{ asset('admin/storage/' . $file) }}" alt="ID Photo"
                                                    class="rounded border shadow-sm"
                                                    style="width: 200px; height: auto; cursor: pointer;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#photoModal{{ $index }}">
                                            @elseif(strtolower($ext) === 'pdf')
                                                <a href="{{ asset('admin/storage/' . $file) }}" target="_blank"
                                                    class="d-inline-flex align-items-center justify-content-center bg-light rounded border shadow-sm"
                                                    style="width: 200px; height: 150px; text-decoration: none;">
                                                    <i class="bi bi-file-earmark-pdf-fill text-danger"
                                                        style="font-size: 2rem;"></i>
                                                    <span class="ms-2 small text-dark">PDF Document</span>
                                                </a>
                                            @endif

                                            <div class="position-absolute top-0 end-0 m-2">
                                                <button class="btn btn-sm btn-light rounded-circle"
                                                    @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp'])) data-bs-toggle="modal" data-bs-target="#photoModal{{ $index }}"
                                @elseif(strtolower($ext) === 'pdf')
                                    onclick="window.open('{{ asset('admin/storage/' . $file) }}','_blank')" @endif>
                                                    <i class="mdi mdi-magnify"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded border"
                                        style="width: 200px; height: 150px;">
                                        <div class="text-center">
                                            <i class="mdi mdi-image-off text-muted" style="font-size: 2rem;"></i>
                                            <p class="text-muted small mb-0 mt-2">No file available</p>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- Photo Modal -->
    @if ($passport->id_photo && is_array($passport->id_photo))
        @foreach ($passport->id_photo as $index => $photo)
            <div class="modal fade" id="photoModal{{ $index }}" tabindex="-1"
                aria-labelledby="photoModalLabel{{ $index }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title" id="photoModalLabel{{ $index }}">ID Photo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center p-4">
                            <img src="{{ asset('admin/storage/' . $photo) }}" alt="ID Photo"
                                class="img-fluid rounded shadow" style="max-height: 70vh;">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

@endsection
