@extends('layouts.vertical', ['subtitle' => 'View Airline Booking'])

@section('content')
    <div class="card">

        {{-- Header with Back & PDF buttons --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Airline Booking Details - {{ $airline_booking->invoice_id }}</h5>
            <div>
                <a href="{{ route('admin.airline-bookings.index') }}" class="btn btn-light me-2" style="width: 130px;">Back</a>
                <a href="{{ route('admin.airline-bookings.edit', $airline_booking->id) }}" class="btn btn-warning me-2"
                    style="width: 130px;">Edit</a>
                <a class="btn btn-primary" onclick="generatePdf()" style="width: 130px;">Generate PDF</a>
            </div>
        </div>

        <div class="card-body">

            {{-- Invoice content --}}
            <div id="invoiceContent">
                <div
                    style="max-width:800px;margin:0 auto;font-family:'Helvetica Neue',Arial,sans-serif;color:#333;background:#fff;padding:25px;">

                    {{-- HEADER --}}
                    <table style="width:100%;border-bottom:2px solid #333;margin-bottom:30px;">
                        <tr>
                            <td style="width:50%;">
                                <img src="{{ asset('images/vacayguider.png') }}" style="height:80px;">
                                <div style="font-size:12px;color:#666;margin-top:10px;line-height:1.4;">
                                    <strong>Vacay Guider (Pvt) Ltd.</strong><br>
                                    Negombo, Sri Lanka<br>
                                    +94 114 272 372 | info@vacayguider.com
                                </div>
                            </td>
                            <td style="width:50%;text-align:right;vertical-align:top;">
                                <h1 style="margin:0;font-size:24px;font-weight:300;letter-spacing:2px;">
                                    {{ strtoupper(str_replace('_', ' ', $airline_booking->status)) }}
                                </h1>
                                <table style="margin-left:auto;margin-top:10px;font-size:13px;">
                                    <tr>
                                        <td style="color:#888;padding:2px 10px;text-align:left;">Invoice No:</td>
                                        <td style="text-align:left;">{{ $airline_booking->invoice_id }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color:#888;padding:2px 10px;text-align:left;">Booking Date:</td>
                                        <td style="text-align:left;">{{ $airline_booking->created_at->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color:#888;padding:2px 10px;text-align:left;">Payment Status:</td>
                                        <td style="text-align:left;">{{ ucfirst($airline_booking->payment_status) }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    {{-- BUSINESS INFO & PASSENGERS --}}
                    <table style="width:100%;margin-bottom:35px;font-size:13px;">
                        <tr>
                            <td style="width:50%;vertical-align:top;">
                                <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">
                                    Business Information
                                </h4>
                                <div style="font-size:14px;">
                                    <strong>Type:</strong> {{ ucfirst($airline_booking->business_type) }}<br>
                                    @if ($airline_booking->business_type == 'corporate' && $airline_booking->company_name)
                                        <strong>Company:</strong> {{ $airline_booking->company_name }}<br>
                                    @endif
                                    <strong>Ticket Type:</strong>
                                    {{ ucfirst(str_replace('_', ' ', $airline_booking->ticket_type)) }}
                                    @if ($airline_booking->ticket_type == 'return' && $airline_booking->return_type)
                                        ({{ ucfirst(str_replace('_', ' ', $airline_booking->return_type)) }})
                                    @endif
                                </div>
                            </td>

                            <td style="width:50%;vertical-align:top;border-left:1px solid #eee;padding-left:25px;">
                                <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">
                                    Passenger(s)
                                </h4>
                                <div style="font-size:15px;font-weight:bold;">
                                    @php
                                        $uniquePassengers = $airline_booking->trips->unique('passport_id');
                                    @endphp
                                    @if ($uniquePassengers->count() > 0)
                                        @foreach ($uniquePassengers as $trip)
                                            @if ($trip->passport)
                                                {{ $trip->passport->first_name }} {{ $trip->passport->second_name }}<br>
                                            @endif
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </table>

                    {{-- TRIP DETAILS SECTION --}}
                    <div style="margin-bottom:35px;">
                        <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">
                            Flight Details
                        </h4>

                        @if ($airline_booking->trips->count() > 0)
                            @php
                                $tripChunks = $airline_booking->trips->chunk(2);
                            @endphp

                            @foreach ($tripChunks as $chunk)
                                <table style="width:100%;margin-bottom:15px;">
                                    <tr>
                                        @foreach ($chunk as $trip)
                                            <td
                                                style="width:{{ $chunk->count() == 1 ? '100' : '50' }}%;vertical-align:top;{{ $loop->first ? '' : 'padding-left:15px;border-left:1px solid #eee;' }}">
                                                <div
                                                    style="margin-bottom:12px; padding-bottom:8px; border-bottom:1px dashed #eee;">
                                                    <div><strong>Trip Type:</strong>
                                                        @switch($trip->trip_type)
                                                            @case('one_way')
                                                                One Way
                                                            @break

                                                            @case('dummy')
                                                                Return (Dummy)
                                                            @break

                                                            @case('going')
                                                                Return Ticket (Going)
                                                            @break

                                                            @case('return')
                                                                Return Ticket (Return)
                                                            @break

                                                            @case('round_trip')
                                                                Round Trip
                                                            @break

                                                            @default
                                                                {{ ucfirst($trip->trip_type) }}
                                                            @break
                                                        @endswitch
                                                    </div>

                                                    <div><strong>Passenger:</strong>
                                                        @if ($trip->passport)
                                                            {{ $trip->passport->first_name }}
                                                            {{ $trip->passport->second_name }}
                                                        @else
                                                            -
                                                        @endif
                                                        ({{ $trip->passport_no ?? '-' }})
                                                    </div>

                                                    <div><strong>Agent:</strong> {{ optional($trip->agent)->name ?? '-' }}
                                                    </div>

                                                    <div><strong>Route:</strong> {{ $trip->from_country ?? '-' }} →
                                                        {{ $trip->to_country ?? '-' }}</div>

                                                    <div><strong>Airline / Flight:</strong> {{ $trip->airline ?? '-' }} /
                                                        {{ $trip->airline_no ?? '-' }}</div>

                                                    <div><strong>PNR:</strong> {{ $trip->pnr ?? '-' }}</div>

                                                    <div><strong>Departure:</strong>
                                                        {{ $trip->departure_datetime ? \Carbon\Carbon::parse($trip->departure_datetime)->format('d/m/Y H:i') : '-' }}
                                                    </div>

                                                    <div><strong>Arrival:</strong>
                                                        {{ $trip->arrival_datetime ? \Carbon\Carbon::parse($trip->arrival_datetime)->format('d/m/Y H:i') : '-' }}
                                                    </div>

                                                    <div><strong>Baggage:</strong> {{ $trip->baggage_qty ?? 0 }} pcs |
                                                        <strong>Hand Luggage:</strong> {{ $trip->handluggage_qty ?? 0 }}
                                                        pcs
                                                    </div>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                </table>
                            @endforeach
                        @else
                            <div style="color:#888;">No trip information available</div>
                        @endif
                    </div>

                    {{-- PRICE TABLE --}}
                    <table style="width:100%;border-collapse:collapse;font-size:14px;margin-bottom:30px;">
                        <thead>
                            <tr style="background:#f9f9f9;border-top:1px solid #333;border-bottom:1px solid #333;">
                                <th style="padding:12px;text-align:left;font-size:11px;text-transform:uppercase;">
                                    Description</th>
                                <th style="padding:12px;text-align:right;font-size:11px;text-transform:uppercase;">Amount
                                    ({{ $airline_booking->currency }})</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding:14px;border-bottom:1px solid #eee;">Base Price</td>
                                <td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">
                                    {{ number_format($airline_booking->base_price, 2) }}</td>
                            </tr>

                            @if ($airline_booking->additional_price > 0)
                                <tr>
                                    <td style="padding:14px;border-bottom:1px solid #eee;">Additional Price</td>
                                    <td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">
                                        {{ number_format($airline_booking->additional_price, 2) }}</td>
                                </tr>
                            @endif

                            @if ($airline_booking->discount > 0)
                                <tr>
                                    <td style="padding:14px;border-bottom:1px solid #eee;color:#888;">Discount</td>
                                    <td style="padding:14px;text-align:right;border-bottom:1px solid #eee;color:#888;">
                                        ({{ number_format($airline_booking->discount, 2) }})</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    {{-- TOTAL --}}
                    <div style="width:40%;margin-left:auto;">
                        <table style="width:100%;font-size:14px;">
                            <tr>
                                <td style="padding:8px 0;color:#888;">Total</td>
                                <td style="padding:8px 0;text-align:right;">
                                    {{ number_format($airline_booking->total_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0;color:#198754;">Advanced Paid</td>
                                <td style="padding:8px 0;text-align:right;color:#198754;">
                                    {{ number_format($airline_booking->advanced_paid, 2) }}</td>
                            </tr>
                            <tr style="border-top:1px solid #333;">
                                <td style="padding:12px 0;font-weight:bold;font-size:16px;">Balance</td>
                                <td style="padding:12px 0;text-align:right;font-weight:bold;font-size:18px;">
                                    {{ $airline_booking->currency }} {{ number_format($airline_booking->balance, 2) }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    {{-- FOOTER --}}
                    <div
                        style="margin-top:60px;text-align:center;border-top:1px solid #eee;padding-top:20px;font-size:11px;color:#aaa;">
                        This is a system generated invoice. No signature required.<br>
                        <strong>Vacay Guider</strong> | www.vacayguider.com
                    </div>

                </div>
            </div>
            {{-- End invoiceContent --}}

        </div>
    </div>

    {{-- PDF Generation Script --}}
    <script>
        function generatePdf() {
            const invoiceElement = document.getElementById('invoiceContent');

            // Show loading state
            const button = event.target;
            const originalText = button.innerText;
            button.innerText = 'Generating...';
            button.disabled = true;

            fetch("{{ route('admin.airline-bookings.generatePdf', $airline_booking->id) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        html: invoiceElement.innerHTML
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("PDF generation failed");
                    }
                    return response.blob();
                })
                .then(blob => {
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'Airline_Booking_{{ $airline_booking->invoice_id }}.pdf';
                    link.click();
                    URL.revokeObjectURL(link.href);

                    // Reset button
                    button.innerText = originalText;
                    button.disabled = false;
                })
                .catch(error => {
                    console.error("Error generating PDF:", error);
                    alert("Failed to generate PDF. Please try again.");

                    // Reset button
                    button.innerText = originalText;
                    button.disabled = false;
                });
        }
    </script>
@endsection
