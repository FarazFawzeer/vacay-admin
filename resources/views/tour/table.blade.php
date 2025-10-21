<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Heading</th>
            <th>Ref No</th>
            <th>Country</th>
            <th>Place</th>
            <th>Type</th>
            <th>Category</th>
            <th>Days / Nights</th>
            <th>Price</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($packages as $package)
            <tr id="package-{{ $package->id }}">
                <td>{{ $package->heading }}</td>
                <td>{{ $package->tour_ref_no }}</td>
                <td>{{ $package->country_name ?? '-' }}</td>
                <td>{{ $package->place ?? '-' }}</td>
                <td>{{ $package->type ?? '-' }}</td>
                <td>{{ $package->tour_category ?? '-' }}</td>
                <td>{{ $package->days ?? 0 }} / {{ $package->nights ?? 0 }}</td>
                <td>{{ $package->price ?? '-' }}</td>
                <td>{{ $package->status ?? '-' }}</td>
                <td class="text-center">

                    {{-- View Button --}}
                    <a href="{{ route('admin.packages.show', $package->id) }}" class="icon-btn text-info"
                        title="View Package">
                        <i class="bi bi-eye-fill fs-5"></i>
                    </a>

                    {{-- Edit Button --}}
                    <a href="{{ route('admin.packages.edit', $package->id) }}" class="icon-btn text-primary"
                        title="Edit Package">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </a>

                    {{-- Toggle Status Button --}}
                    <button type="button" data-id="{{ $package->id }}"
                        class="icon-btn {{ $package->status ? 'text-success' : 'text-warning' }} toggle-status"
                        data-status="{{ $package->status }}"
                        title="{{ $package->status ? 'Change to Not Published' : 'Change to Published' }}">
                        @if ($package->status)
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        @else
                            <i class="bi bi-slash-circle fs-5"></i>
                        @endif
                    </button>

                    {{-- Delete Button --}}
                    <button type="button" data-id="{{ $package->id }}" class="icon-btn text-danger delete-package"
                        title="Delete Package">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>

                </td>



            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center">No packages found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $packages->links() }}
