<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Customer</th>
            <th>Agent</th> <!-- New Column -->
            <th>Vehicle</th>
            <th>Vehicle Number</th> <!-- New Column -->
            <th>Pickup Location</th>
            <th>Dropoff Location</th>
            <th>Total KM</th>
            <th>Price</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse($bookings as $booking)
            <tr id="booking-{{ $booking->id }}">

                {{-- Invoice No --}}
                <td>{{ $booking->inv_no }}</td>

                {{-- Customer --}}
                <td>{{ $booking->customer->name ?? ($booking->customer_name ?? '-') }}</td>

                {{-- Agent / Owner --}}
                <td>{{ $booking->vehicle->agent->name ?? '-' }} - {{ $booking->vehicle->agent->company_name ?? '-' }}</td>

                {{-- Vehicle --}}
                <td>
                    {{ $booking->vehicle->name ?? ($booking->vehicle_model ?? '-') }}
                    @if (!empty($booking->vehicle_type))
                        <br><small class="text-muted">{{ ucfirst($booking->vehicle_type) }}</small>
                    @endif
                </td>

                {{-- Vehicle Number --}}
                <td>{{ $booking->vehicle->vehicle_number ?? '-' }}</td>

                {{-- Pickup Location --}}
                <td>{{ $booking->pickup_location }} <br>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($booking->pickup_datetime)->format('d M Y, h:i A') }}</small>
                </td>

                {{-- Dropoff Location --}}
                <td>{{ $booking->dropoff_location }} <br>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($booking->dropoff_datetime)->format('d M Y, h:i A') }}</small>
                </td>

                {{-- Total KM --}}
                <td>
                    @if ($booking->mileage == 'unlimited')
                        Unlimited
                    @else
                        {{ $booking->total_km }} km
                    @endif
                </td>

                {{-- Price --}}
                <td>{{ $booking->currency ?? 'LKR' }} {{ number_format($booking->total_price, 2) }}</td>

                {{-- Status Dropdown --}}
                <td>
                    <div class="dropdown">
                        <button
                            class="btn btn-sm status-dropdown-btn
        @switch($booking->status)
            @case('Quotation') btn-secondary @break
            @case('Accepted') btn-primary @break
            @case('Invoiced') btn-info @break
            @case('Partially Paid') btn-warning @break
            @case('Paid') btn-success @break
            @case('Cancelled') btn-danger @break
            @default btn-secondary
        @endswitch
        dropdown-toggle"
                            type="button" id="statusDropdown{{ $booking->id }}" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            {{ $booking->status }}
                        </button>

                        <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $booking->id }}">
                            @foreach (['Quotation', 'Accepted', 'Invoiced', 'Partially Paid', 'Paid', 'Cancelled'] as $statusOption)
                                <li>
                                    <a class="dropdown-item change-status" href="#" data-id="{{ $booking->id }}"
                                        data-status="{{ $statusOption }}">
                                        {{ $statusOption }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </td>

                {{-- Actions --}}
                <td class="text-center">
                    <a href="{{ route('admin.vehicle-bookings.show', $booking->id) }}" class="icon-btn text-info"
                        title="View Booking">
                        <i class="bi bi-eye-fill fs-5"></i>
                    </a>

                    <a href="{{ route('admin.vehicle-bookings.edit', $booking->id) }}" class="icon-btn text-primary"
                        title="Edit Booking">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </a>

                    <button type="button" data-id="{{ $booking->id }}" class="icon-btn text-danger delete-booking"
                        title="Delete Booking">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" class="text-center">No vehicle bookings found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $bookings->links() }}
