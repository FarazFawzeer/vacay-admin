@extends('layouts.vertical', ['subtitle' => 'Edit Vehicle Booking'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Vehicle Booking',
        'subtitle' => 'Edit',
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Vehicle Booking</h5>
        </div>

        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @php
    $existingDesc = [];

    // ✅ correct column: desc_points
    if (!empty($booking->desc_points)) {
        // If model cast is set => already array
        if (is_array($booking->desc_points)) {
            $existingDesc = $booking->desc_points;
        } else {
            // If saved as JSON string
            $existingDesc = json_decode($booking->desc_points, true) ?: [];
        }
    }
@endphp


            <form id="vehicleBookingForm" action="{{ route('admin.vehicle-bookings.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" id="invoice_no" value="{{ $booking->inv_no ?? 'VB-0001' }}">

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
                                    data-address="{{ $customer->address ?? 'N/A' }}"
                                    {{ $booking->customer_id == $customer->id ? 'selected' : '' }}>
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
                                    if ($vehicle->vehicle_image) $images[] = $vehicle->vehicle_image;
                                    if (!empty($vehicle->sub_image) && is_array($vehicle->sub_image)) $images = array_merge($images, $vehicle->sub_image);
                                    $images = $images ?: [null];
                                @endphp
                                <option value="{{ $vehicle->id }}"
                                    data-images='@json($images)'
                                    {{ $booking->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->name }} - {{ $vehicle->vehicle_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pickup & Drop-off -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Pickup Location</label>
                        <input type="text" name="pickup_location" id="pickup_location" class="form-control"
                            placeholder="e.g., Colombo Airport" value="{{ old('pickup_location', $booking->pickup_location) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Pickup Date & Time</label>
                        <input type="datetime-local" name="pickup_datetime" id="pickup_datetime" class="form-control"
                            value="{{ old('pickup_datetime', $booking->pickup_datetime ? $booking->pickup_datetime->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Drop-off Location</label>
                        <input type="text" name="dropoff_location" id="dropoff_location" class="form-control"
                            placeholder="e.g., Kandy City Centre" value="{{ old('dropoff_location', $booking->dropoff_location) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Drop-off Date & Time</label>
                        <input type="datetime-local" name="dropoff_datetime" id="dropoff_datetime" class="form-control"
                            value="{{ old('dropoff_datetime', $booking->dropoff_datetime ? $booking->dropoff_datetime->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <!-- Mileage -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Mileage</label>
                        <select name="mileage" id="mileageSelect" class="form-select" required>
                            <option value="unlimited" {{ old('mileage', $booking->mileage) == 'unlimited' ? 'selected' : '' }}>Unlimited</option>
                            <option value="limited" {{ old('mileage', $booking->mileage) == 'limited' ? 'selected' : '' }}>Limited</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3" id="totalKmField" style="display:none;">
                        <label class="form-label">Total KM</label>
                        <input type="number" name="total_km" id="totalKmInput" class="form-control"
                            placeholder="Enter total KM" value="{{ old('total_km', $booking->total_km) }}">
                    </div>

                    <!-- Payment & Status -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-select" required>
                            <option value="pending" {{ old('payment_status', $booking->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="partial" {{ old('payment_status', $booking->payment_status) == 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="paid" {{ old('payment_status', $booking->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            @foreach (['Quotation', 'Accepted', 'Invoiced', 'Partially Paid', 'Paid', 'Cancelled'] as $s)
                                <option value="{{ $s }}" {{ old('status', $booking->status) == $s ? 'selected' : '' }}>
                                    {{ $s }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="published_at" class="form-label">Published Date</label>
                        <input type="date" name="published_at" id="published_at" class="form-control"
                            value="{{ old('published_at', optional($booking->published_at)->format('Y-m-d') ?? now()->toDateString()) }}">
                    </div>

                    <!-- Notes -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="note" id="note" class="form-control" rows="3">{{ old('note', $booking->note) }}</textarea>
                    </div>

                    {{-- Description Points (LIKE RENT VEHICLE) --}}
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
                                            <option value="LKR" {{ old('currency', $booking->currency) == 'LKR' ? 'selected' : '' }}>LKR</option>
                                            <option value="USD" {{ old('currency', $booking->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                            <option value="EUR" {{ old('currency', $booking->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Base Price --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Base Price</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="price" id="price"
                                            class="form-control calc" value="{{ old('price', $booking->price ?? 0) }}" required>
                                    </div>
                                </div>

                                {{-- Additional Charges --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Additional Charges</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="additional_charges" id="additional_charges"
                                            class="form-control calc" value="{{ old('additional_charges', $booking->additional_charges ?? 0) }}">
                                    </div>
                                </div>

                                {{-- Discount --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Discount</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="discount" id="discount"
                                            class="form-control calc" value="{{ old('discount', $booking->discount ?? 0) }}">
                                    </div>
                                </div>

                                <hr>

                                {{-- Total Price --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Total Price</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="total_price" id="total_price"
                                            class="form-control" value="{{ old('total_price', $booking->total_price ?? 0) }}" readonly required>
                                    </div>
                                </div>

                                {{-- Advance Paid --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Advance Paid</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="advance_paid" id="advance_paid"
                                            class="form-control calc" value="{{ old('advance_paid', $booking->advance_paid ?? 0) }}">
                                    </div>
                                </div>

                                {{-- Balance --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Balance</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" id="balance" class="form-control"
                                            value="{{ number_format(max(0, ($booking->total_price ?? 0) - ($booking->advance_paid ?? 0)),2,'.','') }}"
                                            readonly>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-end gap-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-warning" style="width:120px;"
                        onclick="window.location='{{ route('admin.vehicle-bookings.index') }}'">Back</button>

                    <button type="button" class="btn btn-secondary" onclick="previewBooking()" style="width:120px;">Preview</button>
                    <button type="submit" class="btn btn-primary" style="width:120px;">Update</button>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width:130px;">Close</button>
                    <button type="button" class="btn btn-success" onclick="generatePdf()" style="width:130px;">Generate PDF</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Expose existing description to JS --}}
    <script>
        window.EXISTING_DESC_POINTS = @json($existingDesc);
    </script>

    <script>
        // ----------------- Helpers (same as rent vehicle) -----------------
        let descIndex = 0;

        function escapeHtml(str) {
            return String(str ?? '')
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function addDescBlock(prefill = null) {
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
                        placeholder="Title (e.g., Inclusions / Vehicle & Driver)">
                </div>

                <div class="subPoints"></div>

                <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                    onclick="addSubPoint(${idx})">
                    + Add Sub Point
                </button>
            `;

            wrapper.appendChild(block);

            // Prefill
            if (prefill && typeof prefill === 'object') {
                const titleInput = block.querySelector(`input[name="desc_points[${idx}][title]"]`);
                if (titleInput) titleInput.value = prefill.title ?? '';

                const subs = Array.isArray(prefill.subs) ? prefill.subs : [];
                if (subs.length) {
                    subs.forEach(s => addSubPoint(idx, s));
                } else {
                    addSubPoint(idx);
                }
            } else {
                addSubPoint(idx);
            }
        }

        function removeDescBlock(idx) {
            const block = document.querySelector(`#descPointsWrapper [data-index="${idx}"]`);
            if (block) block.remove();
        }

        function addSubPoint(idx, value = '') {
            const block = document.querySelector(`#descPointsWrapper [data-index="${idx}"]`);
            if (!block) return;

            const subWrap = block.querySelector('.subPoints');
            const row = document.createElement('div');
            row.className = 'd-flex gap-2 mb-2';

            row.innerHTML = `
                <input type="text" class="form-control"
                    name="desc_points[${idx}][subs][]"
                    placeholder="Sub point (e.g., Fuel included)" value="${escapeHtml(value)}">
                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">✕</button>
            `;

            subWrap.appendChild(row);
        }

        document.addEventListener("DOMContentLoaded", function() {
            // add button
            const addBtn = document.getElementById('addDescBlockBtn');
            if (addBtn) addBtn.addEventListener('click', () => addDescBlock());

            // load existing desc
            const existing = Array.isArray(window.EXISTING_DESC_POINTS) ? window.EXISTING_DESC_POINTS : [];
            if (existing.length) {
                existing.forEach(item => addDescBlock(item));
            } else {
                addDescBlock();
            }

            // mileage toggle
            const mileageSelect = document.getElementById("mileageSelect");
            const totalKmField = document.getElementById("totalKmField");
            const totalKmInput = document.getElementById("totalKmInput");

            function toggleTotalKm() {
                if (mileageSelect && mileageSelect.value === "limited") {
                    totalKmField.style.display = "block";
                } else {
                    totalKmField.style.display = "none";
                    if (totalKmInput) totalKmInput.value = "";
                }
            }
            if (mileageSelect) {
                mileageSelect.addEventListener("change", toggleTotalKm);
                toggleTotalKm();
            }

            // totals calculation (same style)
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
                const balance = total - advance;

                totalInput.value = total.toFixed(2);
                balanceInput.value = balance > 0 ? balance.toFixed(2) : '0.00';
            }

            [priceInput, additionalInput, discountInput, advanceInput].forEach(el => el.addEventListener('input', calculateTotal));
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

            // images
            let images = [];
            if (vehicleOption && vehicleOption.dataset.images) {
                try { images = JSON.parse(vehicleOption.dataset.images); } catch (e) { images = []; }
            }

            const mainImage = images.length ? getImageUrl(images[0]) : "https://via.placeholder.com/300x200?text=No+Image";

            // pickup/drop
            const pickupLocation = document.getElementById('pickup_location')?.value || '-';
            const pickupDatetime = document.getElementById('pickup_datetime')?.value || '-';
            const dropoffLocation = document.getElementById('dropoff_location')?.value || '-';
            const dropoffDatetime = document.getElementById('dropoff_datetime')?.value || '-';

            // mileage
            const mileage = document.getElementById('mileageSelect')?.value || '-';
            const totalKm = document.getElementById('totalKmInput')?.value || '-';

            // prices
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

            // description points table rows (same as rent vehicle)
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
                        ${index === 0
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

    <!-- Header -->
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

    <!-- Client & Booking Info -->
    <table style="width:100%; margin-bottom:40px; font-size:13px;">
        <tr>
            <td style="width:50%; vertical-align:top;">
                <h4 style="text-transform:uppercase; font-size:11px; color:#888; margin-bottom:10px; letter-spacing:1px;">Client Information</h4>
                <div style="font-size:15px; font-weight:bold; margin-bottom:5px;">${escapeHtml(customerName)}</div>
                <div style="color:#555;">${escapeHtml(customerAddress)}</div>
                <div style="color:#555;">${escapeHtml(customerEmail || '-')}</div>
                <div style="color:#555;">${escapeHtml(customerPhone || '-')}</div>
            </td>

            <td style="width:50%; vertical-align:top; border-left:1px solid #eee; padding-left:30px;">
                <h4 style="text-transform:uppercase; font-size:11px; color:#888; margin-bottom:10px; letter-spacing:1px;">Booking Information</h4>
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
                ${note ? `<p style="color:#666;font-size:13px; white-space:pre-wrap;">${escapeHtml(note)}</p>` : ''}
            </td>

            <td style="width:300px; vertical-align:top;">
                <img src="${mainImage}" style="width:300px;height:200px;object-fit:cover;border:1px solid #ddd;border-radius:4px;">
            </td>

            <td style="width:260px; vertical-align:top; padding-left:10px;">
                ${images.length > 1 ? (function() {
                    let html = '<table style="width:100%; border-collapse:collapse;">';
                    const subImages = images.slice(1,5);
                    for (let i = 0; i < subImages.length; i++) {
                        if (i % 2 === 0) html += '<tr>';
                        html += `<td style="padding:2px;">
                                <img src="${getImageUrl(subImages[i])}" style="width:120px;height:95px;object-fit:cover;border:1px solid #ddd;border-radius:4px;">
                            </td>`;
                        if (i % 2 === 1) html += '</tr>';
                    }
                    if (subImages.length % 2 !== 0) html += '<td></td></tr>';
                    html += '</table>';
                    return html;
                })() : ''}
            </td>
        </tr>
    </table>

    <!-- Description Table -->
    <table style="width:100%; border-collapse:collapse; margin-bottom:30px; font-size:14px;">
        <thead>
            <tr style="background:#f9f9f9; border-top:1px solid #333; border-bottom:1px solid #333;">
                <th style="padding:12px; width:50px; text-align:center; text-transform:uppercase; font-size:11px;">No</th>
                <th style="padding:12px; text-align:left; text-transform:uppercase; font-size:11px;">Description</th>
                <th style="padding:12px; text-align:right; text-transform:uppercase; font-size:11px;">Total (${currency})</th>
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

    <!-- Totals -->
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

    <table style="width:100%; margin-bottom:20px; border-collapse:collapse;">
        <tr>
            <td style="padding:0;">
                ${note && note.trim() !== ''
                    ? `<div style="color:#666; font-size:13px; line-height:1.6; white-space:pre-wrap;">${escapeHtml(note)}</div>`
                    : `<div style="color:#999; font-size:12px;">No notes provided.</div>`
                }
            </td>
        </tr>
    </table>

    <div style="margin-top:60px; text-align:center; border-top:1px solid #eee; padding-top:20px; font-size:11px; color:#aaa;">
        <p>www.vacayguider.com | Thank you for your business.</p>
    </div>

</div>`;

            document.getElementById('bookingPreviewBody').innerHTML = html;
            new bootstrap.Modal(document.getElementById('bookingPreviewModal')).show();
        }

        function generatePdf() {
            const htmlContent = document.getElementById('bookingPreviewBody').innerHTML;

            fetch("{{ route('admin.vehicle-bookings.generatePdf') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ html: htmlContent })
            })
            .then(res => res.blob())
            .then(blob => {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `vehicle_booking_{{ $booking->inv_no ?? 'invoice' }}.pdf`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(url);
            })
            .catch(err => {
                console.error(err);
                alert('PDF generation failed');
            });
        }
    </script>
@endsection
