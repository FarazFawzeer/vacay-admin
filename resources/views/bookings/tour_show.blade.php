@extends('layouts.vertical', ['subtitle' => 'View Tour Booking'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Tour Booking',
        'subtitle' => 'View',
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Tour Booking Details</h5>
        </div>
        <div class="card-body">
            <div id="invoiceContent"
                style="max-width:900px; margin:0 auto; font-family:'Segoe UI', sans-serif; background:#fff; box-shadow:0 0 20px rgba(0,0,0,0.1);">

                {{-- HEADER SECTION --}}
                <div style="padding:40px; border-bottom:2px solid #e0e0e0;">
                    <div style="text-align:center; margin-bottom:30px;">
                        <img src="{{ asset('images/vacayguider.png') }}" alt="Company Logo"
                            style="height:120px; object-fit:contain;">
                    </div>

                    <table style="width:100%; border:none; margin-top:10px;">
                        <tr>
                            <td style="vertical-align:top; width:60%;">
                                <h4 style="margin:0 0 12px 0; font-size:15px; font-weight:600; color:#2c3e50;">
                                    COMPANY DETAILS</h4>
                                <p style="margin:5px 0; font-size:14px;"><strong>Name:</strong> Vacay Guider</p>
                                <p style="margin:5px 0; font-size:14px;"><strong>Address:</strong> 123 Business Street</p>
                                <p style="margin:5px 0; font-size:14px;"><strong>Phone:</strong> +94 114 272 372</p>
                                <p style="margin:5px 0; font-size:14px;"><strong>Email:</strong> info@vacayguider.com</p>
                            </td>
                            <td style="vertical-align:top; text-align:right; width:40%;">
                                @php
                                    // Map status to badge color
                                    $statusColors = [
                                        'quotation' => 'secondary', // gray
                                        'invoiced' => 'primary', // blue
                                        'confirmed' => 'success', // green
                                        'completed' => 'info', // teal
                                        'cancelled' => 'danger', // red
                                    ];

                                    $badgeClass = $statusColors[$booking->status] ?? 'secondary';
                                @endphp

                                <div style="margin-bottom:5px;">
                                    <h2 class="badge bg-{{ $badgeClass }}"
                                        style="display:inline-block; margin:0; font-size:14px; border-radius:4px; font-weight:700; padding:3px 6px;">
                                        {{ strtoupper($booking->status) }}
                                    </h2>
                                </div>
                                <p style="margin:2px 0; font-size:13px;">
                                    <strong>Number:</strong> {{ $booking->invoice_number ?? $booking->id }}
                                </p>
                                <p style="margin:2px 0; font-size:13px;">
                                    {{ $booking->invoice_date ? $booking->invoice_date->format('d/m/Y') : $booking->created_at->format('d/m/Y') }}
                                </p>
                            </td>
                        </tr>
                    </table>

                    {{-- CUSTOMER DETAILS --}}
                    <div style="margin-top:20px;">
                        <h4 style="margin:0 0 12px 0; font-size:15px; font-weight:600; color:#2c3e50;">CUSTOMER DETAILS</h4>
                        <p style="margin:5px 0; font-size:14px;"><strong>Name:</strong>
                            {{ $booking->customer->name ?? 'N/A' }}</p>
                        <p style="margin:5px 0; font-size:14px;"><strong>Email:</strong>
                            {{ $booking->customer->email ?? 'N/A' }}</p>
                        <p style="margin:5px 0; font-size:14px;"><strong>Contact:</strong>
                            {{ $booking->customer->contact ?? 'N/A' }}</p>
                    </div>
                </div>

                {{-- PACKAGE DETAILS --}}
                <div style="padding:40px;">
                    <h3 style="margin:0 0 15px 0; font-size:17px; font-weight:600; color:#2c3e50;">
                        Package Details
                    </h3>

                    <div style="background:#f8f9fa; padding:20px; border-radius:6px; margin-bottom:15px;">
                        <table style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td style="width:50%; vertical-align:top; padding-right:15px;">
                                    <p style="margin:8px 0; font-size:14px; color:#333;">
                                        <strong>Tour Package:</strong> {{ $booking->package->heading ?? 'N/A' }}
                                    </p>
                                    <p style="margin:8px 0; font-size:14px; color:#333;">
                                        <strong>Reference No:</strong> {{ $booking->package->tour_ref_no ?? 'N/A' }}
                                    </p>
                                    <p style="margin:8px 0; font-size:14px; color:#333;">
                                        <strong>Travel Dates:</strong>
                                        {{ $booking->travel_date?->format('d M Y') ?? 'N/A' }} –
                                        {{ $booking->travel_end_date?->format('d M Y') ?? 'N/A' }}
                                    </p>
                                </td>
                                <td style="width:50%; vertical-align:top; padding-left:15px;">
                                    <p style="margin:8px 0; font-size:14px; color:#333;">
                                        <strong>Passengers:</strong>
                                        {{ $booking->adults }} Adult(s)
                                        @if ($booking->children > 0)
                                            , {{ $booking->children }} Child(ren)
                                        @endif
                                        @if ($booking->infants > 0)
                                            , {{ $booking->infants }} Infant(s)
                                        @endif
                                    </p>
                                    <p style="margin:8px 0; font-size:14px; color:#333;">
                                        <strong>Payment Status:</strong> {{ ucfirst($booking->payment_status) }}
                                    </p>
                                    @if ($booking->special_requirements)
                                        <p style="margin:8px 0; font-size:14px; color:#333;">
                                            <strong>Special Requirements:</strong> {{ $booking->special_requirements }}
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- PRICE BREAKDOWN --}}
                <div style="padding:0 40px 40px 40px;">
                    <h3
                        style="margin:0 0 10px 0; font-size:16px; font-weight:600; color:#2c3e50;
                        border-bottom:2px solid #2c3e50; padding-bottom:6px;">
                        Price Breakdown
                    </h3>

                    <table style="width:100%; border-collapse:collapse; background:#fff; border:1px solid #ddd;">
                        <thead>
                            <tr style="background:#f4f6f8;">
                                <th
                                    style="padding:8px 12px; text-align:left; font-size:13px; font-weight:600;
                                    color:#2c3e50; border-bottom:1px solid #ddd;">
                                    Description</th>
                                <th
                                    style="padding:8px 12px; text-align:right; font-size:13px; font-weight:600;
                                    color:#2c3e50; border-bottom:1px solid #ddd; width:180px;">
                                    Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding:8px 12px; font-size:13px; color:#333; border-bottom:1px solid #eee;">
                                    Package Price
                                </td>
                                <td
                                    style="padding:8px 12px; text-align:right; font-size:13px; color:#333;
                                    border-bottom:1px solid #eee;">
                                    {{ $booking->currency }} {{ number_format($booking->package_price, 2) }}
                                </td>
                            </tr>

                            @if ($booking->tax > 0)
                                <tr>
                                    <td style="padding:8px 12px; font-size:13px; color:#333; border-bottom:1px solid #eee;">
                                        Additional Charges
                                    </td>
                                    <td
                                        style="padding:8px 12px; text-align:right; font-size:13px; color:#333;
                                        border-bottom:1px solid #eee;">
                                        {{ $booking->currency }} {{ number_format($booking->tax, 2) }}
                                    </td>
                                </tr>
                            @endif

                            @if ($booking->discount > 0)
                                <tr>
                                    <td style="padding:8px 12px; font-size:13px; color:#333; border-bottom:1px solid #eee;">
                                        Discount
                                    </td>
                                    <td
                                        style="padding:8px 12px; text-align:right; font-size:13px; color:#dc3545;
                                        border-bottom:1px solid #eee;">
                                        - {{ $booking->currency }} {{ number_format($booking->discount, 2) }}
                                    </td>
                                </tr>
                            @endif

                            <tr style="background:#2c3e50;">
                                <td style="padding:10px 12px; font-size:14px; font-weight:700; color:white;">
                                    TOTAL AMOUNT
                                </td>
                                <td
                                    style="padding:10px 12px; text-align:right; font-size:15px; font-weight:700; color:white;">
                                    {{ $booking->currency }} {{ number_format($booking->total_price, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- FOOTER --}}
                <div style="padding:30px 40px; background:#f8f9fa; border-top:2px solid #e0e0e0; text-align:center;">
                    <h4 style="margin:0 0 10px 0; font-size:18px; color:#2c3e50; font-weight:600;">
                        Thank You for Your Business!
                    </h4>
                    <p style="margin:8px 0; font-size:14px; color:#666;">
                        We look forward to serving you and making your travel experience memorable.
                    </p>
                    <p style="margin:8px 0; font-size:14px; color:#666;">
                        For any questions or assistance, please contact us.
                    </p>
                    <p style="margin:5px 0; font-size:13px; color:#888;">
                        Email: info@vacayguider.com | Phone: +94 114 272 372 | Website: www.vacayguider.com
                    </p>
                </div>
            </div>


        </div>
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
