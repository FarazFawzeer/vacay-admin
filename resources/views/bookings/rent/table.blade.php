<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Customer</th>
            <th>Vehicle</th>
            <th>Start</th>
            <th>End</th>
            <th>Total Price</th>
            <th>Payment</th>
            <th>Created By</th>
            <th>Status</th>

            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse($bookings as $booking)
            <tr id="booking-{{ $booking->id }}">

                <td>{{ $booking->inv_no }}</td>

                <td>{{ $booking->customer->name ?? '-' }}</td>

                <td>{{ $booking->vehicle->name ?? '-' }}</td>

                <td>
                    {{ $booking->start_datetime?->format('d M Y h:i A') ?? '-' }}
                </td>

                <td>
                    {{ $booking->end_datetime?->format('d M Y h:i A') ?? '-' }}
                </td>

                <td>{{ $booking->currency }} {{ number_format($booking->total_price, 2) }}</td>
                <td>{{ ucfirst($booking->payment_status) }}</td>
                <td> {{ $booking->created_by ?? '-' }} - {{ $booking->creator->name ?? '-' }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                            id="statusDropdown{{ $booking->id }}" data-bs-toggle="dropdown">
                            {{ ucfirst($booking->status) }}
                        </button>

                        @php
                            $statuses = [
                                'quotation' => ['label' => 'Quotation', 'color' => 'secondary'],
                                'accepted' => ['label' => 'Accepted', 'color' => 'info'],
                                'invoiced' => ['label' => 'Invoiced', 'color' => 'primary'],
                                'partially_paid' => ['label' => 'Partially Paid', 'color' => 'warning'],
                                'paid' => ['label' => 'Paid', 'color' => 'success'],
                                'cancelled' => ['label' => 'Cancelled', 'color' => 'danger'],
                            ];
                        @endphp

                        <ul class="dropdown-menu">
                            @foreach ($statuses as $value => $status)
                                <li>
                                    <a class="dropdown-item change-status" href="#" data-id="{{ $booking->id }}"
                                        data-status="{{ $value }}">
                                        {{ $status['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </td>




                <td class="text-center">

                    <a href="{{ route('admin.rent-vehicle-bookings.show', $booking->id) }}" class="icon-btn text-info">
                        <i class="bi bi-eye-fill fs-5"></i>
                    </a>

                    <a href="{{ route('admin.rent-vehicle-bookings.edit', $booking->id) }}"
                        class="icon-btn text-primary">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </a>

                    <button data-id="{{ $booking->id }}" class="icon-btn text-danger delete-booking">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>

                </td>

            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No rent vehicle bookings found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $bookings->links() }}
