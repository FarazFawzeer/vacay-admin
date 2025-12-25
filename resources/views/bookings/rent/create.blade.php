@extends('layouts.vertical', ['subtitle' => 'Create Rent Vehicle Booking'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Rent Vehicle Booking',
        'subtitle' => 'Create',
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Rent Vehicle Booking</h5>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.rent-vehicle-bookings.store') }}" method="POST" id="rentBookingForm">
                @csrf
                <input type="hidden" id="invoice_no" value="{{ $nextInvoice ?? 'INV0001' }}">

                <div class="row">
                    <!-- Customer -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" data-email="{{ $customer->email ?? 'N/A' }}"
                                    data-phone="{{ $customer->contact ?? 'N/A' }}">
                                    {{ $customer->name }} ({{ $customer->customer_code ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Vehicle -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vehicle</label>
                        <select name="vehicle_id" id="vehicle_id" class="form-select" required>
                            <option value="">Select Vehicle</option>
                            @foreach ($vehicles as $vehicle)
                                @php
                                    $images = $vehicle->vehicle_image ? [$vehicle->vehicle_image] : [];
                                    if (!empty($vehicle->sub_image) && is_array($vehicle->sub_image)) {
                                        $images = array_merge($images, $vehicle->sub_image);
                                    }
                                    $images = $images ?: [null];
                                @endphp
                                <option value="{{ $vehicle->id }}" data-images='@json($images)'>
                                    {{ $vehicle->vehicle_name ?? $vehicle->name }} -
                                    {{ $vehicle->vehicle_number ?? $vehicle->model }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Start & End -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Start Date & Time</label>
                        <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-control"
                            required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">End Date & Time</label>
                        <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control" required>
                    </div>



                    <!-- Status / Payment -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Booking Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Quotation">Quotation</option>
                            <option value="Accepted">Accepted</option>
                            <option value="Ivoiced">Invoiced</option>
                            <option value="Partially Paid">Partially Paid</option>
                            <option value="Paid">Paid</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-select" required>
                            <option value="unpaid">Unpaid</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="notes" rows="4" class="form-control"></textarea>
                    </div>

                    {{-- Price & Payment Details --}}
                    <div class="col-md-6 mb-3">
                        <div class="card border-secondary">
                            <div class="card-header bg-light">
                                <strong>Price & Payment Details</strong>
                            </div>
                            <div class="card-body">

                                {{-- Currency --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Currency</label>
                                    <div class="col-sm-10">
                                        <select name="currency" id="currency" class="form-select" required>
                                            <option value="LKR">LKR</option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Base Price --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Base Price</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" value="0" name="price" id="price"
                                            class="form-control calc" required>
                                    </div>
                                </div>

                                {{-- Additional Charges --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Additional Charges</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" value="0" name="additional_price"
                                            id="additional_price" class="form-control calc">
                                    </div>
                                </div>

                                {{-- Discount --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Discount</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="discount" id="discount"
                                            value="0" class="form-control calc">
                                    </div>
                                </div>

                                <hr>

                                {{-- Total Price --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Total Price</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="total_price" id="total_price"
                                            class="form-control" value="0" readonly required>
                                    </div>
                                </div>

                                {{-- Advance Paid --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Advance Paid</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="advance_paid" id="advance_paid"
                                            class="form-control calc" value="0">
                                    </div>
                                </div>

                                {{-- Balance Amount --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Balance Amount</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" id="balance_amount" class="form-control"
                                            readonly>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-end gap-2 d-flex justify-content-end">
                    <a href="{{ route('admin.rent-vehicle-bookings.index') }}" class="btn btn-light"
                        style="width:130px;">Back</a>
                    <button type="button" class="btn btn-secondary" onclick="previewBooking()"
                        style="width:130px;">Preview</button>
                    <button type="submit" class="btn btn-primary" style="width:130px;">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Preview Modal --}}
    <div class="modal fade" id="bookingPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header  text-white">
                    <h5 class="modal-title">Rent Vehicle Booking Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="bookingPreviewBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="width:130px;">Close</button>
                    <button type="button" class="btn btn-success" onclick="generatePdf()" style="width:130px;">Generate
                        PDF</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const priceInput = document.getElementById("price");
            const additionalInput = document.getElementById("additional_price");
            const discountInput = document.getElementById("discount");
            const totalInput = document.getElementById("total_price");

            const advanceInput = document.getElementById("advance_paid");
            const balanceInput = document.getElementById("balance_amount");

            function calculateAmounts() {
                const price = parseFloat(priceInput.value) || 0;
                const add = parseFloat(additionalInput.value) || 0;
                const discount = parseFloat(discountInput.value) || 0;
                const advance = parseFloat(advanceInput.value) || 0;

                const total = (price + add) - discount;
                const balance = total - advance;

                totalInput.value = total.toFixed(2);
                balanceInput.value = balance > 0 ? balance.toFixed(2) : '0.00';
            }

            [
                priceInput,
                additionalInput,
                discountInput,
                advanceInput
            ].forEach(el => el.addEventListener('input', calculateAmounts));

            calculateAmounts();
        });

        function getImageUrl(imagePath) {
            if (imagePath && imagePath.trim() !== '') {
                return "{{ asset('storage') }}/" + imagePath.replace(/^\/+/, '');
            }
            return "https://via.placeholder.com/280x180?text=No+Image";
        }

        function previewBooking() {
            const customerSelect = document.getElementById('customer_id');
            const vehicleSelect = document.getElementById('vehicle_id');

            const customerOption = customerSelect.options[customerSelect.selectedIndex];
            const vehicleOption = vehicleSelect.options[vehicleSelect.selectedIndex];

            const customerName = customerOption.text;
            const customerEmail = customerOption.dataset.email;
            const customerPhone = customerOption.dataset.phone;

            const vehicleName = vehicleOption.text;

            // Images
            let images = [];
            if (vehicleOption.dataset.images) {
                try {
                    images = JSON.parse(vehicleOption.dataset.images);
                } catch (e) {
                    images = [];
                }
            }

            const mainImage = images.length ?
                getImageUrl(images[0]) :
                "https://via.placeholder.com/300x200?text=No+Image";

            const thumbnails = images.slice(1).map(img =>
                `<img src="${getImageUrl(img)}" style="width:55px;height:40px;object-fit:cover;margin-right:5px;border-radius:3px;border:1px solid #ddd;">`
            ).join('');

            // Dates
            const startDatetime = document.getElementById('start_datetime').value || '-';
            const endDatetime = document.getElementById('end_datetime').value || '-';

            // Prices
            const price = parseFloat(document.getElementById('price').value) || 0;
            const addCharges = parseFloat(document.getElementById('additional_price').value) || 0;
            const discount = parseFloat(document.getElementById('discount').value) || 0;
            const advancePaid = parseFloat(document.getElementById('advance_paid').value) || 0;

            const total = Math.max(0, (price + addCharges) - discount);
            const balance = Math.max(0, total - advancePaid);

            const currency = document.getElementById('currency').value || 'LKR';
            const paymentStatus = document.getElementById('payment_status').value;
            const status = document.getElementById('status').value;
            const note = document.getElementById('notes').value;

            const invoiceNo = document.getElementById('invoice_no').value || '-';
            const invoiceDate = new Date().toLocaleDateString('en-GB');

            const html = `
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
                <h1 style="margin:0;font-size:24px;font-weight:300;letter-spacing:2px;">${status}</h1>
                <table style="margin-left:auto;margin-top:10px;font-size:13px;">
                    <tr>
                        <td style="color:#888;padding:2px 10px;">Invoice No</td>
                        <td>${invoiceNo}</td>
                    </tr>
                    <tr>
                        <td style="color:#888;padding:2px 10px;">Date</td>
                        <td>${invoiceDate}</td>
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
                <div style="font-size:15px;font-weight:bold;">${customerName}</div>
                <div>${customerEmail}</div>
                <div>${customerPhone}</div>
            </td>
            <td style="width:50%;vertical-align:top;border-left:1px solid #eee;padding-left:25px;">
                <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">Booking Details</h4>
                <div><strong>Vehicle:</strong> ${vehicleName}</div>
                <div><strong>Pickup:</strong> ${startDatetime}</div>
                <div><strong>Drop-off:</strong> ${endDatetime}</div>
                <div><strong>Status:</strong> ${status}</div>
                <div><strong>Payment:</strong> ${paymentStatus}</div>
            </td>
        </tr>
    </table>

    <!-- Vehicle Preview -->
   <table style="width:100%;margin-bottom:30px; border-collapse:collapse;">
    <tr>
          <!-- Vehicle Info -->
        <td style="vertical-align:top; padding-right:15px;">
            <h3 style="margin-top:0;">${vehicleName}</h3>
            <p style="color:#666;font-size:13px;">${note || ''}</p>
        </td>

        <!-- Main Image -->
        <td style="width:300px; vertical-align:top;">
            <img src="${mainImage}" style="width:300px;height:200px;object-fit:cover;border:1px solid #ddd;border-radius:4px;">
        </td>

        <!-- Sub Images -->
        <td style="width:260px; vertical-align:top; padding-left:10px;">
          ${images.length > 1 ? (function() {
    let html = '<table style="width:100%; border-collapse:collapse;">';
    const subImages = images.slice(1,5); // max 4 sub-images
    for (let i = 0; i < subImages.length; i++) {
        if (i % 2 === 0) html += '<tr>';
        html += `<td style="padding:2px;">
                            <img src="${getImageUrl(subImages[i])}" style="width:120px;height:95px;object-fit:cover;border:1px solid #ddd;border-radius:4px;">
                         </td>`;
        if (i % 2 === 1) html += '</tr>';
    }
    if (subImages.length % 2 !== 0) html += '<td></td></tr>'; // close last row if odd
    html += '</table>';
    return html;
})() : ''}
        </td>

      
    </tr>
</table>


    <!-- Charges -->
    <table style="width:100%;border-collapse:collapse;font-size:14px;margin-bottom:30px;">
        <thead>
            <tr style="background:#f9f9f9;border-top:1px solid #333;border-bottom:1px solid #333;">
                <th style="padding:12px;text-align:left;font-size:11px;text-transform:uppercase;">Description</th>
                <th style="padding:12px;text-align:right;font-size:11px;text-transform:uppercase;">Amount (${currency})</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding:14px;border-bottom:1px solid #eee;">Base Rental Charge</td>
                <td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">${price.toFixed(2)}</td>
            </tr>
            ${addCharges > 0 ? `
                        <tr>
                            <td style="padding:14px;border-bottom:1px solid #eee;">Additional Charges</td>
                            <td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">${addCharges.toFixed(2)}</td>
                        </tr>` : ''}
            ${discount > 0 ? `
                        <tr>
                            <td style="padding:14px;border-bottom:1px solid #eee;color:#888;">Discount</td>
                            <td style="padding:14px;text-align:right;border-bottom:1px solid #eee;color:#888;">(${discount.toFixed(2)})</td>
                        </tr>` : ''}
        </tbody>
    </table>

    <!-- Totals -->
    <div style="width:40%;margin-left:auto;">
        <table style="width:100%;font-size:14px;">
            <tr>
                <td style="padding:8px 0;color:#888;">Subtotal</td>
                <td style="padding:8px 0;text-align:right;">${total.toFixed(2)}</td>
            </tr>
            <tr>
                <td style="padding:8px 0;color:#888;">Advance Paid</td>
                <td style="padding:8px 0;text-align:right;color:#198754;">${advancePaid.toFixed(2)}</td>
            </tr>
            <tr style="border-top:1px solid #333;">
                <td style="padding:12px 0;font-weight:bold;font-size:16px;">Balance Due</td>
                <td style="padding:12px 0;text-align:right;font-weight:bold;font-size:18px;">
                    ${currency} ${balance.toFixed(2)}
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top:60px;text-align:center;border-top:1px solid #eee;padding-top:20px;font-size:11px;color:#aaa;">
        This is a system generated invoice. No signature required.<br>
        <strong>Vacay Guider</strong> | www.vacayguider.com
    </div>

</div>`;

            document.getElementById('bookingPreviewBody').innerHTML = html;
            new bootstrap.Modal(document.getElementById('bookingPreviewModal')).show();
        }


        // PDF Generation
        function generatePdf() {
            const htmlContent = document.getElementById('bookingPreviewBody').innerHTML;

            fetch("{{ route('admin.rent-vehicle-bookings.generatePdf') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        html: htmlContent
                    })
                }).then(res => res.blob())
                .then(blob => {
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'RentVehicleBooking.pdf';
                    link.click();
                    URL.revokeObjectURL(link.href);
                }).catch(err => {
                    console.error(err);
                    alert("PDF generation failed");
                });
        }
    </script>
@endsection
