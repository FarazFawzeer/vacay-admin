<div class="table-responsive" style="overflow-x:auto;">
    <table class="table table-striped table-hover table-sm text-nowrap">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>WhatsApp</th>
                <th>Country</th>
                <th>Trip Type</th>
                <th>Airline</th>
                <th>From</th>
                <th>To</th>
                <th>Departure</th>
                <th>Return</th>
                <th>Passengers</th>
                <th style="min-width: 200px;">Message</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->full_name }}</td>
                    <td>{{ $booking->email }}</td>
                    <td>{{ $booking->phone }}</td>
                    <td>{{ $booking->whatsapp }}</td>
                    <td>{{ $booking->country }}</td>
                    <td>{{ ucfirst($booking->trip_type) }}</td>
                    <td>{{ $booking->airline }}</td>
                    <td>{{ $booking->from }}</td>
                    <td>{{ $booking->to }}</td>
                    <td>{{ $booking->departure_date }}</td>
                    <td>{{ $booking->return_date ?? '-' }}</td>
                    <td>{{ $booking->passengers }}</td>
                    <td>{{ $booking->message }}</td>
                    <td>{{ $booking->created_at->format('d M Y') }}</td>
                    <td>
                        <select class="form-select form-select-sm changeStatus" data-id="{{ $booking->id }}">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="viewed" {{ $booking->status == 'viewed' ? 'selected' : '' }}>Viewed</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                    </td>
                    <td class="text-center">
                        <button type="button" class="icon-btn text-danger delete-row" data-id="{{ $booking->id }}"
                            data-url="{{ route('admin.enquiry.airTicket.delete', $booking->id) }}" title="Delete">
                            <i class="fa fa-trash fa-lg"></i>
                        </button>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center">No airline bookings found.</td>
                </tr>
            @endforelse

        </tbody>
    </table>
</div>

{{ $bookings->links() }}
