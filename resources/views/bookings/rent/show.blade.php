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
                if (!empty($booking->vehicle?->vehicle_image)) {
                    $images[] = $booking->vehicle->vehicle_image;
                }
                if (!empty($booking->vehicle?->sub_image) && is_array($booking->vehicle->sub_image)) {
                    $images = array_merge($images, $booking->vehicle->sub_image);
                }

                $mainImage = count($images)
                    ? asset('storage/' . ltrim($images[0], '/'))
                    : 'https://via.placeholder.com/300x200?text=No+Image';

                $subImages = array_slice($images, 1, 4); // max 4

                // ---------- Calculations ----------
                $price      = (float) ($booking->price ?? 0);
                $addCharges = (float) ($booking->additional_price ?? 0);
                $discount   = (float) ($booking->discount ?? 0);

                $total   = max(0, ($price + $addCharges) - $discount);
                $advance = (float) ($booking->advance_paid ?? 0);
                $balance = max(0, $total - $advance);

                // ---------- Desc Points ----------
                $descPoints = $booking->desc_points ?? [];
                if (is_string($descPoints)) {
                    $decoded = json_decode($descPoints, true);
                    $descPoints = is_array($decoded) ? $decoded : [];
                }

                $currency = $booking->currency ?? 'LKR';

                $note = $booking->notes ?? '';

                $vehicleName = $booking->vehicle?->vehicle_name ?? $booking->vehicle?->name ?? '-';
                $vehicleNumber = $booking->vehicle?->vehicle_number ?? '-';
                $vehicleLabel = trim($vehicleName . ' - ' . $vehicleNumber, ' -');

                $invoiceDate = $booking->published_at
                    ? \Carbon\Carbon::parse($booking->published_at)->format('d/m/Y')
                    : ($booking->created_at?->format('d/m/Y') ?? now()->format('d/m/Y'));
            @endphp

            <div style="max-width:800px; margin:0 auto; font-family:'Helvetica Neue', Helvetica, Arial, sans-serif; color:#333; background:#fff; padding:20px;">

                <!-- Header (Tour style) -->
                <table style="width:100%; border-bottom:2px solid #333; padding-bottom:20px; margin-bottom:30px;">
                    <tr>
                        <td style="vertical-align: top;">
                            <img src="{{ asset('images/vacayguider.png') }}" alt="Logo" style="height:80px;">
                            <div style="margin-top:10px; font-size:12px; line-height:1.4; color:#666;">
                                <strong>VACAYGUIDER PRIVATE LIMITED</strong><br>
                                22/14 C, Asarappa Road, Negombo.<br>
                                +94114272372 / +94711 999 444 / +94 777 035 325 <br>
                                info@vacayguider.com
                            </div>
                        </td>

                        <td style="text-align:right; vertical-align: bottom;">
                            <h1 style="margin:0; font-size:24px; font-weight:300; letter-spacing:2px; text-transform:uppercase;">
                                {{ $booking->status }}
                            </h1>

                            <table style="margin-left:auto; margin-top:10px; font-size:13px; border-collapse:collapse;">
                                <tr>
                                    <td style="padding:2px 10px; text-align:left; color:#888;">Invoice No:</td>
                                    <td style="padding:2px 10px; font-weight:bold;">{{ $booking->inv_no ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:2px 10px; text-align:left; color:#888;">Date:</td>
                                    <td style="padding:2px 10px;">{{ $invoiceDate }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:2px 10px; text-align:left; color:#888;">Payment:</td>
                                    <td style="padding:2px 10px;">{{ $booking->payment_status ?? '-' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Client & Booking Info (Tour style) -->
                <table style="width:100%; margin-bottom:40px; font-size:13px;">
                    <tr>
                        <td style="width:50%; vertical-align:top;">
                            <h4 style="text-transform:uppercase; font-size:11px; color:#888; margin-bottom:10px; letter-spacing:1px;">
                                Client Information
                            </h4>
                            <div style="font-size:15px; font-weight:bold; margin-bottom:5px;">
                                {{ $booking->customer?->name ?? '-' }}
                            </div>
                            <div style="color:#555;">{{ $booking->customer?->address ?? '-' }}</div>
                            <div style="color:#555;">{{ $booking->customer?->email ?? '-' }}</div>
                            <div style="color:#555;">{{ $booking->customer?->contact ?? '-' }}</div>
                        </td>

                        <td style="width:50%; vertical-align:top; border-left:1px solid #eee; padding-left:30px;">
                            <h4 style="text-transform:uppercase; font-size:11px; color:#888; margin-bottom:10px; letter-spacing:1px;">
                                Booking Information
                            </h4>
                            <div style="margin-bottom:3px;"><strong>Vehicle:</strong> {{ $vehicleLabel }}</div>
                            <div style="margin-bottom:3px;"><strong>Start:</strong> {{ $booking->start_datetime ?? '-' }}</div>
                            <div style="margin-bottom:3px;"><strong>End:</strong> {{ $booking->end_datetime ?? '-' }}</div>
                        </td>
                    </tr>
                </table>

                <!-- Vehicle Images -->
                <table style="width:100%; margin-bottom:30px; border-collapse:collapse;">
                    <tr>
                        <td style="vertical-align:top; padding-right:15px;">
                            <h3 style="margin-top:0;">{{ $vehicleLabel }}</h3>
                            @if(!empty(trim($note)))
                                <p style="color:#666; font-size:13px; white-space:pre-wrap;">{{ $note }}</p>
                            @endif
                        </td>

                        <td style="width:300px; vertical-align:top;">
                            <img src="{{ $mainImage }}" style="width:300px; height:200px; object-fit:cover; border:1px solid #ddd; border-radius:4px;">
                        </td>

                        <td style="width:260px; vertical-align:top; padding-left:10px;">
                            @if(count($subImages))
                                <table style="width:100%; border-collapse:collapse;">
                                    @foreach($subImages as $i => $img)
                                        @if($i % 2 === 0)
                                            <tr>
                                        @endif
                                        <td style="padding:2px;">
                                            <img src="{{ asset('storage/' . ltrim($img,'/')) }}"
                                                 style="width:120px; height:95px; object-fit:cover; border:1px solid #ddd; border-radius:4px;">
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

                <!-- Description Table (Tour style with No column) -->
                <table style="width:100%; border-collapse:collapse; margin-bottom:30px; font-size:14px;">
                    <thead>
                        <tr style="background:#f9f9f9; border-top:1px solid #333; border-bottom:1px solid #333;">
                            <th style="padding:12px; width:50px; text-align:center; text-transform:uppercase; font-size:11px;">No</th>
                            <th style="padding:12px; text-align:left; text-transform:uppercase; font-size:11px;">Description</th>
                            <th style="padding:12px; text-align:right; text-transform:uppercase; font-size:11px;">
                                Total ({{ $currency }})
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $counter = 1; @endphp

                        @if(is_array($descPoints) && count($descPoints))
                            @foreach($descPoints as $index => $row)
                                @php
                                    $title = trim((string)($row['title'] ?? ''));
                                    $subs  = $row['subs'] ?? [];
                                    $subs  = is_array($subs) ? array_values(array_filter(array_map('trim', $subs))) : [];
                                    $hasAny = ($title !== '') || count($subs);
                                    if(!$hasAny) continue;
                                @endphp

                                <tr>
                                    <td style="padding:12px; text-align:center; border-bottom:1px solid #eee; vertical-align:top;">
                                        {{ $counter }}
                                    </td>
                                    <td style="padding:12px; border-bottom:1px solid #eee;">
                                        <div style="font-weight:700; margin-bottom:6px;">
                                            {{ $title }}
                                        </div>

                                        @if(count($subs))
                                            <ul style="margin:0 0 0 18px; padding:0; font-size:12.5px; color:#555; line-height:1.6;">
                                                @foreach($subs as $s)
                                                    <li>{{ $s }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>

                                    @if($counter === 1)
                                        <td style="padding:12px; text-align:right; border-bottom:1px solid #eee; vertical-align:top;">
                                            {{ number_format($price, 2) }}
                                        </td>
                                    @else
                                        <td style="padding:12px; border-bottom:1px solid #eee;"></td>
                                    @endif
                                </tr>

                                @php $counter++; @endphp
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" style="padding:12px; color:#888; text-align:left;">
                                    No description points added.
                                </td>
                            </tr>
                        @endif

                        @if($addCharges > 0)
                            <tr>
                                <td style="padding:12px; text-align:center; border-bottom:1px solid #eee;">-</td>
                                <td style="padding:12px; border-bottom:1px solid #eee;">Additional Charges</td>
                                <td style="padding:12px; text-align:right; border-bottom:1px solid #eee;">
                                    {{ number_format($addCharges,2) }}
                                </td>
                            </tr>
                        @endif

                        @if($discount > 0)
                            <tr>
                                <td style="padding:12px; text-align:center; border-bottom:1px solid #eee;">-</td>
                                <td style="padding:12px; border-bottom:1px solid #eee; color:#888; font-style:italic;">
                                    Discount Applied
                                </td>
                                <td style="padding:12px; text-align:right; border-bottom:1px solid #eee; color:#888;">
                                    ({{ number_format($discount,2) }})
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <!-- Totals (Tour style) -->
                <div style="width:40%; margin-left:auto;">
                    <table style="width:100%; font-size:14px; border-collapse:collapse;">
                        <tr>
                            <td style="padding:8px 0; color:#888;">Subtotal:</td>
                            <td style="padding:8px 0; text-align:right;">{{ number_format($total,2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0; color:#888;">Advance Paid:</td>
                            <td style="padding:8px 0; text-align:right; color:#1a7f37;">
                                {{ number_format($advance,2) }}
                            </td>
                        </tr>
                        <tr style="border-top:1px solid #333;">
                            <td style="padding:12px 0; font-weight:bold; font-size:16px;">Balance Due:</td>
                            <td style="padding:12px 0; text-align:right; font-weight:bold; font-size:18px; color:#000;">
                                {{ $currency }} {{ number_format($balance,2) }}
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Notes (bottom like preview) -->
                <table style="width:100%; margin-bottom:20px; border-collapse:collapse;">
                    <tr>
                        <td style="padding:0;">
                            @if(!empty(trim($note)))
                                <div style="color:#666; font-size:13px; line-height:1.6; white-space:pre-wrap;">
                                    {{ $note }}
                                </div>
                            @else
                                <div style="color:#999; font-size:12px;">No notes provided.</div>
                            @endif
                        </td>
                    </tr>
                </table>

                <!-- Footer -->
                <div style="margin-top:60px; text-align:center; border-top:1px solid #eee; padding-top:20px; font-size:11px; color:#aaa;">
                    <p>www.vacayguider.com | Thank you for your business.</p>
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
        URL.revokeObjectURL(link.href);
    })
    .catch(err => {
        console.error(err);
        alert("PDF generation failed");
    });
}
</script>
@endsection
