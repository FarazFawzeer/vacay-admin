<table class="table table-striped table-hover align-middle">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Passport Holder</th>
            <th>Passport No</th>
            <th>Visa</th>
            <th>Agent</th>
            <th>Total</th>
            <th>Balance</th>
            <th>Status</th>
            <th>Payment</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse($bookings as $booking)
            <tr id="booking-{{ $booking->id }}">

                {{-- Invoice --}}
                <td class="fw-bold">{{ $booking->invoice_no }}</td>

                {{-- Passport Holder --}}
                <td>
                    {{ $booking->passport->first_name ?? '-' }}
                    {{ $booking->passport->second_name ?? '' }}
                </td>

                {{-- Passport Number --}}
                <td>{{ $booking->passport->passport_number ?? '-' }}</td>

                {{-- Visa --}}
                <td>
                    {{ $booking->visa->from_country ?? '-' }}
                    →
                    {{ $booking->visa->to_country ?? '-' }} <br>
                    <small class="text-muted">{{ $booking->visa->visa_type ?? '-' }}</small>
                </td>

                {{-- Agent --}}
                <td>
                    {{ $booking->agent?->name ?? '-' }} - {{ $booking->agent?->company_name ?? '-' }}
                </td>


                {{-- Total --}}
                <td>
                    {{ $booking->currency }}
                    {{ number_format($booking->total_amount, 2) }}
                </td>

                {{-- Balance --}}
                <td class="{{ $booking->balance > 0 ? 'text-danger' : 'text-success' }}">
                    {{ $booking->currency }}
                    {{ number_format($booking->balance, 2) }}
                </td>

                {{-- Booking Status --}}
                {{-- Status --}}
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

                {{-- Payment Status --}}
                <td>
                    <span
                        class="badge bg-{{ $booking->payment_status === 'paid' ? 'success' : ($booking->payment_status === 'partial' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($booking->payment_status) }}
                    </span>
                </td>


                {{-- Actions --}}
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">

                        <a href="{{ route('admin.visa-bookings.show', $booking->id) }}"
                            class="text-info text-decoration-none">
                            <i class="bi bi-eye-fill fs-5"></i>
                        </a>

                        <a href="{{ route('admin.visa-bookings.edit', $booking->id) }}"
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
                    No visa bookings found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $bookings->links() }}
