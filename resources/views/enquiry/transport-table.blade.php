<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>WhatsApp</th>
            <th>Country</th>
            <th>Pickup</th>
            <th>Dropoff</th>
            <th>Start</th>
            <th>End</th>
            <th>Vehicle</th>
            <th>Service Type</th>
            <th>Hour Count</th>
            <th>Message</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @forelse($requests as $req)
            <tr>
                <td>{{ $req->full_name }}</td>
                <td>{{ $req->email }}</td>
                <td>{{ $req->phone }}</td>
                <td>{{ $req->whatsapp }}</td>
                <td>{{ $req->country }}</td>
                <td>{{ $req->pickup_location }}</td>
                <td>{{ $req->drop_location }}</td>
                <td>{{ $req->start_date?->format('d M Y') }} {{ $req->start_time?->format('H:i') }}</td>
                <td>{{ $req->end_date?->format('d M Y') }} {{ $req->end_time?->format('H:i') }}</td>
                <td>{{ $req->vehicle->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($req->service_type) }}</td>
                <td>{{ $req->hour_count }}</td>
                <td>{{ $req->message }}</td>
                <td>
                    <select class="form-select form-select-sm changeStatus" data-id="{{ $req->id }}">
                        <option value="pending" {{ $req->status=='pending'?'selected':'' }}>Pending</option>
                        <option value="confirmed" {{ $req->status=='confirmed'?'selected':'' }}>Confirmed</option>
                        <option value="completed" {{ $req->status=='completed'?'selected':'' }}>Completed</option>
                        <option value="cancelled" {{ $req->status=='cancelled'?'selected':'' }}>Cancelled</option>
                    </select>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="14" class="text-center">No transportation bookings found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $requests->links() }}
