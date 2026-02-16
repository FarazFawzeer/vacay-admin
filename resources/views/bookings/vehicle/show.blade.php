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
                // ---------- Images ----------
                $images = [];
                if (!empty($booking->vehicle?->vehicle_image)) $images[] = $booking->vehicle->vehicle_image;
                if (!empty($booking->vehicle?->sub_image) && is_array($booking->vehicle->sub_image)) {
                    $images = array_merge($images, $booking->vehicle->sub_image);
                }
                $images = $images ?: [null];

                $mainImage = $images[0]
                    ? asset('storage/' . ltrim($images[0], '/'))
                    : 'https://via.placeholder.com/280x180?text=No+Image';

                $thumbnails = array_slice($images, 1);

                // ---------- Totals ----------
                $price        = (float) ($booking->price ?? 0);
                $addCharges   = (float) ($booking->additional_charges ?? 0);
                $discount     = (float) ($booking->discount ?? 0);
                $advancePaid  = (float) ($booking->advance_paid ?? 0);
                $totalPrice   = (float) ($booking->total_price ?? (($price + $addCharges) - $discount));
                $balance      = max(0, $totalPrice - $advancePaid);

                $mileageText = $booking->mileage === 'limited'
                    ? "Limited ({$booking->total_km} KM)"
                    : ucfirst((string) $booking->mileage);

                // ---------- DESC POINTS (DB COLUMN: desc_points) ----------
                $descPoints = [];
                if (!empty($booking->desc_points)) {
                    if (is_array($booking->desc_points)) {
                        $descPoints = $booking->desc_points;
                    } else {
                        $descPoints = json_decode($booking->desc_points, true) ?: [];
                    }
                }

                // helper escape (for safety inside html)
                $e = fn($v) => e((string) $v);
            @endphp

            <div style="max-width:800px; margin:0 auto; font-family:'Helvetica Neue', Helvetica, Arial, sans-serif; color:#333; background:#fff; padding:20px;">

                <!-- Header (Rent Vehicle style) -->
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
                                {{ strtoupper($booking->status ?? 'QUOTATION') }}
                            </h1>

                            <table style="margin-left:auto; margin-top:10px; font-size:13px; border-collapse:collapse;">
                                <tr>
                                    <td style="padding:2px 10px; text-align:left; color:#888;">Invoice No:</td>
                                    <td style="padding:2px 10px; font-weight:bold;">{{ $booking->inv_no }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:2px 10px; text-align:left; color:#888;">Date:</td>
                                    <td style="padding:2px 10px;">
                                        {{ $booking->published_at?->format('d-m-Y') ?? $booking->created_at?->format('d-m-Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:2px 10px; text-align:left; color:#888;">Payment:</td>
                                    <td style="padding:2px 10px;">{{ ucfirst($booking->payment_status ?? 'pending') }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Client & Booking Info -->
                <table style="width:100%; margin-bottom:40px; font-size:13px;">
                    <tr>
                        <td style="width:50%; vertical-align:top;">
                            <h4 style="text-transform:uppercase; font-size:11px; color:#888; margin-bottom:10px; letter-spacing:1px;">
                                Client Information
                            </h4>
                            <div style="font-size:15px; font-weight:bold; margin-bottom:5px;">
                                {{ $booking->customer->name ?? '-' }}
                            </div>
                            <div style="color:#555;">{{ $booking->customer->address ?? '-' }}</div>
                            <div style="color:#555;">{{ $booking->customer->email ?? '-' }}</div>
                            <div style="color:#555;">{{ $booking->customer->contact ?? '-' }}</div>
                        </td>

                        <td style="width:50%; vertical-align:top; border-left:1px solid #eee; padding-left:30px;">
                            <h4 style="text-transform:uppercase; font-size:11px; color:#888; margin-bottom:10px; letter-spacing:1px;">
                                Booking Information
                            </h4>
                            <div style="margin-bottom:3px;">
                                <strong>Vehicle:</strong>
                                {{ $booking->vehicle->name ?? '-' }} {{ $booking->vehicle->model ? ' - '.$booking->vehicle->model : '' }}
                            </div>
                            <div style="margin-bottom:3px;">
                                <strong>Pickup:</strong>
                                {{ $booking->pickup_datetime?->format('Y-m-d H:i') ?? '-' }} ({{ $booking->pickup_location ?? '-' }})
                            </div>
                            <div style="margin-bottom:3px;">
                                <strong>Drop-off:</strong>
                                {{ $booking->dropoff_datetime?->format('Y-m-d H:i') ?? '-' }} ({{ $booking->dropoff_location ?? '-' }})
                            </div>
                            <div style="margin-bottom:3px;">
                                <strong>Mileage:</strong> {{ $mileageText }}
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Vehicle Images -->
                <table style="width:100%;margin-bottom:30px; border-collapse:collapse;">
                    <tr>
                        <td style="vertical-align:top; padding-right:15px;">
                            <h3 style="margin-top:0;">
                                {{ $booking->vehicle->name ?? '-' }} {{ $booking->vehicle->model ? ' - '.$booking->vehicle->model : '' }}
                            </h3>
                            @if(!empty($booking->note))
                                <p style="color:#666;font-size:13px; white-space:pre-wrap;">{{ $booking->note }}</p>
                            @endif
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
                                                    <img src="{{ $thumb ? asset('storage/' . ltrim($thumb, '/')) : 'https://via.placeholder.com/120x95?text=No+Image' }}"
                                                        style="width:120px;height:95px;object-fit:cover;border:1px solid #ddd;border-radius:4px;">
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

                <!-- ✅ Description Table (same as preview) -->
                <table style="width:100%; border-collapse:collapse; margin-bottom:30px; font-size:14px;">
                    <thead>
                        <tr style="background:#f9f9f9; border-top:1px solid #333; border-bottom:1px solid #333;">
                            <th style="padding:12px; width:50px; text-align:center; text-transform:uppercase; font-size:11px;">No</th>
                            <th style="padding:12px; text-align:left; text-transform:uppercase; font-size:11px;">Description</th>
                            <th style="padding:12px; text-align:right; text-transform:uppercase; font-size:11px;">
                                Total ({{ $booking->currency ?? 'LKR' }})
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @php $rowNo = 1; @endphp

                        @if(!empty($descPoints))
                            @foreach($descPoints as $i => $p)
                                @php
                                    $title = $p['title'] ?? '';
                                    $subs  = $p['subs'] ?? [];
                                    $subs  = is_array($subs) ? array_values(array_filter($subs, fn($s)=> trim((string)$s) !== '')) : [];
                                @endphp

                                @if(trim((string)$title) !== '' || count($subs) > 0)
                                    <tr>
                                        <td style="padding:12px; text-align:center; border-bottom:1px solid #eee; vertical-align:top;">
                                            {{ $rowNo }}
                                        </td>

                                        <td style="padding:12px; border-bottom:1px solid #eee;">
                                            @if(trim((string)$title) !== '')
                                                <div style="font-weight:700; margin-bottom:6px;">
                                                    {{ $title }}
                                                </div>
                                            @endif

                                            @if(count($subs) > 0)
                                                <ul style="margin:0 0 0 18px; padding:0; font-size:12.5px; color:#555; line-height:1.6;">
                                                    @foreach($subs as $s)
                                                        <li>{{ $s }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>

                                        {{-- ✅ first row shows base price (like preview) --}}
                                        @if($rowNo === 1)
                                            <td style="padding:12px; text-align:right; border-bottom:1px solid #eee; vertical-align:top;">
                                                {{ number_format($price, 2) }}
                                            </td>
                                        @else
                                            <td style="padding:12px; border-bottom:1px solid #eee;"></td>
                                        @endif
                                    </tr>
                                    @php $rowNo++; @endphp
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" style="padding:12px; color:#888; text-align:left;">
                                    No description points added.
                                </td>
                            </tr>

                            {{-- if no desc points, still show base price row --}}
                            <tr>
                                <td style="padding:12px; text-align:center; border-bottom:1px solid #eee;">1</td>
                                <td style="padding:12px; border-bottom:1px solid #eee;">Base Rental Charge</td>
                                <td style="padding:12px; text-align:right; border-bottom:1px solid #eee;">{{ number_format($price,2) }}</td>
                            </tr>
                        @endif

                        @if($addCharges > 0)
                            <tr>
                                <td style="padding:12px; text-align:center; border-bottom:1px solid #eee;">-</td>
                                <td style="padding:12px; border-bottom:1px solid #eee;">Additional Charges</td>
                                <td style="padding:12px; text-align:right; border-bottom:1px solid #eee;">{{ number_format($addCharges,2) }}</td>
                            </tr>
                        @endif

                        @if($discount > 0)
                            <tr>
                                <td style="padding:12px; text-align:center; border-bottom:1px solid #eee;">-</td>
                                <td style="padding:12px; border-bottom:1px solid #eee; color:#888; font-style:italic;">Discount Applied</td>
                                <td style="padding:12px; text-align:right; border-bottom:1px solid #eee; color:#888;">
                                    ({{ number_format($discount,2) }})
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <!-- Totals -->
                <div style="width:40%; margin-left:auto;">
                    <table style="width:100%; font-size:14px; border-collapse:collapse;">
                        <tr>
                            <td style="padding:8px 0; color:#888;">Subtotal:</td>
                            <td style="padding:8px 0; text-align:right;">{{ number_format($totalPrice,2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0; color:#888;">Advance Paid:</td>
                            <td style="padding:8px 0; text-align:right; color:#1a7f37;">{{ number_format($advancePaid,2) }}</td>
                        </tr>
                        <tr style="border-top:1px solid #333;">
                            <td style="padding:12px 0; font-weight:bold; font-size:16px;">Balance Due:</td>
                            <td style="padding:12px 0; text-align:right; font-weight:bold; font-size:18px; color:#000;">
                                {{ $booking->currency ?? 'LKR' }} {{ number_format($balance,2) }}
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Notes -->
                <table style="width:100%; margin-bottom:20px; border-collapse:collapse;">
                    <tr>
                        <td style="padding:0;">
                            @if(!empty($booking->note))
                                <div style="color:#666; font-size:13px; line-height:1.6; white-space:pre-wrap;">{{ $booking->note }}</div>
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
