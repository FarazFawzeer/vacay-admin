@php
    $first20TourIds = $first20TourIds ?? [];
@endphp

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted fw-semibold text-uppercase small">Heading</th>
                        <th class="px-3 py-3 text-muted fw-semibold text-uppercase small">Ref No</th>
                        <th class="px-3 py-3 text-muted fw-semibold text-uppercase small">Country</th>
                        <th class="px-3 py-3 text-muted fw-semibold text-uppercase small">Place</th>
                        <th class="px-3 py-3 text-muted fw-semibold text-uppercase small">Type</th>
                        <th class="px-3 py-3 text-muted fw-semibold text-uppercase small">Category</th>
                        <th class="px-3 py-3 text-muted fw-semibold text-uppercase small text-center">Duration</th>
                        <th class="px-3 py-3 text-muted fw-semibold text-uppercase small text-end">Price</th>
                        <th class="px-3 py-3 text-muted fw-semibold text-uppercase small text-center">Status</th>
                        <th class="px-4 py-3 text-muted fw-semibold text-uppercase small text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        @php
                            $isSuperAdmin = auth()->user()->type === 'Super Admin';
                            $isFirst20 = in_array($package->id, $first20TourIds);
                        @endphp

                        <tr id="package-{{ $package->id }}"
                            class="{{ $isFirst20 ? 'bg-warning bg-opacity-10 border-start border-warning border-3' : '' }}">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    @if ($isFirst20)
                                        <i class="bi bi-star-fill text-warning me-2"></i>
                                    @endif
                                    <span class="fw-medium text-dark">{{ $package->heading }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <span class="badge bg-light text-dark border">{{ $package->tour_ref_no }}</span>
                            </td>
                            <td class="px-3 py-3 text-muted">{{ $package->country_name ?? '-' }}</td>
                            <td class="px-3 py-3 text-muted">{{ $package->place ?? '-' }}</td>
                            <td class="px-3 py-3">
                                @if ($package->type)
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary border-0">{{ $package->type }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-muted">{{ $package->tour_category ?? '-' }}</td>
                            <td class="px-3 py-3 text-center">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border-0">
                                    {{ $package->days ?? 0 }}D / {{ $package->nights ?? 0 }}N
                                </span>
                            </td>
                            <td class="px-3 py-3 text-end">
                                <span class="fw-semibold text-dark">{{ $package->price ?? '-' }}</span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                @if ($package->status)
                                    <span
                                        class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>Published
                                    </span>
                                @else
                                    <span
                                        class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary border border-secondary">
                                        <i class="bi bi-x-circle-fill me-1"></i>Unpublished
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.packages.show', $package->id) }}"
                                        class="btn btn-sm btn-outline-info rounded-circle p-0 d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;" title="View Package"
                                        data-bs-toggle="tooltip">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    @if ($isFirst20)
                                        @if ($isSuperAdmin)
                                            <a href="{{ route('admin.packages.edit', $package->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-circle p-0 d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;" title="Edit Package"
                                                data-bs-toggle="tooltip">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <button type="button" data-id="{{ $package->id }}"
                                                class="btn btn-sm btn-outline-{{ $package->status ? 'success' : 'warning' }} rounded-circle p-0 d-flex align-items-center justify-content-center toggle-status"
                                                style="width: 32px; height: 32px;" data-status="{{ $package->status }}"
                                                title="{{ $package->status ? 'Unpublish' : 'Publish' }}"
                                                data-bs-toggle="tooltip">
                                                <i
                                                    class="bi bi-{{ $package->status ? 'check-circle-fill' : 'slash-circle' }}"></i>
                                            </button>

                                            <button type="button" data-id="{{ $package->id }}"
                                                class="btn btn-sm btn-outline-danger rounded-circle p-0 d-flex align-items-center justify-content-center delete-package"
                                                style="width: 32px; height: 32px;" title="Delete Package"
                                                data-bs-toggle="tooltip">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                                <i class="bi bi-lock-fill me-1"></i>Restricted
                                            </span>
                                        @endif
                                    @else
                                        <a href="{{ route('admin.packages.edit', $package->id) }}"
                                            class="btn btn-sm btn-outline-primary rounded-circle p-0 d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;" title="Edit Package"
                                            data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <button type="button" data-id="{{ $package->id }}"
                                            class="btn btn-sm btn-outline-{{ $package->status ? 'success' : 'warning' }} rounded-circle p-0 d-flex align-items-center justify-content-center toggle-status"
                                            style="width: 32px; height: 32px;" data-status="{{ $package->status }}"
                                            title="{{ $package->status ? 'Unpublish' : 'Publish' }}"
                                            data-bs-toggle="tooltip">
                                            <i
                                                class="bi bi-{{ $package->status ? 'check-circle-fill' : 'slash-circle' }}"></i>
                                        </button>

                                        <button type="button" data-id="{{ $package->id }}"
                                            class="btn btn-sm btn-outline-danger rounded-circle p-0 d-flex align-items-center justify-content-center delete-package"
                                            style="width: 32px; height: 32px;" title="Delete Package"
                                            data-bs-toggle="tooltip">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    <p class="mb-0">No packages found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $packages->links() }}
</div>

<style>
    /* Smooth hover effects */
    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02) !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Action button hover effects */
    .btn-outline-info:hover,
    .btn-outline-primary:hover,
    .btn-outline-success:hover,
    .btn-outline-warning:hover,
    .btn-outline-danger:hover {
        transform: scale(1.1);
        transition: all 0.2s ease;
    }

    /* Star animation for first 20 */
    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.6;
        }
    }

    .bg-warning.bg-opacity-10 i.bi-star-fill {
        animation: pulse 2s infinite;
    }

    /* Better responsive table */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }

        .btn-sm {
            width: 28px !important;
            height: 28px !important;
        }
    }
</style>

<script>
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
