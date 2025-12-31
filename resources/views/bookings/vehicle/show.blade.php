@extends('layouts.vertical', ['subtitle' => 'Vehicle Booking Details'])

@section('content')
@include('layouts.partials.page-title', [
    'title' => 'Vehicle Booking Details',
    'subtitle' => 'Show',
])

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Vehicle Booking Details</h5>
        <div>
            <a href="{{ route('admin.vehicle-bookings.index') }}" class="btn btn-light me-2" style="width: 130px;">Back</a>
            <a class="btn btn-primary" onclick="generatePdf()" style="width: 130px;">Generate PDF</a>
        </div>
    </div>

    <div class="card-body">
        <div id="invoiceContent">
            @php
                $images = [];
                if ($booking->vehicle->vehicle_image) {
                    $images[] = $booking->vehicle->vehicle_image;
                }
                if (!empty($booking->vehicle->sub_image) && is_array($booking->vehicle->sub_image)) {
                    $images = array_merge($images, $booking->vehicle->sub_image);
                }
                if (empty($images)) $images[] = null;

                $mainImage = $images[0] ? asset('storage/' . ltrim($images[0], '/')) : 'https://via.placeholder.com/280x180?text=No+Image';
                $thumbnails = array_slice($images, 1);
                $badgeColors = [
                    'quotation' => '#6c757d',
                    'confirmed' => '#198754',
                    'completed' => '#20c997',
                    'cancelled' => '#dc3545',
                ];
                $badgeColor = $badgeColors[$booking->status] ?? '#6c757d';
                $advancePaid = $booking->advance_paid ?? 0;
                $balance = ($booking->total_price ?? 0) - $advancePaid;
                $mileageText = $booking->mileage === 'limited' ? "Limited ({$booking->total_km} KM)" : ucfirst($booking->mileage);
            @endphp

            <div style="max-width:800px;margin:0 auto;font-family:'Helvetica Neue',Arial,sans-serif;color:#333;background:#fff;padding:25px;">

                <!-- Header -->
                <table style="width:100%;border-bottom:2px solid #333;margin-bottom:30px;">
                    <tr>
                        <td>
                            <img src="{{ asset('images/vacayguider.png') }}" style="height:80px;">
                            <div style="font-size:12px;color:#666;margin-top:10px;line-height:1.4;">
                                <strong>Vacay Guider (Pvt) Ltd.</strong><br>
                                Negombo, Sri Lanka<br>
                      +94 114 272 372 | +94 711 999 444 |  +94 777 035 325 <br>
                                    info@vacayguider.com
                            </div>
                        </td>
                        <td style="text-align:right;">
                            <h1 style="margin:0;font-size:24px;font-weight:300;letter-spacing:2px;">{{ strtoupper($booking->status) }}</h1>
                            <table style="margin-left:auto;margin-top:10px;font-size:13px;">
                                <tr><td style="color:#888;padding:2px 10px;">Reference</td><td>{{ $booking->inv_no }}</td></tr>
                                <tr><td style="color:#888;padding:2px 10px;">Date</td><td>{{ $booking->created_at->format('F d, Y') }}</td></tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Customer & Booking Info -->
                <table style="width:100%;margin-bottom:35px;font-size:13px;">
                    <tr>
                        <td style="width:50%;vertical-align:top;">
                            <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">Client Information</h4>
                            <div style="font-size:15px;font-weight:bold;">{{ $booking->customer->name }}</div>
                            <div>{{ $booking->customer->email ?? 'N/A' }}</div>
                            <div>{{ $booking->customer->contact ?? 'N/A' }}</div>
                        </td>
                        <td style="width:50%;vertical-align:top;border-left:1px solid #eee;padding-left:25px;">
                            <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">Booking Details</h4>
                            <div><strong>Vehicle:</strong> {{ $booking->vehicle->name }} - {{ $booking->vehicle->model }}</div>
                            <div><strong>Pickup Location:</strong> {{ $booking->pickup_location }}</div>
                            <div><strong>Pickup Date & Time:</strong> {{ $booking->pickup_datetime?->format('Y-m-d H:i') ?? 'N/A' }}</div>
                            <div><strong>Drop-off Location:</strong> {{ $booking->dropoff_location }}</div>
                            <div><strong>Drop-off Date & Time:</strong> {{ $booking->dropoff_datetime?->format('Y-m-d H:i') ?? 'N/A' }}</div>
                            <div><strong>Mileage:</strong> {{ $mileageText }}</div>
                        </td>
                    </tr>
                </table>

                <!-- Vehicle Preview -->
                <table style="width:100%;margin-bottom:30px; border-collapse:collapse;">
                    <tr>
                        <td style="vertical-align:top; padding-right:15px;">
                            <h3 style="margin-top:0;">{{ $booking->vehicle->name }} - {{ $booking->vehicle->model }}</h3>
                            <p style="color:#666;font-size:13px;">{{ $booking->note }}</p>
                        </td>
                        <td style="width:300px; vertical-align:top;">
                            <img src="{{ $mainImage }}" style="width:300px;height:200px;object-fit:cover;border:1px solid #ddd;border-radius:4px;">
                        </td>
                        <td style="width:260px; vertical-align:top; padding-left:10px;">
                            @if(count($thumbnails) > 0)
                                <table style="width:100%; border-collapse:collapse;">
                                    @foreach(array_chunk($thumbnails, 2) as $row)
                                        <tr>
                                            @foreach($row as $thumb)
                                                <td style="padding:2px;">
                                                    <img src="{{ $thumb ? asset('storage/' . ltrim($thumb, '/')) : 'https://via.placeholder.com/120x95?text=No+Image' }}" style="width:120px;height:95px;object-fit:cover;border:1px solid #ddd;border-radius:4px;">
                                                </td>
                                            @endforeach
                                            @if(count($row) === 1)<td></td>@endif
                                        </tr>
                                    @endforeach
                                </table>
                            @endif
                        </td>
                    </tr>
                </table>

                <!-- Charges -->
                <table style="width:100%;border-collapse:collapse;font-size:14px;margin-bottom:30px;">
                    <thead>
                        <tr style="background:#f9f9f9;border-top:1px solid #333;border-bottom:1px solid #333;">
                            <th style="padding:12px;text-align:left;font-size:11px;text-transform:uppercase;">Description</th>
                            <th style="padding:12px;text-align:right;font-size:11px;text-transform:uppercase;">Amount ({{ $booking->currency }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td style="padding:14px;border-bottom:1px solid #eee;">Base Rental Charge</td><td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">{{ number_format($booking->price,2) }}</td></tr>
                        @if($booking->additional_charges > 0)
                            <tr><td style="padding:14px;border-bottom:1px solid #eee;">Additional Charges</td><td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">{{ number_format($booking->additional_charges,2) }}</td></tr>
                        @endif
                        @if($booking->discount > 0)
                            <tr><td style="padding:14px;border-bottom:1px solid #eee;color:#888;">Discount</td><td style="padding:14px;text-align:right;border-bottom:1px solid #eee;color:#888;">({{ number_format($booking->discount,2) }})</td></tr>
                        @endif
                    </tbody>
                </table>

                <!-- Totals -->
                <div style="width:40%;margin-left:auto;">
                    <table style="width:100%;font-size:14px;">
                        <tr><td style="padding:8px 0;color:#888;">Subtotal</td><td style="padding:8px 0;text-align:right;">{{ number_format($booking->total_price,2) }}</td></tr>
                        <tr><td style="padding:8px 0;color:#888;">Advance Paid</td><td style="padding:8px 0;text-align:right;color:#198754;">{{ number_format($advancePaid,2) }}</td></tr>
                        <tr style="border-top:1px solid #333;"><td style="padding:12px 0;font-weight:bold;font-size:16px;">Balance Due</td><td style="padding:12px 0;text-align:right;font-weight:bold;font-size:18px;">{{ $booking->currency }} {{ number_format($balance,2) }}</td></tr>
                    </table>
                </div>

                <div style="margin-top:60px;text-align:center;border-top:1px solid #eee;padding-top:20px;font-size:11px;color:#aaa;">
                    This is a system generated invoice. No signature required.<br>
                    <strong>Vacay Guider</strong> | www.vacayguider.com
                </div>
            </div>
        </div>
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
            body: JSON.stringify({ html: htmlContent })
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
