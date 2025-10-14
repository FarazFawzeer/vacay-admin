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
                <td>
                    <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-sm btn-equal btn-primary">Edit</a>
                    <button type="button" data-id="{{ $package->id }}" class="btn btn-sm btn-danger btn-equal delete-package">Delete</button>
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
