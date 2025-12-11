@extends('layouts.vertical', ['subtitle' => 'Visa Details'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Visa Details', 'subtitle' => 'Visa'])

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold text-dark">Visa Information</h5>
                    <a href="{{ route('admin.visa.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="mdi mdi-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="mb-5 pb-3 border-bottom">
                        <h6 class="text-uppercase text-muted mb-3 fs-6-sm">
                            Main Details
                        </h6>

                        <div class="row g-4">
                            <div class="col-md-6 col-lg-4">
                                <label class="text-muted small mb-1">From Country</label>
                                <p class="mb-0 fw-bold text-dark">{{ $visa->from_country ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <label class="text-muted small mb-1">To Country</label>
                                <p class="mb-0 fw-bold text-dark">{{ $visa->to_country ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <label class="text-muted small mb-1">Visa Type</label>
                                <p class="mb-0 fw-bold text-dark">
                                    {{ $visa->visa_type ?? '-' }}
                                    @if($visa->visa_type === 'custom' && $visa->custom_visa_type)
                                        <span class="text-muted">({{ $visa->custom_visa_type }})</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5 pb-3 border-bottom">
                        <h6 class="text-uppercase text-muted mb-3 fs-6-sm">
                            Visa Categories & Pricing
                        </h6>
                        @if($visa->categories && $visa->categories->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Visa Type</th>
                                            <th scope="col">State</th>
                                            <th scope="col">Days (Max Stay)</th>
                                            <th scope="col">Validity</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Processing Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($visa->categories as $cat)
                                            <tr>
                                                <td>{{ $cat->visa_type ?? '-' }}</td>
                                                <td>{{ $cat->state ?? '-' }}</td>
                                                <td>{{ $cat->days ?? $cat->how_many_days ?? '-' }}</td>
                                                <td>{{ $cat->visa_validity ?? '-' }}</td>
                                                <td class="fw-bold">{{ $cat->price ? number_format($cat->price) : '-' }} {{ $cat->currency ?? '' }}</td>
                                                <td>{{ $cat->processing_time ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <span class="text-muted">No categories added for this visa.</span>
                        @endif
                    </div>

                    <div class="mb-5 pb-3 border-bottom">
                        <h6 class="text-uppercase text-muted mb-3 fs-6-sm">
                            Documents
                        </h6>
                        <div class="d-flex flex-wrap">
                            @if($visa->documents && count($visa->documents) > 0)
                                @foreach($visa->documents as $doc)
                                    @php
                                        $ext = pathinfo($doc, PATHINFO_EXTENSION);
                                        $path = asset('storage/' . $doc);
                                    @endphp
                                    <div class="me-3 mb-3 text-center">
                                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','webp']))
                                            <a href="{{ $path }}" target="_blank">
                                                <img src="{{ $path }}" alt="Document" width="100" class="rounded border shadow-sm" style="height: 100px; object-fit: cover;">
                                            </a>
                                            <p class="small text-muted mt-1 mb-0">{{ strtoupper($ext) }}</p>
                                        @elseif(strtolower($ext) === 'pdf')
                                            <a href="{{ $path }}" target="_blank" class="btn btn-lg btn-danger rounded-circle d-block mx-auto" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-file-earmark-pdf-fill fs-3"></i>
                                            </a>
                                            <p class="small text-muted mt-1 mb-0">PDF File</p>
                                        @else
                                            <a href="{{ $path }}" target="_blank" class="btn btn-lg btn-outline-secondary rounded-circle d-block mx-auto" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-file-earmark-text fs-3"></i>
                                            </a>
                                            <p class="small text-muted mt-1 mb-0">{{ strtoupper($ext) }} File</p>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <span class="text-muted">No document uploaded.</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-5 pb-3 border-bottom">
                        <h6 class="text-uppercase text-muted mb-3 fs-6-sm">
                            Required Documents / Checklist
                        </h6>
                        @if(is_array($visa->checklist) && count($visa->checklist) > 0)
                            <ul class="list-unstyled">
                                @foreach($visa->checklist as $item)
                                    <li class="d-flex align-items-start mb-2">
                                        <i class="mdi mdi-check-circle-outline text-success me-2 mt-1"></i>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">No checklist items provided.</span>
                        @endif
                    </div>

                    <div class="mb-5 pb-3 border-bottom">
                        <h6 class="text-uppercase text-muted mb-3 fs-6-sm">
                            Additional Notes
                        </h6>
                        <blockquote class="blockquote border-start border-3 border-info ps-3 bg-light p-3 rounded">
                            <p class="mb-0 fw-normal text-dark">{{ $visa->note ?? 'No additional notes.' }}</p>
                        </blockquote>
                    </div>

                    <div class="mb-5 pb-3 border-bottom">
                        <h6 class="text-uppercase text-muted mb-3 fs-6-sm">
                            Assigned Agents
                        </h6>
                        <div>
                            @forelse($visa->agents as $agent)
                                <span class="badge bg-primary text-white me-2 mb-2 p-2 fs-6">{{ $agent->company_name }} - {{ $agent->name }}</span>
                            @empty
                                <span class="text-muted">No agents assigned.</span>
                            @endforelse
                        </div>
                    </div>

                    @if(auth()->user() && auth()->user()->type === 'Super Admin')
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="text-uppercase text-muted mb-3 fs-6-sm">
                                System Information
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <th class="py-1">Created By:</th>
                                            <td class="py-1">{{ $visa->user->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-1">Created At:</th>
                                            <td class="py-1">{{ $visa->created_at ? $visa->created_at->format('d M Y, h:i A') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="py-1">Last Updated:</th>
                                            <td class="py-1">{{ $visa->updated_at ? $visa->updated_at->format('d M Y, h:i A') : '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('admin.visa.index') }}" class="btn btn-secondary mt-4">
                        <i class="mdi mdi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Optional: Custom style for section headers */
        .fs-6-sm {
            font-size: 0.75rem !important; /* Retaining your original small size */
            letter-spacing: 0.5px;
        }
    </style>
@endpush