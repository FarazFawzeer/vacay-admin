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
            <a href="{{ route('admin.rent-vehicle-bookings.index') }}" class="btn btn-light me-2" style="width:130px;">Back</a>
            <a class="btn btn-primary" onclick="generatePdf()" style="width:130px;">Generate PDF</a>
        </div>
    </div>

    <div class="card-body">
        <div id="invoiceContent">
            @php
                // ---------- Images ----------
                $images = [];
                if ($booking->vehicle->vehicle_image) {
                    $images[] = $booking->vehicle->vehicle_image;
                }
                if (!empty($booking->vehicle->sub_image) && is_array($booking->vehicle->sub_image)) {
                    $images = array_merge($images, $booking->vehicle->sub_image);
                }

                $mainImage = count($images)
                    ? asset('storage/' . ltrim($images[0], '/'))
                    : 'https://via.placeholder.com/280x180?text=No+Image';

                $subImages = array_slice($images, 1, 4); // max 4

                // ---------- Calculations ----------
                $subtotal = $booking->price + ($booking->additional_price ?? 0) - ($booking->discount ?? 0);
                $advance  = $booking->advance_paid ?? 0;
                $balance  = max(0, $subtotal - $advance);
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
                                +94 114 272 372 | info@vacayguider.com
                            </div>
                        </td>
                        <td style="text-align:right;">
                            <h1 style="margin:0;font-size:24px;font-weight:300;letter-spacing:2px;">
                                {{ strtoupper($booking->status) }}
                            </h1>
                            <table style="margin-left:auto;margin-top:10px;font-size:13px;">
                                <tr>
                                    <td style="color:#888;padding:2px 10px;">Invoice No</td>
                                    <td>{{ $booking->inv_no }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#888;padding:2px 10px;">Date</td>
                                    <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                                </tr>
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
                            <div>{{ $booking->customer->email ?? '-' }}</div>
                            <div>{{ $booking->customer->contact ?? '-' }}</div>
                        </td>
                        <td style="width:50%;vertical-align:top;border-left:1px solid #eee;padding-left:25px;">
                            <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">Booking Details</h4>
                            <div><strong>Vehicle:</strong> {{ $booking->vehicle->name }}</div>
                            <div><strong>Pickup:</strong> {{ $booking->start_datetime }}</div>
                            <div><strong>Drop-off:</strong> {{ $booking->end_datetime }}</div>
                            <div><strong>Status:</strong> {{ ucfirst($booking->status) }}</div>
                            <div><strong>Payment:</strong> {{ ucfirst($booking->payment_status) }}</div>
                        </td>
                    </tr>
                </table>

                <!-- Vehicle Preview -->
                <table style="width:100%;margin-bottom:30px;border-collapse:collapse;">
                    <tr>
                        <!-- Info -->
                        <td style="vertical-align:top;padding-right:15px;">
                            <h3 style="margin-top:0;">{{ $booking->vehicle->name }}</h3>
                            <p style="color:#666;font-size:13px;">{{ $booking->notes }}</p>
                        </td>

                        <!-- Main Image -->
                        <td style="width:300px;vertical-align:top;">
                            <img src="{{ $mainImage }}"
                                 style="width:300px;height:200px;object-fit:cover;border:1px solid #ddd;border-radius:4px;">
                        </td>

                        <!-- Sub Images 2x2 -->
                        <td style="width:260px;vertical-align:top;padding-left:10px;">
                            @if(count($subImages))
                                <table style="width:100%;border-collapse:collapse;">
                                    @foreach($subImages as $i => $img)
                                        @if($i % 2 === 0)
                                            <tr>
                                        @endif
                                        <td style="padding:2px;">
                                            <img src="{{ asset('storage/' . ltrim($img,'/')) }}"
                                                 style="width:120px;height:95px;object-fit:cover;border:1px solid #ddd;border-radius:4px;">
                                        </td>
                                        @if($i % 2 === 1)
                                            </tr>
                                        @endif
                                    @endforeach
                                    @if(count($subImages) % 2 !== 0)
                                        <td></td></tr>
                                    @endif
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
                            <th style="padding:12px;text-align:right;font-size:11px;text-transform:uppercase;">
                                Amount ({{ $booking->currency }})
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding:14px;border-bottom:1px solid #eee;">Base Rental Charge</td>
                            <td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">
                                {{ number_format($booking->price,2) }}
                            </td>
                        </tr>

                        @if($booking->additional_price > 0)
                        <tr>
                            <td style="padding:14px;border-bottom:1px solid #eee;">Additional Charges</td>
                            <td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">
                                {{ number_format($booking->additional_price,2) }}
                            </td>
                        </tr>
                        @endif

                        @if($booking->discount > 0)
                        <tr>
                            <td style="padding:14px;border-bottom:1px solid #eee;color:#888;">Discount</td>
                            <td style="padding:14px;text-align:right;border-bottom:1px solid #eee;color:#888;">
                                ({{ number_format($booking->discount,2) }})
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <!-- Totals -->
                <div style="width:40%;margin-left:auto;">
                    <table style="width:100%;font-size:14px;">
                        <tr>
                            <td style="padding:8px 0;color:#888;">Subtotal</td>
                            <td style="padding:8px 0;text-align:right;">
                                {{ number_format($subtotal,2) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;color:#888;">Advance Paid</td>
                            <td style="padding:8px 0;text-align:right;color:#198754;">
                                {{ number_format($advance,2) }}
                            </td>
                        </tr>
                        <tr style="border-top:1px solid #333;">
                            <td style="padding:12px 0;font-weight:bold;font-size:16px;">Balance Due</td>
                            <td style="padding:12px 0;text-align:right;font-weight:bold;font-size:18px;">
                                {{ $booking->currency }} {{ number_format($balance,2) }}
                            </td>
                        </tr>
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
    fetch("{{ route('admin.vehicle-bookings.generatePdf') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            html: document.getElementById('invoiceContent').innerHTML
        })
    })
    .then(res => res.blob())
    .then(blob => {
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'Vehicle_Booking_Invoice.pdf';
        link.click();
    });
}
</script>
@endsection
