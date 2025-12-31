@extends('layouts.vertical', ['subtitle' => 'View Tour Booking'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Tour Booking',
        'subtitle' => 'View',
    ])

    <div class="card">
        <div class="card-body">
            <div id="invoiceContent"
                style="max-width:900px; margin:0 auto; font-family:'Helvetica Neue', Helvetica, Arial, sans-serif; color:#333; background:#fff; padding:20px;">

                {{-- HEADER --}}
                <table style="width:100%; border-bottom:2px solid #333; padding-bottom:20px; margin-bottom:30px;">
                    <tr>
                        <td style="vertical-align: top;">
                            <img src="{{ asset('images/vacayguider.png') }}" alt="Logo" style="height:80px;">
                            <div style="margin-top:15px; font-size:12px; line-height:1.4; color:#666;">
                                <strong>Vacay Guider (Pvt) Ltd.</strong><br>
                                22/14 C Asarappa Rd, Negombo 11400<br>
                                +94 114 272 372 | +94 711 999 444 |  +94 777 035 325 <br>
                                    info@vacayguider.com
                            </div>
                        </td>
                        <td style="text-align:right; vertical-align: top;">
                            @php
                                $statusColors = [
                                    'quotation' => 'secondary',
                                    'accepted' => 'primary',
                                    'invoiced' => 'info',
                                    'partially_paid' => 'warning',
                                    'paid' => 'success',
                                    'cancelled' => 'danger',
                                ];
                                $badgeClass = $statusColors[$booking->status] ?? 'secondary';
                            @endphp
                            <h1
                                style="margin:0; font-size:24px; font-weight:300; letter-spacing:2px; text-transform:uppercase;">
                                {{ strtoupper($booking->status) }}
                            </h1>
                            <table style="margin-left:auto; margin-top:10px; font-size:13px; border-collapse:collapse;">
                                <tr>
                                    <td style="padding:2px 10px; text-align:left; color:#888;">Ref:</td>
                                    <td style="padding:2px 10px; font-weight:bold;">
                                        {{ $booking->booking_ref_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:2px 10px; text-align:left; color:#888;">Date:</td>
                                    <td style="padding:2px 10px;">
                                        {{ $booking->invoice_date?->format('d/m/Y') ?? $booking->created_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:2px 10px; text-align:left; color:#888;">Currency:</td>
                                    <td style="padding:2px 10px;">{{ $booking->currency }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                {{-- CLIENT & TOUR INFO --}}
                <table style="width:100%; margin-bottom:40px; font-size:13px;">
                    <tr>
                        <td style="width:50%; vertical-align:top;">
                            <h4
                                style="text-transform:uppercase; font-size:11px; color:#888; margin-bottom:10px; letter-spacing:1px;">
                                Client Information
                            </h4>
                            <div style="font-size:15px; font-weight:bold; margin-bottom:5px;">
                                {{ $booking->customer->name ?? 'N/A' }}
                            </div>
                            <div style="color:#555;">{{ $booking->customer->email ?? 'N/A' }}</div>
                            <div style="color:#555;">{{ $booking->customer->contact ?? 'N/A' }}</div>
                        </td>
                        <td style="width:50%; vertical-align:top; border-left:1px solid #eee; padding-left:30px;">
                            <h4
                                style="text-transform:uppercase; font-size:11px; color:#888; margin-bottom:10px; letter-spacing:1px;">
                                Tour Information
                            </h4>
                            <div style="margin-bottom:3px;"><strong>Package:</strong>
                                {{ $booking->package->heading ?? 'N/A' }}</div>
                            <div style="margin-bottom:3px;"><strong>Reference:</strong>
                                {{ $booking->package->tour_ref_no ?? 'N/A' }}</div>
                            <div style="margin-bottom:3px;">
                                <strong>Duration:</strong> {{ $booking->travel_date?->format('d M Y') ?? 'N/A' }} to
                                {{ $booking->travel_end_date?->format('d M Y') ?? 'N/A' }}
                            </div>
                            <div style="margin-bottom:3px;">
                                <strong>Pax:</strong> {{ $booking->adults }} Adults, {{ $booking->children }} Children,
                                {{ $booking->infants }} Infants
                            </div>
                        </td>
                    </tr>
                </table>

                {{-- PRICE TABLE --}}
                <table style="width:100%; border-collapse:collapse; margin-bottom:30px; font-size:14px;">
                    <thead>
                        <tr style="background:#f9f9f9; border-top:1px solid #333; border-bottom:1px solid #333;">
                            <th style="padding:12px; text-align:left; text-transform:uppercase; font-size:11px;">Description
                            </th>
                            <th style="padding:12px; text-align:right; text-transform:uppercase; font-size:11px;">Total
                                ({{ $booking->currency }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding:15px 12px; border-bottom:1px solid #eee;">
                                <strong>Travel Package Arrangement</strong><br>
                                <small style="color:#888;">Comprehensive tour package including accommodation and
                                    transport.</small>
                            </td>
                            <td style="padding:15px 12px; text-align:right; border-bottom:1px solid #eee;">
                                {{ number_format($booking->package_price, 2) }}
                            </td>
                        </tr>
                        @if ($booking->tax > 0)
                            <tr>
                                <td style="padding:12px; border-bottom:1px solid #eee;">Additional Services / Charges</td>
                                <td style="padding:12px; text-align:right; border-bottom:1px solid #eee;">
                                    {{ number_format($booking->tax, 2) }}</td>
                            </tr>
                        @endif
                        @if ($booking->discount > 0)
                            <tr>
                                <td style="padding:12px; border-bottom:1px solid #eee; color:#888; font-style:italic;">
                                    Discount Applied</td>
                                <td style="padding:12px; text-align:right; border-bottom:1px solid #eee; color:#888;">
                                    ({{ number_format($booking->discount, 2) }})</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                {{-- TOTAL / ADVANCE / BALANCE --}}
                @php
                    $advancePaid = $booking->advance_paid ?? 0;
                    $balance = $booking->total_price - $advancePaid;
                @endphp
                <div style="width:40%; margin-left:auto;">
                    <table style="width:100%; font-size:14px; border-collapse:collapse;">
                        <tr>
                            <td style="padding:8px 0; color:#888;">Subtotal:</td>
                            <td style="padding:8px 0; text-align:right;">{{ number_format($booking->total_price, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0; color:#888;">Advance Paid:</td>
                            <td style="padding:8px 0; text-align:right; color:#1a7f37;">{{ number_format($advancePaid, 2) }}
                            </td>
                        </tr>
                        <tr style="border-top:1px solid #333;">
                            <td style="padding:12px 0; font-weight:bold; font-size:16px;">Balance Due:</td>
                            <td style="padding:12px 0; text-align:right; font-weight:bold; font-size:18px; color:#000;">
                                {{ $booking->currency }} {{ number_format($balance, 2) }}
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- SPECIAL REQUIREMENTS --}}
                @if ($booking->special_requirements)
                    <div style="margin-top:50px; border-top:1px solid #eee; padding-top:20px;">
                        <h4 style="font-size:11px; text-transform:uppercase; color:#888; margin-bottom:10px;">Terms & Notes
                        </h4>
                        <div style="font-size:12px; color:#666; line-height:1.6; white-space: pre-wrap;">
                            {{ $booking->special_requirements }}
                        </div>
                    </div>
                @endif

                {{-- FOOTER --}}
                <div
                    style="margin-top:60px; text-align:center; border-top:1px solid #eee; padding-top:20px; font-size:11px; color:#aaa;">
                    <p style="margin-bottom:5px;">This is a computer-generated document. No signature is required.</p>
                    <p><strong>Vacay Guider</strong> | www.vacayguider.com | Thank you for your business.</p>
                </div>
            </div>

        </div>

        {{-- ACTION BUTTONS --}}
        <div class="d-flex justify-content-end align-items-center gap-2 mt-4  p-4">
            <a href="{{ route('admin.tour-bookings.index') }}" class="btn btn-secondary" style="width:120px;">Back</a>
            <button type="button" class="btn btn-primary" style="width:150px;" onclick="generatePdf()">Generate
                PDF</button>
        </div>
    </div>

    <script>
        function generatePdf() {
            const htmlContent = document.querySelector('#invoiceContent').innerHTML;

            fetch("{{ route('admin.tour-quotations.generatePdf') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        html: htmlContent
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error("PDF generation failed");
                    return response.blob();
                })
                .then(blob => {
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'Tour_Booking_Invoice.pdf';
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
