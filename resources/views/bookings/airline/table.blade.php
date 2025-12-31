<table class="table table-striped table-hover align-middle">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Trip Type</th>
            <th>Agent</th>
            <th>Passenger / Customer</th>
            <th>From → To</th>
            <th>Airline / Flight</th>
            <th>Total</th>
            <th>Balance</th>
            <th>Payment</th>
            <th>Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse($bookings as $booking)
            @php
                $firstTrip = $booking->trips->first();
            @endphp
            <tr id="booking-{{ $booking->id }}">

                {{-- Invoice --}}
                <td class="fw-bold">{{ $booking->invoice_id }}</td>



                {{-- Trip Type --}}
                <td>
                    @php
                        $tripTypes = $booking->trips->pluck('trip_type')->unique();

                        if ($tripTypes->contains('round_trip')) {
                            $tripLabel = 'Round Trip';
                        } elseif ($tripTypes->contains('going') && $tripTypes->contains('return')) {
                            $tripLabel = 'Return Ticket';
                        } elseif ($tripTypes->contains('dummy')) {
                            $tripLabel = 'Return (Dummy)';
                        } elseif ($tripTypes->contains('one_way')) {
                            $tripLabel = 'One Way';
                        } else {
                            $tripLabel = '-';
                        }
                    @endphp

                    <span class="fw-semibold">{{ $tripLabel }}</span>
                </td>
                <td>
                    @if ($firstTrip && $firstTrip->agent)
                        {{ $firstTrip->agent->company_name }} - {{ $firstTrip->agent->name }}
                    @else
                        -
                    @endif
                </td>

                {{-- Passenger / Customer --}}
                <td>
                    @foreach ($booking->trips as $trip)
                        @if ($trip->passport)
                            {{ $trip->passport->first_name }} {{ $trip->passport->second_name }}<br>
                        @endif
                    @endforeach
                </td>
                {{-- Route From → To --}}
                {{-- Route From → To --}}
                <td>
                    @if ($booking->trips->count() > 0)
                        @foreach ($booking->trips as $trip)
                            {{ $trip->from_country ?? '-' }} → {{ $trip->to_country ?? '-' }}<br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>


                {{-- Airline / Flight --}}
                <td>{{ optional($firstTrip)->airline ?? '-' }}</td>

                {{-- Total --}}
                <td>{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</td>

                {{-- Balance --}}
                <td class="{{ $booking->balance > 0 ? 'text-danger' : 'text-success' }}">
                    {{ $booking->currency }} {{ number_format($booking->balance, 2) }}
                </td>

                {{-- Payment Status --}}
                <td>{{ ucfirst($booking->payment_status) }}</td>

                {{-- Booking Status --}}
                <td>
                    <div class="dropdown">
                        <button
                            class="btn btn-sm dropdown-toggle
                            @switch($booking->status)
                                @case('Quotation') btn-secondary @break
                                @case('Accepted') btn-primary @break
                                @case('Invoiced') btn-info @break
                                @case('Partially Paid') btn-warning @break
                                @case('Paid') btn-success @break
                                @case('Cancelled') btn-danger @break
                                @default btn-secondary
                            @endswitch"
                            type="button" id="statusDropdown{{ $booking->id }}" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                        </button>

                        <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $booking->id }}">
                            @foreach (['Quotation', 'Accepted', 'Invoiced', 'Partially Paid', 'Paid', 'Cancelled'] as $statusOption)
                                <li>
                                    <a class="dropdown-item change-status" href="#" data-id="{{ $booking->id }}"
                                        data-status="{{ $statusOption }}">
                                        {{ ucwords(str_replace('_', ' ', $statusOption)) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </td>

                {{-- Actions --}}
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.airline-bookings.show', $booking->id) }}"
                            class="text-info text-decoration-none">
                            <i class="bi bi-eye-fill fs-5"></i>
                        </a>
                        <a href="{{ route('admin.airline-bookings.edit', $booking->id) }}"
                            class="text-primary text-decoration-none">
                            <i class="bi bi-pencil-square fs-5"></i>
                        </a>
                        <button type="button" data-id="{{ $booking->id }}"
                            class="btn btn-link text-danger p-0 delete-booking">
                            <i class="bi bi-trash-fill fs-5"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center text-muted">
                    No airline bookings found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $bookings->links() }}
