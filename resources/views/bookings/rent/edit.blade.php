@extends('layouts.vertical', ['subtitle' => 'Edit Rent Vehicle Booking'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Rent Vehicle Booking',
        'subtitle' => 'Edit',
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Rent Vehicle Booking</h5>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.rent-vehicle-bookings.update', $booking->id) }}" method="POST" id="rentBookingForm">
                @csrf
                @method('PUT')

                <input type="hidden" id="invoice_no" value="{{ $booking->inv_no ?? 'INV0001' }}">

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
                                    $images = $vehicle->vehicle_image ? [$vehicle->vehicle_image] : [];
                                    if (!empty($vehicle->sub_image) && is_array($vehicle->sub_image)) {
                                        $images = array_merge($images, $vehicle->sub_image);
                                    }
                                    $images = $images ?: [null];
                                @endphp
                                <option value="{{ $vehicle->id }}" data-images='@json($images)'
                                    {{ $booking->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->vehicle_name ?? $vehicle->name }} -
                                    {{ $vehicle->vehicle_number ?? $vehicle->vehicle_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Start & End -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Start Date & Time</label>
                        <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-control"
                            value="{{ date('Y-m-d\TH:i', strtotime($booking->start_datetime)) }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">End Date & Time</label>
                        <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control"
                            value="{{ date('Y-m-d\TH:i', strtotime($booking->end_datetime)) }}" required>
                    </div>

                    <!-- Status / Payment -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Booking Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Quotation" {{ $booking->status == 'Quotation' ? 'selected' : '' }}>Quotation</option>
                            <option value="Accepted" {{ $booking->status == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="Invoiced" {{ $booking->status == 'Invoiced' ? 'selected' : '' }}>Invoiced</option>
                            <option value="Partially Paid" {{ $booking->status == 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                            <option value="Paid" {{ $booking->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Cancelled" {{ $booking->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-select" required>
                            <option value="unpaid" {{ $booking->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="partial" {{ $booking->payment_status == 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="published_at" class="form-label">Published Date</label>
                        <input type="date" name="published_at" id="published_at" class="form-control"
                            value="{{ $booking->published_at?->format('Y-m-d') ?? now()->toDateString() }}">
                    </div>

                    <!-- Notes -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="notes" rows="4" class="form-control">{{ $booking->notes }}</textarea>
                    </div>

                    {{-- Description Points --}}
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
                                            <option value="LKR" {{ $booking->currency == 'LKR' ? 'selected' : '' }}>LKR</option>
                                            <option value="USD" {{ $booking->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                            <option value="EUR" {{ $booking->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Base Price --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Base Price</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="price" id="price"
                                            class="form-control calc" value="{{ $booking->price ?? 0 }}" required>
                                    </div>
                                </div>

                                {{-- Additional Charges --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Additional Charges</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="additional_price" id="additional_price"
                                            class="form-control calc" value="{{ $booking->additional_price ?? 0 }}">
                                    </div>
                                </div>

                                {{-- Discount --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Discount</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="discount" id="discount"
                                            class="form-control calc" value="{{ $booking->discount ?? 0 }}">
                                    </div>
                                </div>

                                <hr>

                                {{-- Total Price --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Total Price</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="total_price" id="total_price"
                                            class="form-control" value="{{ $booking->total_price ?? 0 }}" readonly required>
                                    </div>
                                </div>

                                {{-- Advance Paid --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Advance Paid</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" name="advance_paid" id="advance_paid"
                                            class="form-control calc" value="{{ $booking->advance_paid ?? 0 }}">
                                    </div>
                                </div>

                                {{-- Balance Amount --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Balance Amount</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" id="balance_amount" class="form-control"
                                            value="{{ max(0, ($booking->total_price ?? 0) - ($booking->advance_paid ?? 0)) }}" readonly>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-end gap-2 d-flex justify-content-end">
                    <a href="{{ route('admin.rent-vehicle-bookings.index') }}" class="btn btn-light" style="width:130px;">Back</a>
                    <button type="button" class="btn btn-secondary" onclick="previewBooking()" style="width:130px;">Preview</button>
                    <button type="submit" class="btn btn-primary" style="width:130px;">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Preview Modal --}}
    <div class="modal fade" id="bookingPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title">Rent Vehicle Booking Preview</h5>
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
@endsection

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
                    placeholder="Title (e.g., Inclusions / Vehicle & Driver)"
                    value="${prefill?.title ? escapeHtml(prefill.title) : ''}">
            </div>

            <div class="subPoints"></div>

            <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addSubPoint(${idx})">
                + Add Sub Point
            </button>
        `;

        wrapper.appendChild(block);

        const subs = Array.isArray(prefill?.subs) ? prefill.subs : [];
        if (subs.length) {
            subs.forEach(s => addSubPoint(idx, s));
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
                placeholder="Sub point (e.g., Fuel included)"
                value="${value ? escapeHtml(value) : ''}">
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">✕</button>
        `;

        subWrap.appendChild(row);
    }
</script>

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // ✅ init desc points from booking JSON
        const existing = @json($booking->desc_points ?? []);
        if (Array.isArray(existing) && existing.length) {
            existing.forEach(row => addDescBlock(row));
        } else {
            addDescBlock();
        }

        const addBtn = document.getElementById('addDescBlockBtn');
        if (addBtn) addBtn.addEventListener('click', () => addDescBlock());

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

        [priceInput, additionalInput, discountInput, advanceInput].forEach(el => el.addEventListener('input', calculateAmounts));
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
        const customerAddress = customerOption.dataset.address || '-';

        const vehicleName = vehicleOption.text;

        let images = [];
        if (vehicleOption.dataset.images) {
            try { images = JSON.parse(vehicleOption.dataset.images); } catch (e) { images = []; }
        }

        const mainImage = images.length ? getImageUrl(images[0]) : "https://via.placeholder.com/300x200?text=No+Image";

        const startDatetime = document.getElementById('start_datetime').value || '-';
        const endDatetime = document.getElementById('end_datetime').value || '-';

        const price = parseFloat(document.getElementById('price').value) || 0;
        const addCharges = parseFloat(document.getElementById('additional_price').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const advancePaid = parseFloat(document.getElementById('advance_paid').value) || 0;

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
                            </ul>` : ''
                        }
                    </td>
                    ${
                        index === 0
                            ? `<td style="padding:12px; text-align:right; border-bottom:1px solid #eee; vertical-align:top;">
                                    ${price.toFixed(2)}
                               </td>`
                            : `<td style="padding:12px; border-bottom:1px solid #eee;"></td>`
                    }
                </tr>`;
            counter++;
        });

        if (!descHtml) {
            descHtml = `
                <tr>
                    <td colspan="3" style="padding:12px; color:#888; text-align:left;">
                        No description points added.
                    </td>
                </tr>`;
        }

        const total = Math.max(0, (price + addCharges) - discount);
        const balance = Math.max(0, total - advancePaid);

        const currency = document.getElementById('currency').value || 'LKR';
        const paymentStatus = document.getElementById('payment_status').value;
        const status = document.getElementById('status').value;
        const note = document.getElementById('notes').value;

        const invoiceNo = document.getElementById('invoice_no').value || '-';
        const invoiceDate = new Date().toLocaleDateString('en-GB');

        const html = `
<div style="max-width:800px; margin:0 auto; font-family:'Helvetica Neue', Helvetica, Arial, sans-serif; color:#333; background:#fff; padding:20px;">

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
                <div style="margin-bottom:3px;"><strong>Start:</strong> ${escapeHtml(startDatetime)}</div>
                <div style="margin-bottom:3px;"><strong>End:</strong> ${escapeHtml(endDatetime)}</div>
            </td>
        </tr>
    </table>

    <table style="width:100%;margin-bottom:30px; border-collapse:collapse;">
        <tr>
            <td style="vertical-align:top; padding-right:15px;">
                <h3 style="margin-top:0;">${escapeHtml(vehicleName)}</h3>
                ${note && note.trim() !== ''
                    ? `<p style="color:#666;font-size:13px; white-space:pre-wrap;">${escapeHtml(note)}</p>`
                    : ''
                }
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
                    ? `<div style="color:#666; font-size:13px; line-height:1.6; white-space:pre-wrap;">
                            ${escapeHtml(note)}
                       </div>`
                    : `<div style="color:#999; font-size:12px;">No notes provided.</div>`}
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

        fetch("{{ route('admin.rent-vehicle-bookings.generatePdf') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ html: htmlContent })
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
