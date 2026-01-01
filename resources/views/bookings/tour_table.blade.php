<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Booking Ref</th>
            <th>Agent</th>
            <th>Customer</th>
            <th>Nationality</th>
            <th>Visit Country</th>
            <th>Tour Category</th>
            <th>Package</th>
            <th>Travel Dates</th>
            <th>Passengers</th>
            <th>Payment Status</th>
            <th>Total Price</th>
            <th>Status</th> {{-- Status column --}}
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($bookings as $booking)
            <tr id="booking-{{ $booking->id }}">
                <td>{{ $booking->booking_ref_no }}</td>
                {{-- Agent Column --}}
                <td>
                    @if ($booking->agent)
                        {{ $booking->agent->name }} - {{ $booking->agent->company_name }}
                    @else
                        -
                    @endif

                </td>

                <td>{{ $booking->customer->name ?? '-' }}</td>

                <td>
                    {{ $booking->customer->country ?? '-' }}
                </td>

                <td>
                    {{ $booking->visit_country ?? '-' }}
                </td>
                <td>{{ $booking->package->tour_category ?? '-' }}</td>
                <td>{{ $booking->package->heading ?? '-' }}</td>
                <td>{{ $booking->travel_date }} to {{ $booking->travel_end_date }}</td>
                <td>{{ $booking->adults }}
                    Adult(s){{ $booking->children ? ', ' . $booking->children . ' Child(ren)' : '' }}{{ $booking->infants ? ', ' . $booking->infants . ' Infant(s)' : '' }}
                </td>
                <td>{{ ucfirst($booking->payment_status) }}</td>
                <td>{{ $booking->currency }} {{ number_format($booking->total_price, 2) }}</td>

                {{-- Status column --}}
                <td>
                    <div class="dropdown">
                        <button
                            class="btn btn-sm status-dropdown-btn
            @switch($booking->status)
                @case('quotation') btn-secondary @break
                @case('accepted') btn-primary @break
                @case('invoiced') btn-info @break
                @case('partially_paid') btn-warning @break
                @case('paid') btn-success @break
                @case('cancelled') btn-danger @break
                @default btn-secondary
            @endswitch
            dropdown-toggle"
                            type="button" id="statusDropdown{{ $booking->id }}" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $booking->id }}">
                            @foreach (['quotation', 'accepted', 'invoiced', 'partially_paid', 'paid', 'cancelled'] as $statusOption)
                                <li>
                                    <a class="dropdown-item change-status" href="#" data-id="{{ $booking->id }}"
                                        data-status="{{ $statusOption }}">
                                        {{ ucfirst(str_replace('_', ' ', $statusOption)) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </td>

                {{-- Action column --}}
                <td class="text-center">
                    {{-- View --}}
                    <a href="{{ route('admin.tour-bookings.show', $booking->id) }}" class="icon-btn text-info"
                        title="View Booking">
                        <i class="bi bi-eye-fill fs-5"></i>
                    </a>
                    {{-- Edit --}}
                    <a href="{{ route('admin.tour-bookings.edit', $booking->id) }}" class="icon-btn text-primary"
                        title="Edit Booking">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </a>
                    {{-- Delete --}}
                    <button type="button" data-id="{{ $booking->id }}" class="icon-btn text-danger delete-booking"
                        title="Delete Booking">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" class="text-center">No bookings found.</td>
            </tr>
        @endforelse
    </tbody>
</table>


{{ $bookings->links() }}
