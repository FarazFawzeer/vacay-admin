<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Customer</th>
            <th>Visa</th>
            <th>Passport No</th>
            <th>Issue Date</th>
            <th>Expiry Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse($bookings as $booking)
            <tr id="booking-{{ $booking->id }}">
                <td>{{ $booking->inv_no }}</td>
                <td>{{ $booking->customer->name ?? '-' }}</td>
                <td>{{ $booking->visa->country ?? '-' }} - {{ $booking->visa->visa_type ?? '-' }}</td>
                <td>{{ $booking->passport_number ?? '-' }}</td>
                <td>{{ $booking->visa_issue_date?->format('d M Y') ?? '-' }}</td>
                <td>{{ $booking->visa_expiry_date?->format('d M Y') ?? '-' }}</td>

                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                            id="statusDropdown{{ $booking->id }}" data-bs-toggle="dropdown">
                            {{ ucfirst($booking->status) }}
                        </button>
                        <ul class="dropdown-menu">
                            @foreach (['pending', 'approved', 'rejected'] as $status)
                                <li>
                                    <a class="dropdown-item change-status" href="#" data-id="{{ $booking->id }}"
                                        data-status="{{ $status }}">
                                        {{ ucfirst($status) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </td>

                <td class="text-center">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <!-- View -->
                        <a href="{{ route('admin.visa-bookings.show', $booking->id) }}" class="text-info"
                            style="text-decoration:none;">
                            <i class="bi bi-eye-fill fs-5"></i>
                        </a>

                        <!-- Edit -->
                        <a href="{{ route('admin.visa-bookings.edit', $booking->id) }}" class="text-primary"
                            style="text-decoration:none;">
                            <i class="bi bi-pencil-square fs-5"></i>
                        </a>

                        <!-- Delete -->
                        <button type="button" data-id="{{ $booking->id }}"
                            class="btn btn-link text-danger p-0 delete-booking">
                            <i class="bi bi-trash-fill fs-5"></i>
                        </button>
                    </div>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No visa bookings found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $bookings->links() }}
