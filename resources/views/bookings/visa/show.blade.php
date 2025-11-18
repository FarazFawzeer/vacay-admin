@extends('layouts.vertical', ['subtitle' => 'Visa Booking Details'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Visa Booking',
        'subtitle' => 'Details',
    ])

    <div class="card">


        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Visa Booking Details</h5>
            <div>
                <a href="{{ route('admin.visa-bookings.index') }}" class="btn btn-light me-2" style="width: 130px;">Back</a>
                <a class="btn btn-primary" onclick="generatePdf()" style="width: 130px;">Generate PDF</a>
            </div>
        </div>
        <div class="card-body">
            <div id="invoiceContent">
                @php
                    $badgeColors = [
                        'pending' => '#ffc107',
                        'approved' => '#198754',
                        'rejected' => '#dc3545',
                    ];
                    $badgeColor = $badgeColors[$booking->status] ?? '#6c757d';
                    $invoiceDate = $booking->created_at->format('F j, Y');
                @endphp

                <div
                    style="max-width:800px;margin:0 auto;font-family:Arial,sans-serif;background:#fff;padding:40px;border:1px solid #ddd;">
                    <!-- Company Logo & Details -->
                    <div style="text-align:center;margin-bottom:30px;border-bottom:2px solid #333;padding-bottom:20px;">
                        <img src="{{ asset('images/vacayguider.png') }}" alt="Company Logo"
                            style="max-width:150px;margin-bottom:15px;">
                        <p style="margin:5px 0;color:#666;font-size:14px;">123 Business Street, City, State 12345</p>
                        <p style="margin:5px 0;color:#666;font-size:14px;">Phone: +94 114 272 372 | Email:
                            info@vacayguider.com</p>
                        <p style="margin:5px 0;color:#666;font-size:14px;">Website: www.vacayguider.com</p>
                    </div>

                    <!-- Invoice Header -->
                    <div style="text-align:center;margin-bottom:30px;">
                        <h2 style="margin:0 0 10px 0;font-size:24px;color:#333;">VISA INVOICE</h2>
                        <span
                            style="background:{{ $badgeColor }};color:white;padding:5px 15px;border-radius:3px;font-size:12px;font-weight:bold;">
                            {{ strtoupper($booking->status) }}
                        </span>
                    </div>

                    <!-- Customer & Invoice Info -->
                    <table style="width:100%;margin-bottom:30px;border-collapse:collapse;">
                        <tr>
                            <td style="width:50%;vertical-align:top;padding-right:15px;">
                                <h3
                                    style="margin:0 0 10px 0;font-size:14px;color:#333;font-weight:bold;text-transform:uppercase;">
                                    Bill To:</h3>
                                <p style="margin:5px 0;color:#666;font-size:14px;"><strong>Name:</strong>
                                    {{ $booking->customer->name }}</p>
                                <p style="margin:5px 0;color:#666;font-size:14px;"><strong>Email:</strong>
                                    {{ $booking->customer->email ?? 'N/A' }}</p>
                                <p style="margin:5px 0;color:#666;font-size:14px;"><strong>Phone:</strong>
                                    {{ $booking->customer->contact ?? 'N/A' }}</p>
                            </td>
                            <td style="width:50%;vertical-align:top;padding-left:15px;text-align:right;">
                                <p style="margin:5px 0;color:#666;font-size:14px;"><strong>Invoice No:</strong>
                                    {{ $booking->invoice_no }}</p>
                                <p style="margin:5px 0;color:#666;font-size:14px;"><strong>Invoice Date:</strong>
                                    {{ $invoiceDate }}</p>
                            </td>
                        </tr>
                    </table>

                    <!-- Visa Details -->
                    <div style="width:100%;margin-bottom:30px;">
                        <table style="width:100%;border-collapse:collapse;background:#f9f9f9;border-radius:5px;">
                            <tr>
                                <td style="padding:20px;">
                                    <h3
                                        style="margin:0 0 15px 0;font-size:16px;color:#333;font-weight:bold;text-align:center;">
                                        Visa Details</h3>
                                    <table style="width:100%;border-collapse:collapse;">
                                        <tr>
                                            <td style="padding:8px 0;font-size:14px;color:#666;"><strong>Visa:</strong>
                                                {{ $booking->visa->country ?? 'N/A' }} -
                                                {{ $booking->visa->visa_type ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:8px 0;font-size:14px;color:#666;"><strong>Passport
                                                    No:</strong> {{ $booking->passport_number }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:8px 0;font-size:14px;color:#666;"><strong>Agent:</strong>
                                                {{ $booking->agent ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:8px 0;font-size:14px;color:#666;"><strong>Issue
                                                    Date:</strong> {{ $booking->visa_issue_date->format('F j, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:8px 0;font-size:14px;color:#666;"><strong>Expiry
                                                    Date:</strong> {{ $booking->visa_expiry_date->format('F j, Y') }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Notes -->
                    @if ($booking->notes)
                        <div style="margin-bottom:20px;padding:15px;background:#fffbea;border-left:4px solid #ffc107;">
                            <p style="margin:0;font-size:14px;color:#666;"><strong>Note:</strong> {{ $booking->notes }}</p>
                        </div>
                    @endif

                    <!-- Footer -->
                    <div style="margin-top:40px;padding-top:20px;border-top:1px solid #ddd;text-align:center;">
                        <p style="margin:5px 0;color:#999;font-size:12px;">Thank you for your business!</p>
                        <p style="margin:5px 0;color:#999;font-size:12px;">For inquiries, contact info@vacayguider.com</p>
                    </div>
                </div>

            </div>
            ]
        </div>
    </div>
    <script>
        function generatePdf() {
            const htmlContent = document.querySelector('#invoiceContent').innerHTML;

            fetch("{{ route('admin.vehicle-bookings.generatePdf') }}", {
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
                    link.download = 'Vehicle_Booking_Invoice.pdf';
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
