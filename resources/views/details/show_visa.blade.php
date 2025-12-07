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
                    <!-- Visa Details -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Main Details
                        </h6>

                        <div class="row g-4">
                            <div class="col-md-6 col-lg-4">
                                <div class="d-flex align-items-start">
                             
                                    <div class="flex-grow-1">
                                        <label class="text-muted small mb-1">Country</label>
                                        <p class="mb-0 fw-semibold text-dark">{{ $visa->country }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="d-flex align-items-start">
                                 
                                    <div class="flex-grow-1">
                                        <label class="text-muted small mb-1">Visa Type</label>
                                        <p class="mb-0 fw-semibold text-dark">{{ $visa->visa_type }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="d-flex align-items-start">
                                  
                                    <div class="flex-grow-1">
                                        <label class="text-muted small mb-1">Visa Details</label>
                                        <p class="mb-0 fw-semibold text-dark">{{ $visa->visa_details ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Documents
                        </h6>
                        <div class="d-flex align-items-start">
                          
                            <div class="flex-grow-1">
                                @if ($visa->documents)
                                    <img src="{{ asset('admin/storage/' . $visa->documents) }}" width="200"
                                        class="rounded border shadow-sm">
                                @else
                                    <span class="text-muted">No document uploaded</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Note -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Note
                        </h6>
                        <p class="mb-0 fw-semibold text-dark">{{ $visa->note ?? '-' }}</p>
                    </div>

                    <!-- Assigned Agents -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Assigned Agents
                        </h6>
                        <div>
                            @forelse($visa->agents as $agent)
                                <span class="badge bg-primary me-1 mb-1">{{ $agent->company_name }} -
                                    {{ $agent->name }}</span>
                            @empty
                                <span class="text-muted">No agents assigned</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Created By -->
                    {{-- Only visible to Super Admin --}}
                    @if (auth()->user()->type === 'Super Admin')
                        <!-- User Data -->
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                User Data
                            </h6>
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th>Created By</th>
                                    <td>{{ $visa->user->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $visa->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
                                    <td>{{ $visa->updated_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    @endif


                    <a href="{{ route('admin.visa.index') }}" class="btn btn-secondary mt-3">Back to List</a>
                </div>
            </div>
        </div>
    </div>
@endsection
