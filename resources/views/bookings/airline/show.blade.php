@extends('layouts.vertical', ['subtitle' => 'View Airline Booking'])

@section('content')
<div class="card">

    {{-- Header with Back & PDF buttons --}}
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Airline Booking Details</h5>
        <div>
            <a href="{{ route('admin.airline-bookings.index') }}" class="btn btn-light me-2" style="width: 130px;">Back</a>
            <a class="btn btn-primary" onclick="generatePdf()" style="width: 130px;">Generate PDF</a>
        </div>
    </div>

    <div class="card-body">

        {{-- Invoice content --}}
        <div id="invoiceContent">
            <div style="max-width:800px;margin:0 auto;font-family:'Helvetica Neue',Arial,sans-serif;color:#333;background:#fff;padding:25px;">

                {{-- HEADER --}}
                <table style="width:100%;border-bottom:2px solid #333;margin-bottom:30px;">
                    <tr>
                        <td>
                            <img src="{{ asset('images/vacayguider.png') }}" style="height:80px;">
                            <div style="font-size:12px;color:#666;margin-top:10px;line-height:1.4;">
                                <strong>Vacay Guider (Pvt) Ltd.</strong><br>
                                Negombo, Sri Lanka<br>
                                +94 114 272 372 | info@vacayguider.com
                            </div>
                        </td>
                        <td style="text-align:right;">
                            <h1 style="margin:0;font-size:24px;font-weight:300;letter-spacing:2px;">
                                {{ strtoupper(str_replace('_',' ', $airline_booking->status)) }}
                            </h1>
                            <table style="margin-left:auto;margin-top:10px;font-size:13px;">
                                <tr>
                                    <td style="color:#888;padding:2px 10px;">Booking Date</td>
                                    <td>{{ $airline_booking->created_at->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#888;padding:2px 10px;">Payment Status</td>
                                    <td>{{ ucfirst($airline_booking->payment_status) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                {{-- CUSTOMER / FLIGHT --}}
                <table style="width:100%;margin-bottom:35px;font-size:13px;">
                    <tr>
                        <td style="width:50%;vertical-align:top;">
                            <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">
                                Passenger / Customer Details
                            </h4>
                            <div style="font-size:15px;font-weight:bold;">
                                {{ $airline_booking->customer->name }}
                            </div>
                            {{-- <div style="font-size:14px;color:#555;">
                                {{ $airline_booking->agent->name ?? '—' }}
                            </div> --}}
                        </td>

                        <td style="width:50%;vertical-align:top;border-left:1px solid #eee;padding-left:25px;">
                            <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">
                                Flight Details
                            </h4>
                            <div><strong>Route:</strong> {{ $airline_booking->from_country }} - {{ $airline_booking->to_country }}</div>
                            <div><strong>Airline / Flight:</strong> {{ $airline_booking->airline ?? '-' }}</div>
                            <div><strong>Departure:</strong> {{ $airline_booking->departure_datetime->format('d/m/Y H:i') }}</div>
                            <div><strong>Arrival:</strong> {{ $airline_booking->arrival_datetime->format('d/m/Y H:i') }}</div>
                        </td>
                    </tr>
                </table>

                {{-- PRICE TABLE --}}
                <table style="width:100%;border-collapse:collapse;font-size:14px;margin-bottom:30px;">
                    <thead>
                        <tr style="background:#f9f9f9;border-top:1px solid #333;border-bottom:1px solid #333;">
                            <th style="padding:12px;text-align:left;font-size:11px;text-transform:uppercase;">Description</th>
                            <th style="padding:12px;text-align:right;font-size:11px;text-transform:uppercase;">Amount ({{ $airline_booking->currency }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding:14px;border-bottom:1px solid #eee;">Base Price</td>
                            <td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">{{ number_format($airline_booking->base_price, 2) }}</td>
                        </tr>

                        @if($airline_booking->additional_price > 0)
                            <tr>
                                <td style="padding:14px;border-bottom:1px solid #eee;">Additional Price</td>
                                <td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">{{ number_format($airline_booking->additional_price, 2) }}</td>
                            </tr>
                        @endif

                        @if($airline_booking->discount > 0)
                            <tr>
                                <td style="padding:14px;border-bottom:1px solid #eee;color:#888;">Discount</td>
                                <td style="padding:14px;text-align:right;border-bottom:1px solid #eee;color:#888;">({{ number_format($airline_booking->discount, 2) }})</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                {{-- TOTAL --}}
                <div style="width:40%;margin-left:auto;">
                    <table style="width:100%;font-size:14px;">
                        <tr>
                            <td style="padding:8px 0;color:#888;">Total</td>
                            <td style="padding:8px 0;text-align:right;">{{ number_format($airline_booking->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#198754;">Advanced Paid</td>
                            <td style="padding:8px 0;text-align:right;color:#198754;">{{ number_format($airline_booking->advanced_paid, 2) }}</td>
                        </tr>
                        <tr style="border-top:1px solid #333;">
                            <td style="padding:12px 0;font-weight:bold;font-size:16px;">Balance</td>
                            <td style="padding:12px 0;text-align:right;font-weight:bold;font-size:18px;">{{ $airline_booking->currency }} {{ number_format($airline_booking->balance, 2) }}</td>
                        </tr>
                    </table>
                </div>

                {{-- FOOTER --}}
                <div style="margin-top:60px;text-align:center;border-top:1px solid #eee;padding-top:20px;font-size:11px;color:#aaa;">
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
    const htmlContent = document.querySelector('#invoiceContent').innerHTML;

    fetch("{{ route('admin.airline-bookings.generatePdf', $airline_booking->id) }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ html: htmlContent })
    })
    .then(response => {
        if (!response.ok) throw new Error("PDF generation failed");
        return response.blob();
    })
    .then(blob => {
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'Airline_Booking_Invoice.pdf';
        link.click();
        URL.revokeObjectURL(link.href);
    })
    .catch(error => {
        console.error("Error generating PDF:", error);
        alert("Failed to generate PDF. Please try again.");
    });
}
</script>
@endsection
