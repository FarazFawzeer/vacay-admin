<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Vehicle</th>
            <th>Start</th>
            <th>End</th>
            <th>Message</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse($bookings as $item)
            <tr>
                <td>{{ $item->full_name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->phone }}</td>
                <td>{{ optional($item->vehicle)->title ?? 'N/A' }}</td>
                <td>{{ $item->start_date?->format('Y-m-d') }}</td>
                <td>{{ $item->end_date?->format('Y-m-d') }}</td>
                <td>{{ $item->message }}</td>
                <td>{{ $item->created_at->format('d M Y') }}</td>
                <td>
                    <select class="form-select form-select-sm changeStatus" data-id="{{ $item->id }}">
                        <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="viewed" {{ $item->status == 'viewed' ? 'selected' : '' }}>Viewed</option>
                        <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Completed
                        </option>
                    </select>
                </td>
                <td class="text-center">
                    <button type="button" class="icon-btn text-danger delete-row" data-id="{{ $item->id }}"
                        data-url="{{ route('admin.enquiry.rentVehicle.delete', $item->id) }}" title="Delete">
                        <i class="fa fa-trash fa-lg"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No vehicle bookings found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $bookings->links() }}
