<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ strtoupper($status ?? 'Quotation') }}</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        .total { background: #2c3e50; color: #fff; font-weight: 700; }
    </style>
</head>
<body>
    <div>
        <h2 style="text-align:center;">{{ strtoupper($status ?? 'Quotation') }}</h2>
        <p><strong>Number:</strong> {{ $invoiceNumber ?? '0001' }}</p>
        <p><strong>Date:</strong> {{ $currentDate ?? now()->format('d/m/Y') }}</p>

        <h4>Customer Details</h4>
        <p><strong>Name:</strong> {{ $customerName ?? '' }}</p>
        <p><strong>Email:</strong> {{ $customerEmail ?? '' }}</p>
        <p><strong>Phone:</strong> {{ $customerPhone ?? '' }}</p>

        <h4>Package Details</h4>
        <p><strong>Package:</strong> {{ $packageName ?? '' }}</p>
        <p><strong>Reference:</strong> {{ $packageRef ?? '' }}</p>
        <p><strong>Travel Dates:</strong> {{ $travelStart ?? '' }} to {{ $travelEnd ?? '' }}</p>
        <p><strong>Passengers:</strong> {{ $adults ?? 0 }} Adult(s) {{ $children ?? 0 }} Child(ren) {{ $infants ?? 0 }} Infant(s)</p>
        <p><strong>Payment Status:</strong> {{ strtoupper($paymentStatus ?? 'PENDING') }}</p>
        @if(!empty($specialReq))
        <p><strong>Special Requirements:</strong> {{ $specialReq }}</p>
        @endif

        <h4>Price Breakdown</h4>
        <table>
            <tr>
                <td>Package Price</td>
                <td style="text-align:right;">{{ $currency ?? 'USD' }} {{ number_format($packagePriceVal ?? 0, 2) }}</td>
            </tr>
            @if(($addChargesVal ?? 0) > 0)
            <tr>
                <td>Additional Charges</td>
                <td style="text-align:right;">{{ $currency ?? 'USD' }} {{ number_format($addChargesVal, 2) }}</td>
            </tr>
            @endif
            @if(($discountVal ?? 0) > 0)
            <tr>
                <td>Discount</td>
                <td style="text-align:right;">- {{ $currency ?? 'USD' }} {{ number_format($discountVal, 2) }}</td>
            </tr>
            @endif
            <tr class="total">
                <td>Total Amount</td>
                <td style="text-align:right;">{{ $currency ?? 'USD' }} {{ number_format(($packagePriceVal + $addChargesVal - $discountVal) ?? 0, 2) }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
