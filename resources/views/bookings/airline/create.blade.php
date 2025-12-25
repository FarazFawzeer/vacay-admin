@extends('layouts.vertical', ['subtitle' => 'Create Airline Booking'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Airline Booking',
        'subtitle' => 'Create',
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Airline Booking</h5>
        </div>

        <div class="card-body">
            <form id="airlineBookingForm" action="{{ route('admin.airline-bookings.store') }}" method="POST">
                @csrf

                <div class="row">

                    {{-- Customer --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->name }} - {{ $customer->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Agent --}}
                    {{-- Agent --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agent</label>
                        <select name="agent_id" class="form-select" required>
                            <option value="">Select Agent</option>
                            @foreach ($agents as $agent)
                                <option value="{{ $agent->id }}">
                                    {{ $agent->name }} - {{ $agent->company_name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- From Country --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">From Country</label>
                        <select name="from_country" class="form-select" required>
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country['en'] }}">{{ $country['en'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- To Country --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">To Country</label>
                        <select name="to_country" class="form-select" required>
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country['en'] }}">{{ $country['en'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Departure --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Departure Date & Time</label>
                        <input type="datetime-local" name="departure_datetime" class="form-control" required>
                    </div>

                    {{-- Arrival --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Arrival Date & Time</label>
                        <input type="datetime-local" name="arrival_datetime" class="form-control" required>
                    </div>

                    {{-- Airline --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Airline / Flight</label>
                        <input type="text" name="airline" class="form-control">
                    </div>

                    {{-- Booking Status --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Booking Status</label>
                        <select name="status" class="form-select">
                            <option value="Quotation" selected>Quotation</option>
                            <option value="Accepted">Accepted</option>
                            <option value="Invoiced">Invoiced</option>
                            <option value="Partially Paid">Partially Paid</option>
                            <option value="Paid">Paid</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>

                    {{-- Payment Status --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select">
                            <option value="unpaid">Unpaid</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>

                    {{-- Price & Payment Section --}}
                    <div class="col-md-6 mb-3">
                        <div class="card border-secondary">
                            <div class="card-header bg-light">
                                <strong>Price & Payment Details</strong>
                            </div>
                            <div class="card-body">
                                {{-- Currency Dropdown --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-4 col-form-label">Currency</label>
                                    <div class="col-sm-8">
                                        <select name="currency" id="currency" class="form-select">
                                            <option value="LKR" selected>LKR</option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Base Price --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-4 col-form-label">Base Price</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="base_price" id="base_price" class="form-control"
                                            value="0" required>
                                    </div>
                                </div>

                                {{-- Additional Price --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-4 col-form-label">Additional Price</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="additional_price" id="additional_price"
                                            class="form-control" value="0">
                                    </div>
                                </div>

                                {{-- Discount --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-4 col-form-label">Discount</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="discount" id="discount" class="form-control"
                                            value="0">
                                    </div>
                                </div>

                                <hr>

                                {{-- Total --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-4 col-form-label">Total</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="total_amount" id="total_amount" class="form-control"
                                            readonly>
                                    </div>
                                </div>

                                {{-- Advanced Paid --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-4 col-form-label">Advanced Paid</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="advanced_paid" id="advanced_paid"
                                            class="form-control" value="0">
                                    </div>
                                </div>

                                {{-- Balance --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-4 col-form-label">Balance</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="balance" id="balance" class="form-control"
                                            readonly>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>



                </div>

                <div class="text-end mt-3">
                    <button type="button" class="btn btn-warning" style="width:130px;"
                        onclick="window.location='{{ route('admin.airline-bookings.index') }}'">
                        Back
                    </button>

                    <button type="button" class="btn btn-secondary me-2" onclick="previewBooking()"
                        style="width: 130px;">Preview</button>
                    <button type="submit" class="btn btn-primary" style="width: 130px;">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Preview Modal --}}
    <div class="modal fade" id="bookingPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-white bg-primary">
                    <h5 class="modal-title">Airline Booking Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="bookingPreviewBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="width:130px;">Close</button>
                    <button type="button" class="btn btn-success" onclick="generatePdf()" style="width:130px;">
                        Generate PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Recalculate total and balance
        ['base_price', 'additional_price', 'discount', 'advanced_paid'].forEach(id => {
            document.getElementById(id).addEventListener('input', calculate);
        });

        function calculate() {
            const base = +document.getElementById('base_price').value || 0;
            const add = +document.getElementById('additional_price').value || 0;
            const disc = +document.getElementById('discount').value || 0;
            const adv = +document.getElementById('advanced_paid').value || 0;

            const total = base + add - disc;
            document.getElementById('total_amount').value = total;
            document.getElementById('balance').value = total - adv;
        }

        // Preview Booking
    function previewBooking() {
    // Customer
    const customerSelect = document.querySelector('select[name="customer_id"]');
    const customerOption = customerSelect.options[customerSelect.selectedIndex];
    const customerName = customerOption ? customerOption.text : '-';

    // Agent
    const agentSelect = document.querySelector('select[name="agent_id"]');
    const agentOption = agentSelect.options[agentSelect.selectedIndex];
    const agentName = agentOption ? agentOption.text : '-';

    // From/To Country
    const fromSelect = document.querySelector('select[name="from_country"]');
    const fromCountry = fromSelect.options[fromSelect.selectedIndex].text || '-';
    const toSelect = document.querySelector('select[name="to_country"]');
    const toCountry = toSelect.options[toSelect.selectedIndex].text || '-';

    // Airline & Flight
    const airline = document.querySelector('input[name="airline"]').value || '-';
    const departure = document.querySelector('input[name="departure_datetime"]').value || '-';
    const arrival = document.querySelector('input[name="arrival_datetime"]').value || '-';

    // Prices
    const basePrice = parseFloat(document.getElementById('base_price').value) || 0;
    const additionalPrice = parseFloat(document.getElementById('additional_price').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const advancedPaid = parseFloat(document.getElementById('advanced_paid').value) || 0;
    const total = basePrice + additionalPrice - discount;
    const balance = total - advancedPaid;
    const currency = document.getElementById('currency').value || 'LKR';

    // Status
    const status = document.querySelector('select[name="status"]').value || '-';
    const paymentStatus = document.querySelector('select[name="payment_status"]').value || '-';
    const bookingDate = new Date().toLocaleDateString('en-GB');

    const html = `
<div style="max-width:800px;margin:0 auto;font-family:'Helvetica Neue',Arial,sans-serif;color:#333;background:#fff;padding:25px;">
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
                <h1 style="margin:0;font-size:24px;font-weight:300;letter-spacing:2px;">${status}</h1>
                <table style="margin-left:auto;margin-top:10px;font-size:13px;">
                    <tr><td style="color:#888;padding:2px 10px;">Booking Date</td><td>${bookingDate}</td></tr>
                    <tr><td style="color:#888;padding:2px 10px;">Payment Status</td><td>${paymentStatus}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="width:100%;margin-bottom:35px;font-size:13px;">
        <tr>
            <td style="width:50%;vertical-align:top;">
                <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">Passenger / Agent</h4>
                <div style="font-size:15px;font-weight:bold;">${customerName}</div>
                <div style="font-size:14px;color:#555;">${agentName}</div>
            </td>
            <td style="width:50%;vertical-align:top;border-left:1px solid #eee;padding-left:25px;">
                <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">Flight Details</h4>
                <div><strong>Route:</strong> ${fromCountry} - ${toCountry}</div>
                <div><strong>Airline / Flight:</strong> ${airline}</div>
                <div><strong>Departure:</strong> ${departure}</div>
                <div><strong>Arrival:</strong> ${arrival}</div>
            </td>
        </tr>
    </table>

    <table style="width:100%;border-collapse:collapse;font-size:14px;margin-bottom:30px;">
        <thead>
            <tr style="background:#f9f9f9;border-top:1px solid #333;border-bottom:1px solid #333;">
                <th style="padding:12px;text-align:left;font-size:11px;text-transform:uppercase;">Description</th>
                <th style="padding:12px;text-align:right;font-size:11px;text-transform:uppercase;">Amount (${currency})</th>
            </tr>
        </thead>
        <tbody>
            <tr><td style="padding:14px;border-bottom:1px solid #eee;">Base Price</td><td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">${basePrice.toFixed(2)}</td></tr>
            ${additionalPrice > 0 ? `<tr><td style="padding:14px;border-bottom:1px solid #eee;">Additional Price</td><td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">${additionalPrice.toFixed(2)}</td></tr>` : ''}
            ${discount > 0 ? `<tr><td style="padding:14px;border-bottom:1px solid #eee;color:#888;">Discount</td><td style="padding:14px;text-align:right;border-bottom:1px solid #eee;color:#888;">(${discount.toFixed(2)})</td></tr>` : ''}
        </tbody>
    </table>

    <div style="width:40%;margin-left:auto;">
        <table style="width:100%;font-size:14px;">
            <tr><td style="padding:8px 0;color:#888;">Total</td><td style="padding:8px 0;text-align:right;">${total.toFixed(2)}</td></tr>
            <tr><td style="padding:8px 0;color:#198754;">Advanced Paid</td><td style="padding:8px 0;text-align:right;color:#198754;">${advancedPaid.toFixed(2)}</td></tr>
            <tr style="border-top:1px solid #333;"><td style="padding:12px 0;font-weight:bold;font-size:16px;">Balance</td><td style="padding:12px 0;text-align:right;font-weight:bold;font-size:18px;">${currency} ${balance.toFixed(2)}</td></tr>
        </table>
    </div>

    <div style="margin-top:60px;text-align:center;border-top:1px solid #eee;padding-top:20px;font-size:11px;color:#aaa;">
        This is a system generated invoice. No signature required.<br>
        <strong>Vacay Guider</strong> | www.vacayguider.com
    </div>
</div>
`;

    document.getElementById('bookingPreviewBody').innerHTML = html;
    new bootstrap.Modal(document.getElementById('bookingPreviewModal')).show();
}



        // Generate PDF
        function generatePdf() {
            const htmlContent = document.getElementById('bookingPreviewBody').innerHTML;

            fetch("{{ route('admin.airline-bookings.generatePdf') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        html: htmlContent
                    })
                })
                .then(response => response.blob())
                .then(blob => {
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'Airline_Booking.pdf';
                    link.click();
                    URL.revokeObjectURL(link.href);
                })
                .catch(error => {
                    console.error(error);
                    alert("PDF generation failed");
                });
        }
    </script>
@endsection
