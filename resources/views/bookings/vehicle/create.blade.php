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

                <input type="hidden" id="invoice_no" value="{{ $nextInvoice }}">

                <div class="row">
                    <!-- Customer -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    data-email="{{ $customer->email ?? 'N/A' }}"
                                    data-phone="{{ $customer->contact ?? 'N/A' }}"
                                    data-address="{{ $customer->address ?? 'N/A' }}">
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
                                    $images = [];
                                    if ($vehicle->vehicle_image) {
                                        $images[] = $vehicle->vehicle_image;
                                    }
                                    if (!empty($vehicle->sub_image) && is_array($vehicle->sub_image)) {
                                        $images = array_merge($images, $vehicle->sub_image);
                                    }
                                    $images = $images ?: [null];
                                @endphp
                                <option value="{{ $vehicle->id }}" data-images='@json($images)'>
                                    {{ $vehicle->vehicle_name ?? $vehicle->name }} -
                                    {{ $vehicle->vehicle_number ?? $vehicle->vehicle_number }}
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

                    <!-- Mileage -->
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

                    <!-- Payment Status -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>

                    <!-- Booking Status -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Quotation" selected>Quotation</option>
                            <option value="Accepted">Accepted</option>
                            <option value="Invoiced">Invoiced</option>
                            <option value="Partially Paid">Partially Paid</option>
                            <option value="Paid">Paid</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>

                    <!-- Published -->
                    <div class="col-md-3 mb-3">
                        <label for="published_at" class="form-label">Published Date</label>
                        <input type="date" name="published_at" id="published_at" class="form-control"
                            value="{{ old('published_at', now()->toDateString()) }}">
                    </div>

                    <!-- Note -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Note</label>
                        <textarea name="note" id="note" class="form-control" rows="3"></textarea>
                    </div>

                    {{-- Description Points (same style as Rent Vehicle) --}}
                    <div class="col-md-12 mb-3">
                        <div class="card border-secondary">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <strong>Description Points</strong>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="addDescBlockBtn">
                                    + Add Point
                                </button>
                            </div>

                            <div class="card-body" id="descPointsWrapper"></div>

                            <div class="px-3 pb-3 text-muted" style="font-size:12px;">
                                Add main description titles and optional sub points (bullets). This will show in preview/PDF.
                            </div>
                        </div>
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
                                            <option value="LKR" selected>LKR</option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Base Price --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Base Price</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="price" id="price"
                                            class="form-control calc" value="0" required>
                                    </div>
                                </div>

                                {{-- Additional Charges --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Additional Charges</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="additional_charges"
                                            id="additional_charges" class="form-control calc" value="0">
                                    </div>
                                </div>

                                {{-- Discount --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Discount</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="discount" id="discount"
                                            class="form-control calc" value="0">
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

                                {{-- Balance --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Balance</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="balance" id="balance"
                                            class="form-control" value="0" readonly>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-end gap-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-warning" style="width:120px;"
                        onclick="window.location='{{ route('admin.vehicle-bookings.index') }}'">
                        Back
                    </button>

                    <button type="button" class="btn btn-secondary" onclick="previewBooking()"
                        style="width:120px;">Preview</button>
                    <button type="submit" class="btn btn-primary" style="width:120px;">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Preview Modal --}}
    <div class="modal fade" id="bookingPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title">Vehicle Booking Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="bookingPreviewBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="width:130px;">Close</button>
                    <button type="button" class="btn btn-success" onclick="generatePdf()"
                        style="width:130px;">Generate PDF</button>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Global helpers + desc UI (keep OUTSIDE @section('scripts') so onclick works) --}}
<script>
    // ---------- Description UI helpers (must be global for onclick) ----------
    let descIndex = 0;

    function escapeHtml(str) {
        return String(str ?? '')
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function addDescBlock() {
        const wrapper = document.getElementById('descPointsWrapper');
        if (!wrapper) return;

        const idx = descIndex++;

        const block = document.createElement('div');
        block.className = 'border rounded p-3 mb-3';
        block.setAttribute('data-index', idx);

        block.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold">Main Point</div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDescBlock(${idx})">
                    Remove
                </button>
            </div>

            <div class="mb-2">
                <input type="text" class="form-control"
                    name="desc_points[${idx}][title]"
                    placeholder="Title (e.g., Inclusions / Transport Details)">
            </div>

            <div class="subPoints"></div>

            <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                onclick="addSubPoint(${idx})">
                + Add Sub Point
            </button>
        `;

        wrapper.appendChild(block);
        addSubPoint(idx);
    }

    function removeDescBlock(idx) {
        const block = document.querySelector(`#descPointsWrapper [data-index="${idx}"]`);
        if (block) block.remove();
    }

    function addSubPoint(idx) {
        const block = document.querySelector(`#descPointsWrapper [data-index="${idx}"]`);
        if (!block) return;

        const subWrap = block.querySelector('.subPoints');

        const row = document.createElement('div');
        row.className = 'd-flex gap-2 mb-2';

        row.innerHTML = `
            <input type="text" class="form-control"
                name="desc_points[${idx}][subs][]"
                placeholder="Sub point (e.g., Parking charges included)">
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">✕</button>
        `;

        subWrap.appendChild(row);
    }
</script>

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Desc points init
            const addBtn = document.getElementById('addDescBlockBtn');
            if (addBtn) addBtn.addEventListener('click', addDescBlock);
            addDescBlock();

            // Mileage toggle
            const mileageSelect = document.getElementById("mileageSelect");
            const totalKmField = document.getElementById("totalKmField");
            const totalKmInput = document.getElementById("totalKmInput");

            function toggleTotalKm() {
                if (!mileageSelect) return;
                if (mileageSelect.value === "limited") {
                    totalKmField.style.display = "block";
                } else {
                    totalKmField.style.display = "none";
                    totalKmInput.value = "";
                }
            }
            if (mileageSelect) {
                mileageSelect.addEventListener("change", toggleTotalKm);
                toggleTotalKm();
            }

            // Price calc
            const priceInput = document.getElementById("price");
            const additionalInput = document.getElementById("additional_charges");
            const discountInput = document.getElementById("discount");
            const advanceInput = document.getElementById("advance_paid");
            const totalInput = document.getElementById("total_price");
            const balanceInput = document.getElementById("balance");

            function calculateTotal() {
                const price = parseFloat(priceInput.value) || 0;
                const add = parseFloat(additionalInput.value) || 0;
                const discount = parseFloat(discountInput.value) || 0;
                const advance = parseFloat(advanceInput.value) || 0;

                const total = (price + add) - discount;
                const balance = Math.max(0, total - advance);

                totalInput.value = total.toFixed(2);
                balanceInput.value = balance.toFixed(2);
            }

            [priceInput, additionalInput, discountInput, advanceInput].forEach(el => el.addEventListener("input",
                calculateTotal));
            calculateTotal();
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

            const customerName = customerOption ? customerOption.text : '-';
            const customerEmail = customerOption ? customerOption.dataset.email : '-';
            const customerPhone = customerOption ? customerOption.dataset.phone : '-';
            const customerAddress = customerOption ? (customerOption.dataset.address || '-') : '-';

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

            // Description points (render like Rent Vehicle invoice)
            const descBlocks = document.querySelectorAll('#descPointsWrapper [data-index]');
            let descHtml = '';
            let counter = 1;

            descBlocks.forEach((block, index) => {
                const titleInput = block.querySelector(`input[name^="desc_points"][name$="[title]"]`);
                const title = titleInput ? titleInput.value.trim() : '';

                const subInputs = block.querySelectorAll('.subPoints input');
                const subs = Array.from(subInputs).map(i => i.value.trim()).filter(Boolean);

                if (!title && subs.length === 0) return;

                descHtml += `
                    <tr>
                        <td style="padding:12px; text-align:center; border-bottom:1px solid #eee; vertical-align:top;">
                            ${counter}
                        </td>
                        <td style="padding:12px; border-bottom:1px solid #eee;">
                            <div style="font-weight:700; margin-bottom:6px;">
                                ${escapeHtml(title)}
                            </div>
                            ${subs.length > 0 ? `
                                <ul style="margin:0 0 0 18px; padding:0; font-size:12.5px; color:#555; line-height:1.6;">
                                    ${subs.map(s => `<li>${escapeHtml(s)}</li>`).join('')}
                                </ul>
                            ` : ''}
                        </td>
                        ${
                            index === 0
                                ? `<td style="padding:12px; text-align:right; border-bottom:1px solid #eee; vertical-align:top;">
                                        ${price.toFixed(2)}
                                   </td>`
                                : `<td style="padding:12px; border-bottom:1px solid #eee;"></td>`
                        }
                    </tr>
                `;
                counter++;
            });

            if (!descHtml) {
                descHtml = `
                    <tr>
                        <td colspan="3" style="padding:12px; color:#888; text-align:left;">
                            No description points added.
                        </td>
                    </tr>
                `;
            }

            const html = `
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
                    ${escapeHtml(status)}
                </h1>

                <table style="margin-left:auto; margin-top:10px; font-size:13px; border-collapse:collapse;">
                    <tr>
                        <td style="padding:2px 10px; text-align:left; color:#888;">Invoice No:</td>
                        <td style="padding:2px 10px; font-weight:bold;">${escapeHtml(invoiceNo)}</td>
                    </tr>
                    <tr>
                        <td style="padding:2px 10px; text-align:left; color:#888;">Date:</td>
                        <td style="padding:2px 10px;">${invoiceDate}</td>
                    </tr>
                    <tr>
                        <td style="padding:2px 10px; text-align:left; color:#888;">Payment:</td>
                        <td style="padding:2px 10px;">${escapeHtml(paymentStatus)}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Client & Booking Info (Rent Vehicle style) -->
    <table style="width:100%; margin-bottom:40px; font-size:13px;">
        <tr>
            <td style="width:50%; vertical-align:top;">
                <h4 style="text-transform:uppercase; font-size:11px; color:#888; margin-bottom:10px; letter-spacing:1px;">
                    Client Information
                </h4>
                <div style="font-size:15px; font-weight:bold; margin-bottom:5px;">
                    ${escapeHtml(customerName)}
                </div>
                <div style="color:#555;">${escapeHtml(customerAddress)}</div>
                <div style="color:#555;">${escapeHtml(customerEmail || '-')}</div>
                <div style="color:#555;">${escapeHtml(customerPhone || '-')}</div>
            </td>

            <td style="width:50%; vertical-align:top; border-left:1px solid #eee; padding-left:30px;">
                <h4 style="text-transform:uppercase; font-size:11px; color:#888; margin-bottom:10px; letter-spacing:1px;">
                    Booking Information
                </h4>
                <div style="margin-bottom:3px;"><strong>Vehicle:</strong> ${escapeHtml(vehicleName)}</div>
                <div style="margin-bottom:3px;"><strong>Pickup:</strong> ${escapeHtml(pickupDatetime)} (${escapeHtml(pickupLocation)})</div>
                <div style="margin-bottom:3px;"><strong>Drop-off:</strong> ${escapeHtml(dropoffDatetime)} (${escapeHtml(dropoffLocation)})</div>
                <div style="margin-bottom:3px;"><strong>Mileage:</strong> ${escapeHtml(mileage)} ${mileage === 'limited' ? `(${escapeHtml(totalKm)} KM)` : ''}</div>
            </td>
        </tr>
    </table>

    <!-- Vehicle Images -->
    <table style="width:100%;margin-bottom:30px; border-collapse:collapse;">
        <tr>
            <td style="vertical-align:top; padding-right:15px;">
                <h3 style="margin-top:0;">${escapeHtml(vehicleName)}</h3>
                ${note && note.trim() !== '' ? `<p style="color:#666;font-size:13px; white-space:pre-wrap;">${escapeHtml(note)}</p>` : ''}
            </td>

            <td style="width:300px; vertical-align:top;">
                <img src="${mainImage}" style="width:300px;height:200px;object-fit:cover;border:1px solid #ddd;border-radius:4px;">
            </td>

            <td style="width:260px; vertical-align:top; padding-left:10px;">
                ${images.length > 1 ? (function() {
                    let h = '<table style="width:100%; border-collapse:collapse;">';
                    const subImages = images.slice(1,5);
                    for (let i = 0; i < subImages.length; i++) {
                        if (i % 2 === 0) h += '<tr>';
                        h += `<td style="padding:2px;">
                                <img src="${getImageUrl(subImages[i])}" style="width:120px;height:95px;object-fit:cover;border:1px solid #ddd;border-radius:4px;">
                              </td>`;
                        if (i % 2 === 1) h += '</tr>';
                    }
                    if (subImages.length % 2 !== 0) h += '<td></td></tr>';
                    h += '</table>';
                    return h;
                })() : ''}
            </td>
        </tr>
    </table>

    <!-- Description Table (Rent Vehicle style) -->
    <table style="width:100%; border-collapse:collapse; margin-bottom:30px; font-size:14px;">
        <thead>
            <tr style="background:#f9f9f9; border-top:1px solid #333; border-bottom:1px solid #333;">
                <th style="padding:12px; width:50px; text-align:center; text-transform:uppercase; font-size:11px;">No</th>
                <th style="padding:12px; text-align:left; text-transform:uppercase; font-size:11px;">Description</th>
                <th style="padding:12px; text-align:right; text-transform:uppercase; font-size:11px;">
                    Total (${currency})
                </th>
            </tr>
        </thead>
        <tbody>
            ${descHtml}

            ${addCharges > 0 ? `
                <tr>
                    <td style="padding:12px; text-align:center; border-bottom:1px solid #eee;">-</td>
                    <td style="padding:12px; border-bottom:1px solid #eee;">Additional Charges</td>
                    <td style="padding:12px; text-align:right; border-bottom:1px solid #eee;">${addCharges.toFixed(2)}</td>
                </tr>` : ''}

            ${discount > 0 ? `
                <tr>
                    <td style="padding:12px; text-align:center; border-bottom:1px solid #eee;">-</td>
                    <td style="padding:12px; border-bottom:1px solid #eee; color:#888; font-style:italic;">Discount Applied</td>
                    <td style="padding:12px; text-align:right; border-bottom:1px solid #eee; color:#888;">(${discount.toFixed(2)})</td>
                </tr>` : ''}
        </tbody>
    </table>

    <!-- Totals (Rent Vehicle style) -->
    <div style="width:40%; margin-left:auto;">
        <table style="width:100%; font-size:14px; border-collapse:collapse;">
            <tr>
                <td style="padding:8px 0; color:#888;">Subtotal:</td>
                <td style="padding:8px 0; text-align:right;">${total.toFixed(2)}</td>
            </tr>
            <tr>
                <td style="padding:8px 0; color:#888;">Advance Paid:</td>
                <td style="padding:8px 0; text-align:right; color:#1a7f37;">${advancePaid.toFixed(2)}</td>
            </tr>
            <tr style="border-top:1px solid #333;">
                <td style="padding:12px 0; font-weight:bold; font-size:16px;">Balance Due:</td>
                <td style="padding:12px 0; text-align:right; font-weight:bold; font-size:18px; color:#000;">
                    ${currency} ${balance.toFixed(2)}
                </td>
            </tr>
        </table>
    </div>

    <!-- Notes block (same as your Rent Vehicle improved version) -->
    <table style="width:100%; margin-bottom:20px; border-collapse:collapse;">
        <tr>
            <td style="padding:0;">
                ${note && note.trim() !== ''
                    ? `<div style="color:#666; font-size:13px; line-height:1.6; white-space:pre-wrap;">
                            ${escapeHtml(note)}
                       </div>`
                    : `<div style="color:#999; font-size:12px;">No notes provided.</div>`
                }
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div style="margin-top:60px; text-align:center; border-top:1px solid #eee; padding-top:20px; font-size:11px; color:#aaa;">
        <p>www.vacayguider.com | Thank you for your business.</p>
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
