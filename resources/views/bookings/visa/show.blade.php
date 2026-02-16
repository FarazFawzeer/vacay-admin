@extends('layouts.vertical', ['subtitle' => 'Visa Booking Details'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Visa Booking',
        'subtitle' => 'Details',
    ])

    <div class="card">

        {{-- Header buttons --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Visa Booking Details - {{ $booking->invoice_no }}</h5>
            <div>
                <a href="{{ route('admin.visa-bookings.index') }}" class="btn btn-light me-2" style="width: 130px;">
                    Back
                </a>
                <a href="{{ route('admin.visa-bookings.edit', $booking->id) }}" class="btn btn-warning me-2"
                    style="width: 130px;">
                    Edit
                </a>
                <a class="btn btn-primary" onclick="generatePdf(event)" style="width: 130px;">
                    Generate PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            <div id="invoiceContent">
                @php
                    $fromCountry = $booking->visa->from_country ?? '-';
                    $toCountry = $booking->visa->to_country ?? '-';
                    $visaType = $booking->visa->visa_type ?? '-';
                    $visaCategory = $booking->visaCategory->visa_type ?? '-';

                    $customerName = trim(($booking->passport->first_name ?? '') . ' ' . ($booking->passport->second_name ?? ''));
                    $passportNo = $booking->passport->passport_number ?? '-';
                    $nationality = $booking->passport->nationality ?? '-';
                    $address = $booking->passport->address ?? null;

                    $agentText = $booking->agent
                        ? $booking->agent->name . ' (' . ($booking->agent->company_name ?? 'N/A') . ')'
                        : '-';

                    $currency = $booking->currency ?? 'LKR';

                    // desc_points: supports array/json/string
                    $descRaw = $booking->desc_points ?? [];
                    if (is_string($descRaw)) {
                        $decoded = json_decode($descRaw, true);
                        $descRaw = is_array($decoded) ? $decoded : [];
                    }
                    $descItems = collect($descRaw)->values();

                    $basePrice = (float) ($booking->base_price ?? 0);
                    $addChargesVal = (float) ($booking->additional_price ?? 0);
                    $discountVal = (float) ($booking->discount ?? 0);

                    $bookingDate = optional($booking->created_at)->format('d/m/Y') ?? '-';
                    $publishedDate = $booking->published_at ? $booking->published_at->format('d/m/Y') : null;
                @endphp

                <div style="max-width:800px;margin:0 auto;font-family:'Helvetica Neue',Arial,sans-serif;color:#333;background:#fff;padding:25px;">

                    {{-- HEADER --}}
                    <table style="width:100%;border-bottom:2px solid #333;margin-bottom:30px;">
                        <tr>
                            <td style="width:50%;">
                                <img src="{{ asset('images/vacayguider.png') }}" style="height:80px;">
                                <div style="font-size:12px;color:#666;margin-top:10px;line-height:1.4;">
                                    <strong>Vacay Guider (Pvt) Ltd.</strong><br>
                                    Negombo, Sri Lanka<br>
                                    +94 114 272 372 | +94 711 999 444 | +94 777 035 325 <br>
                                    info@vacayguider.com
                                </div>
                            </td>

                            <td style="width:50%;text-align:right;vertical-align:top;">
                                <h1 style="margin:0;font-size:24px;font-weight:300;letter-spacing:2px;">
                                    {{ strtoupper(str_replace('_', ' ', $booking->status ?? '')) }}
                                </h1>

                                <table style="margin-left:auto;margin-top:10px;font-size:13px;">
                                    <tr>
                                        <td style="color:#888;padding:2px 10px;text-align:left;">Invoice No:</td>
                                        <td style="text-align:left;">{{ $booking->invoice_no }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color:#888;padding:2px 10px;text-align:left;">Booking Date:</td>
                                        <td style="text-align:left;">{{ $bookingDate }}</td>
                                    </tr>
                                 
                                    <tr>
                                        <td style="color:#888;padding:2px 10px;text-align:left;">Commercial License No</td>
                                        <td style="text-align:left;">PV 00285826</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    {{-- CUSTOMER / VISA INFO --}}
                    <table style="width:100%;margin-bottom:35px;font-size:13px;">
                        <tr>
                            <td style="width:50%;vertical-align:top;">
                                <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">
                                    Customer / Passport Information
                                </h4>

                                <div style="font-size:15px;font-weight:bold;margin-bottom:6px;">
                                    {{ $customerName ?: '-' }}
                                </div>

                                <div style="font-size:13px;color:#555;line-height:1.6;">
                                    <div><strong>Passport No:</strong> {{ $passportNo }}</div>
                                    <div><strong>Nationality:</strong> {{ $nationality }}</div>
                                    @if (!empty($address))
                                        <div><strong>Address:</strong> {{ $address }}</div>
                                    @endif
                                </div>
                            </td>

                            <td style="width:50%;vertical-align:top;border-left:1px solid #eee;padding-left:25px;">
                                <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">
                                    Visa Details
                                </h4>

                                <div style="font-size:13px;color:#555;line-height:1.7;">
                                    <div><strong>Route:</strong> {{ $fromCountry }} → {{ $toCountry }}</div>
                                    <div><strong>Visa Type:</strong> {{ $visaType }}</div>
                                    <div><strong>Visa Category:</strong> {{ $visaCategory }}</div>
                                    <div><strong>Agent:</strong> {{ $agentText }}</div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    {{-- DESCRIPTION TABLE (Visa Charges first, then description points, then discount, then additional) --}}
                    @php $rowNo = 1; @endphp

                    <table style="width:100%; border-collapse:collapse; margin-bottom:30px; font-size:14px;">
                        <thead>
                            <tr style="background:#f9f9f9; border-top:1px solid #333; border-bottom:1px solid #333;">
                                <th style="padding:12px; width:50px; text-align:center; text-transform:uppercase; font-size:11px;">
                                    No
                                </th>
                                <th style="padding:12px; text-align:left; text-transform:uppercase; font-size:11px;">
                                    Description
                                </th>
                                <th style="padding:12px; text-align:right; text-transform:uppercase; font-size:11px;">
                                    Total ({{ $currency }})
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            {{-- 1) Visa Charges --}}
                            <tr>
                                <td style="padding:12px; border-bottom:1px solid #eee; text-align:center;">
                                    {{ $rowNo++ }}
                                </td>
                                <td style="padding:12px; border-bottom:1px solid #eee;">
                                    Visa Charges
                                </td>
                                <td style="padding:12px; text-align:right; border-bottom:1px solid #eee;">
                                    {{ number_format($basePrice, 2) }}
                                </td>
                            </tr>

                            {{-- 2) Description points --}}
                            @if ($descItems->count() > 0)
                                @foreach ($descItems as $item)
                                    @php
                                        $title = '';
                                        $subs = collect();

                                        if (is_string($item)) {
                                            $subs = collect([$item]);
                                        }

                                        if (is_array($item)) {
                                            $title = trim($item['title'] ?? '');
                                            $subs = collect($item['subs'] ?? []);
                                            if (!$title && isset($item['value'])) {
                                                $subs = collect([$item['value']]);
                                            }
                                        }

                                        $subs = $subs->map(fn($v) => trim((string) $v))->filter()->values();
                                    @endphp

                                    @if ($title || $subs->count())
                                        <tr>
                                            <td style="padding:12px; border-bottom:1px solid #eee; text-align:center; vertical-align:top;">
                                                {{ $rowNo++ }}
                                            </td>

                                            <td style="padding:12px; border-bottom:1px solid #eee;">
                                                @if ($title)
                                                    <div style="font-weight:700; margin-bottom:6px;">
                                                        {{ $title }}
                                                    </div>
                                                @endif

                                                @if ($subs->count())
                                                    <ul style="margin:0; padding-left:18px; line-height:1.5;">
                                                        @foreach ($subs as $s)
                                                            <li>{{ $s }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </td>

                                            <td style="padding:12px; border-bottom:1px solid #eee; text-align:right; color:#999;">
                                                -
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif

                            {{-- 3) Discount --}}
                            @if ($discountVal > 0)
                                <tr>
                                    <td style="padding:12px; border-bottom:1px solid #eee; text-align:center; color:#888;">
                                        {{ $rowNo++ }}
                                    </td>
                                    <td style="padding:12px; border-bottom:1px solid #eee; color:#888; font-style:italic;">
                                        Discount Applied
                                    </td>
                                    <td style="padding:12px; text-align:right; border-bottom:1px solid #eee; color:#888;">
                                        ({{ number_format($discountVal, 2) }})
                                    </td>
                                </tr>
                            @endif

                            {{-- 4) Additional Services / Charges --}}
                            @if ($addChargesVal > 0)
                                <tr>
                                    <td style="padding:12px; border-bottom:1px solid #eee; text-align:center;">
                                        {{ $rowNo++ }}
                                    </td>
                                    <td style="padding:12px; border-bottom:1px solid #eee;">
                                        Additional Services / Charges
                                    </td>
                                    <td style="padding:12px; text-align:right; border-bottom:1px solid #eee;">
                                        {{ number_format($addChargesVal, 2) }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    {{-- TOTALS --}}
                    <div style="width:40%;margin-left:auto;">
                        <table style="width:100%;font-size:14px;">
                            <tr>
                                <td style="padding:8px 0;color:#888;">Total</td>
                                <td style="padding:8px 0;text-align:right;">
                                    {{ number_format((float) $booking->total_amount, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0;color:#198754;">Advanced Paid</td>
                                <td style="padding:8px 0;text-align:right;color:#198754;">
                                    {{ number_format((float) $booking->advanced_paid, 2) }}
                                </td>
                            </tr>
                            <tr style="border-top:1px solid #333;">
                                <td style="padding:12px 0;font-weight:bold;font-size:16px;">Balance</td>
                                <td style="padding:12px 0;text-align:right;font-weight:bold;font-size:18px;">
                                    {{ $currency }} {{ number_format((float) $booking->balance, 2) }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    {{-- NOTE --}}
                    @if (!empty($booking->note))
                        <div style="margin-top:25px;padding:15px;border:1px dashed #ddd;background:#fafafa;">
                            <h4 style="margin:0 0 8px;font-size:12px;color:#888;text-transform:uppercase;">
                                Note
                            </h4>
                            <div style="font-size:14px;line-height:1.6;color:#333;">
                                {!! nl2br(e($booking->note)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- FOOTER --}}
                    <div style="margin-top:60px;text-align:center;border-top:1px solid #eee;padding-top:20px;font-size:11px;color:#aaa;">
                        This is a system generated invoice. No signature required.<br>
                        <strong>Vacay Guider</strong> | www.vacayguider.com
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function generatePdf(event) {
            const invoiceElement = document.getElementById('invoiceContent');

            const button = event?.target;
            const originalText = button ? button.innerText : null;
            if (button) {
                button.innerText = 'Generating...';
                button.disabled = true;
            }

            fetch("{{ route('admin.visa-bookings.generatePdf', $booking->id) }}", {
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
                    if (!response.ok) throw new Error("PDF generation failed");
                    return response.blob();
                })
                .then(blob => {
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'Visa_Booking_{{ $booking->invoice_no }}.pdf';
                    link.click();
                    URL.revokeObjectURL(link.href);

                    if (button) {
                        button.innerText = originalText;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error("Error generating PDF:", error);
                    alert("Failed to generate PDF. Please try again.");

                    if (button) {
                        button.innerText = originalText;
                        button.disabled = false;
                    }
                });
        }
    </script>
@endsection
