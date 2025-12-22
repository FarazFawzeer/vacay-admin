@extends('layouts.vertical', ['subtitle' => 'Create Vehicle Booking'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Vehicle Booking',
        'subtitle' => 'Create',
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Vehicle Booking</h5>
        </div>

        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form id="vehicleBookingForm" action="{{ route('admin.vehicle-inv-bookings.store') }}" method="POST">
                @csrf

                <div class="row">
                    <input type="hidden" id="invoice_no" value="{{ $nextInvoice }}">
                    <!-- Customer -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" data-email="{{ $customer->email ?? 'N/A' }}"
                                    data-phone="{{ $customer->contact ?? 'N/A' }}">
                                    {{ $customer->name }} ({{ $customer->customer_code }})
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
                                    $images = [];
                                    if ($vehicle->vehicle_image) {
                                        $images[] = $vehicle->vehicle_image;
                                    }
                                    if (!empty($vehicle->sub_image) && is_array($vehicle->sub_image)) {
                                        $images = array_merge($images, $vehicle->sub_image);
                                    }
                                    if (empty($images)) {
                                        $images[] = null;
                                    }
                                    $imagesJson = json_encode($images); // no htmlspecialchars
                                @endphp
                                <option value="{{ $vehicle->id }}" data-images='@json($images)'>
                                    {{ $vehicle->name }} - {{ $vehicle->model }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <!-- Pickup & Drop-off Details -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Pickup Location</label>
                        <input type="text" name="pickup_location" id="pickup_location" class="form-control"
                            placeholder="e.g., Colombo Airport">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Pickup Date & Time</label>
                        <input type="datetime-local" name="pickup_datetime" id="pickup_datetime" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Drop-off Location</label>
                        <input type="text" name="dropoff_location" id="dropoff_location" class="form-control"
                            placeholder="e.g., Kandy City Centre">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Drop-off Date & Time</label>
                        <input type="datetime-local" name="dropoff_datetime" id="dropoff_datetime" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mileage</label>
                            <select name="mileage" id="mileageSelect" class="form-select" required>
                                <option value="unlimited">Unlimited</option>
                                <option value="limited">Limited</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3" id="totalKmField" style="display:none;">
                            <label class="form-label">Total KM</label>
                            <input type="number" name="total_km" id="totalKmInput" class="form-control"
                                placeholder="Enter total KM">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Currency</label>
                            <select name="currency" id="currency" class="form-select">
                                <option value="LKR">LKR</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                            </select>
                        </div>
                    </div>
                    <!-- Pricing -->
                    <!-- Pricing -->


                    <div class="col-md-2 mb-3">
                        <label class="form-label">Base Price</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control"
                            value="0">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Additional Charges</label>
                        <input type="number" step="0.01" name="additional_charges" id="additional_charges"
                            class="form-control" value="0">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Discount</label>
                        <input type="number" step="0.01" name="discount" id="discount" class="form-control"
                            value="0">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Advance Paid</label>
                        <input type="number" step="0.01" name="advance_paid" id="advance_paid" class="form-control"
                            value="0">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Total Price</label>
                        <input type="number" step="0.01" name="total_price" id="total_price" class="form-control"
                            readonly>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Balance</label>
                        <input type="number" step="0.01" name="balance" id="balance" class="form-control"
                            readonly>
                    </div>


                    <!-- Status / Payment -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="Quotation" selected>Quotation</option>
                            <option value="Accepted">Accepted</option>
                            <option value="Invoiced">Invoiced</option>
                            <option value="Partially Paid">Partially Paid</option>
                            <option value="Paid">Paid</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>



                    <!-- Note -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Note</label>
                        <textarea name="note" id="note" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="text-end gap-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-warning" style="width:120px;"
                        onclick="window.location='{{ route('admin.vehicle-bookings.index') }}'">
                        Back
                    </button>


                    <button type="button" class="btn btn-secondary" onclick="previewBooking()"
                        style="width:120px;">Preview</button>
                    <button type="submit" class="btn btn-primary" style="width:120px;">Save </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Preview Modal --}}
    <div class="modal fade" id="bookingPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header  text-white">
                    <h5 class="modal-title">Vehicle Booking Preview</h5>
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


    <script>
        const priceInput = document.getElementById("price");
        const additionalInput = document.getElementById("additional_charges");
        const discountInput = document.getElementById("discount");
        const advanceInput = document.getElementById("advance_paid");
        const totalInput = document.getElementById("total_price");
        const balanceInput = document.getElementById("balance");

        document.addEventListener("DOMContentLoaded", function() {
            // Mileage toggle
            const mileageSelect = document.getElementById("mileageSelect");
            const totalKmField = document.getElementById("totalKmField");
            const totalKmInput = document.getElementById("totalKmInput");

            if (mileageSelect) {
                function toggleTotalKm() {
                    if (mileageSelect.value === "limited") {
                        totalKmField.style.display = "block";
                    } else {
                        totalKmField.style.display = "none";
                        totalKmInput.value = "";
                    }
                }
                mileageSelect.addEventListener("change", toggleTotalKm);
                toggleTotalKm();
            }

            function calculateTotal() {
                const price = parseFloat(priceInput.value) || 0;
                const add = parseFloat(additionalInput.value) || 0;
                const discount = parseFloat(discountInput.value) || 0;
                const advance = parseFloat(advanceInput.value) || 0;

                const total = (price + add) - discount;
                totalInput.value = total.toFixed(2);

                const balance = Math.max(0, total - advance);
                balanceInput.value = balance.toFixed(2);
            }

            [priceInput, additionalInput, discountInput, advanceInput].forEach(el => el.addEventListener("input",
                calculateTotal));
        });



        const STORAGE_BASE = "{{ asset('storage') }}";

        function getImageUrl(imagePath) {
            if (imagePath && imagePath.trim() !== '') {
                return "{{ asset('storage') }}/" + imagePath.replace(/^\/+/, '');
            }
            return "https://via.placeholder.com/280x180?text=No+Image";
        }


        // Preview function
        // Preview function
        // Preview function
        function previewBooking() {
            const customerSelect = document.getElementById('customer_id');
            const vehicleSelect = document.getElementById('vehicle_id');

            const customerOption = customerSelect.options[customerSelect.selectedIndex];
            const vehicleOption = vehicleSelect.options[vehicleSelect.selectedIndex];

            const customerName = customerOption ? customerOption.text : '-';
            const customerEmail = customerOption ? customerOption.dataset.email : '-';
            const customerPhone = customerOption ? customerOption.dataset.phone : '-';

            const vehicleName = vehicleOption ? vehicleOption.text : '-';

            // Images
            let images = [];
            if (vehicleOption && vehicleOption.dataset.images) {
                try {
                    images = JSON.parse(vehicleOption.dataset.images);
                } catch (e) {
                    images = [];
                }
            }
            const mainImage = images.length ? getImageUrl(images[0]) : "https://via.placeholder.com/300x200?text=No+Image";

            // Dates & Locations
            const pickupLocation = document.getElementById('pickup_location')?.value || '-';
            const pickupDatetime = document.getElementById('pickup_datetime')?.value || '-';
            const dropoffLocation = document.getElementById('dropoff_location')?.value || '-';
            const dropoffDatetime = document.getElementById('dropoff_datetime')?.value || '-';

            // Mileage
            const mileage = document.getElementById('mileageSelect')?.value || '-';
            const totalKm = document.getElementById('totalKmInput')?.value || '-';

            // Prices
            const price = parseFloat(document.getElementById('price')?.value) || 0;
            const addCharges = parseFloat(document.getElementById('additional_charges')?.value) || 0;
            const discount = parseFloat(document.getElementById('discount')?.value) || 0;
            const advancePaid = parseFloat(document.getElementById('advance_paid')?.value) || 0;

            const total = Math.max(0, (price + addCharges) - discount);
            const balance = Math.max(0, total - advancePaid);

            const currency = document.getElementById('currency')?.value || 'LKR';
            const paymentStatus = document.getElementById('payment_status')?.value || '-';
            const status = document.getElementById('status')?.value || '-';
            const note = document.getElementById('note')?.value || '';

            const invoiceNo = document.getElementById('invoice_no')?.value || '-';
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
                    <tr><td style="color:#888;padding:2px 10px;">Invoice No</td><td>${invoiceNo}</td></tr>
                    <tr><td style="color:#888;padding:2px 10px;">Date</td><td>${invoiceDate}</td></tr>
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
                <div><strong>Pickup Location:</strong> ${pickupLocation}</div>
                <div><strong>Pickup Date & Time:</strong> ${pickupDatetime}</div>
                <div><strong>Drop-off Location:</strong> ${dropoffLocation}</div>
                <div><strong>Drop-off Date & Time:</strong> ${dropoffDatetime}</div>
                <div><strong>Mileage:</strong> ${mileage} ${mileage === 'limited' ? `(${totalKm} KM)` : ''}</div>
    
            </td>
        </tr>
    </table>

    <!-- Vehicle Preview -->
    <table style="width:100%;margin-bottom:30px; border-collapse:collapse;">
        <tr>
            <td style="vertical-align:top; padding-right:15px;">
                <h3 style="margin-top:0;">${vehicleName}</h3>
                <p style="color:#666;font-size:13px;">${note}</p>
            </td>
            <td style="width:300px; vertical-align:top;">
                <img src="${mainImage}" style="width:300px;height:200px;object-fit:cover;border:1px solid #ddd;border-radius:4px;">
            </td>
            <td style="width:260px; vertical-align:top; padding-left:10px;">
                ${images.length > 1 ? (function(){
                    let html = '<table style="width:100%; border-collapse:collapse;">';
                    const subImages = images.slice(1,5);
                    for(let i=0;i<subImages.length;i++){
                        if(i%2===0) html += '<tr>';
                        html += `<td style="padding:2px;"><img src="${getImageUrl(subImages[i])}" style="width:120px;height:95px;object-fit:cover;border:1px solid #ddd;border-radius:4px;"></td>`;
                        if(i%2===1) html += '</tr>';
                    }
                    if(subImages.length%2!==0) html += '<td></td></tr>';
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
            <tr><td style="padding:14px;border-bottom:1px solid #eee;">Base Rental Charge</td><td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">${price.toFixed(2)}</td></tr>
            ${addCharges > 0 ? `<tr><td style="padding:14px;border-bottom:1px solid #eee;">Additional Charges</td><td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">${addCharges.toFixed(2)}</td></tr>` : ''}
            ${discount > 0 ? `<tr><td style="padding:14px;border-bottom:1px solid #eee;color:#888;">Discount</td><td style="padding:14px;text-align:right;border-bottom:1px solid #eee;color:#888;">(${discount.toFixed(2)})</td></tr>` : ''}
        </tbody>
    </table>

    <!-- Totals -->
    <div style="width:40%;margin-left:auto;">
        <table style="width:100%;font-size:14px;">
            <tr><td style="padding:8px 0;color:#888;">Subtotal</td><td style="padding:8px 0;text-align:right;">${total.toFixed(2)}</td></tr>
            <tr><td style="padding:8px 0;color:#888;">Advance Paid</td><td style="padding:8px 0;text-align:right;color:#198754;">${advancePaid.toFixed(2)}</td></tr>
            <tr style="border-top:1px solid #333;"><td style="padding:12px 0;font-weight:bold;font-size:16px;">Balance Due</td><td style="padding:12px 0;text-align:right;font-weight:bold;font-size:18px;">${currency} ${balance.toFixed(2)}</td></tr>
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
                .then(response => response.blob())
                .then(blob => {
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'Vehicle_Booking.pdf';
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
