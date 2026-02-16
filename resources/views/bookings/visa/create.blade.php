@extends('layouts.vertical', ['subtitle' => 'Create Visa Booking'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Visa Booking',
        'subtitle' => 'Create',
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Visa Booking</h5>
        </div>

        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="visaBookingForm" action="{{ route('admin.visa-bookings.store') }}" method="POST">
                @csrf

                <div class="row">
                    {{-- Passport --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Passport</label>
                        <select name="passport_id" id="passport_id" class="form-select" required>
                            <option value="">Select Passport</option>
                            @foreach ($passports as $passport)
                                @php
                                    // We pass extra data for preview (no need API call)
                                    $customerName = trim(($passport->first_name ?? '') . ' ' . ($passport->second_name ?? ''));
                                    $address = $passport->address ?? '';
                                    $nationality = $passport->nationality ?? '';
                                    $passportNo = $passport->passport_number ?? '';
                                @endphp
                                <option
                                    value="{{ $passport->id }}"
                                    data-passport-no="{{ $passportNo }}"
                                    data-customer-name="{{ $customerName }}"
                                    data-nationality="{{ $nationality }}"
                                    data-address="{{ $address }}"
                                >
                                    {{ $passportNo }} - {{ $customerName }} ({{ $nationality }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- From → To --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">From → To Country</label>
                        <select id="country_pair" class="form-select" required>
                            <option value="">Select Route</option>
                            @foreach ($visas as $visa)
                                <option value="{{ $visa->from_country }}|{{ $visa->to_country }}">
                                    {{ $visa->from_country }} → {{ $visa->to_country }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Visa Type --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Visa Type</label>
                        <select name="visa_id" id="visa_id" class="form-select" required>
                            <option value="">Select Visa Type</option>
                        </select>
                    </div>

                    {{-- Visa Category --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Visa Category</label>
                        <select name="visa_category_id" id="visa_category_id" class="form-select" required>
                            <option value="">Select Category</option>
                        </select>
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

                    {{-- Agent --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Agent</label>
                        <select name="agent_id" id="agent_id" class="form-select" required>
                            <option value="">Select Agent</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="published_at" class="form-label">Published Date</label>
                        <input type="date" name="published_at" id="published_at" class="form-control"
                            value="{{ old('published_at', now()->toDateString()) }}">
                    </div>

                    {{-- Note --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Note</label>
                        <textarea name="note" id="note" class="form-control" rows="3"
                            placeholder="Add any special notes or remarks (optional)"></textarea>
                    </div>

                    {{-- ✅ Description Points (same UI as Airline Booking) --}}
                    <div class="col-md-12 mb-3">
                        <div class="card border-secondary">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <strong>Description Points</strong>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="addVisaDescBlockBtn">
                                    + Add Point
                                </button>
                            </div>

                            <div class="card-body" id="visaDescPointsWrapper"></div>

                            <div class="px-3 pb-3 text-muted" style="font-size:12px;">
                                Add main titles and optional sub points (bullets). This will show in preview/PDF.
                            </div>
                        </div>
                    </div>

                    {{-- Price & Payment Details --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card border-secondary">
                                <div class="card-header bg-light">
                                    <strong>Price & Payment Details</strong>
                                </div>
                                <div class="card-body">

                                    <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label">Currency</label>
                                        <div class="col-sm-10">
                                            <input type="text" id="currency" name="currency" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label">Base Price</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="base_price" name="base_price" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label">Additional Price</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="additional_price" name="additional_price" class="form-control" value="0">
                                        </div>
                                    </div>

                                    <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label">Discount</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="discount" name="discount" class="form-control" value="0">
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label">Total</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="total_amount" name="total_amount" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label">Advance Paid</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="advanced_paid" name="advanced_paid" class="form-control" value="0">
                                        </div>
                                    </div>

                                    <div class="mb-2 row">
                                        <label class="col-sm-2 col-form-label">Balance</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="balance" name="balance" class="form-control" readonly>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="button" class="btn btn-warning" style="width:130px;"
                        onclick="window.location='{{ route('admin.visa-bookings.index') }}'">
                        Back
                    </button>

                    <button type="button" class="btn btn-secondary me-2" onclick="previewBooking()" style="width: 130px;">
                        Preview
                    </button>

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
                    <h5 class="modal-title">Visa Booking Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="bookingPreviewBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width:130px;">
                        Close
                    </button>
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
    // ---------------- Visa Description UI helpers ----------------
    let visaDescIndex = 0;

    function addVisaDescBlock() {
        const wrapper = document.getElementById('visaDescPointsWrapper');
        if (!wrapper) return;

        const idx = visaDescIndex++;

        const block = document.createElement('div');
        block.className = 'border rounded p-3 mb-3';
        block.setAttribute('data-index', idx);

        block.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold">Main Point</div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVisaDescBlock(${idx})">
                    Remove
                </button>
            </div>

            <div class="mb-2">
                <input type="text" class="form-control"
                    name="desc_points[${idx}][title]"
                    placeholder="Title (e.g., Requirements / Processing / Notes)">
            </div>

            <div class="subPoints"></div>

            <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                onclick="addVisaSubPoint(${idx})">
                + Add Sub Point
            </button>
        `;

        wrapper.appendChild(block);

        // At least 1 sub point
        addVisaSubPoint(idx);
    }

    function removeVisaDescBlock(idx) {
        const block = document.querySelector(`#visaDescPointsWrapper [data-index="${idx}"]`);
        if (block) block.remove();
    }

    function addVisaSubPoint(idx) {
        const block = document.querySelector(`#visaDescPointsWrapper [data-index="${idx}"]`);
        if (!block) return;

        const subWrap = block.querySelector('.subPoints');

        const row = document.createElement('div');
        row.className = 'd-flex gap-2 mb-2';

        row.innerHTML = `
            <input type="text" class="form-control"
                name="desc_points[${idx}][subs][]"
                placeholder="Sub point (e.g., Passport copy required)">
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">✕</button>
        `;

        subWrap.appendChild(row);
    }

    function getVisaDescPointsForPreview() {
        const blocks = document.querySelectorAll('#visaDescPointsWrapper [data-index]');
        const items = [];

        blocks.forEach(block => {
            const titleInput = block.querySelector(`input[name^="desc_points"][name$="[title]"]`);
            const title = (titleInput?.value || '').trim();

            const subs = Array.from(block.querySelectorAll(`input[name*="[subs]"]`))
                .map(i => (i.value || '').trim())
                .filter(Boolean);

            if (title || subs.length) {
                items.push({ title, subs });
            }
        });

        return items;
    }

    function escapeHtml(str) {
        return String(str ?? '')
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // ✅ IMPORTANT FIX: startNo parameter (so numbering continues correctly)
    function renderDescRows(descItems, startNo = 1) {
        let rowNo = startNo;
        let html = '';

        descItems.forEach(item => {
            const title = (item.title || '').trim();
            const subs = Array.isArray(item.subs) ? item.subs.filter(s => (s || '').trim() !== '') : [];

            if (!title && subs.length === 0) return;

            const subHtml = subs.length
                ? `<ul style="margin:0; padding-left:18px; line-height:1.5;">
                    ${subs.map(s => `<li>${escapeHtml(s)}</li>`).join('')}
                   </ul>`
                : '';

            html += `
                <tr>
                    <td style="padding:12px; border-bottom:1px solid #eee; text-align:center; vertical-align:top;">
                        ${rowNo++}
                    </td>
                    <td style="padding:12px; border-bottom:1px solid #eee;">
                        ${title ? `<div style="font-weight:700; margin-bottom:6px;">${escapeHtml(title)}</div>` : ''}
                        ${subHtml}
                    </td>
                    <td style="padding:12px; border-bottom:1px solid #eee; text-align:right; color:#999;">-</td>
                </tr>
            `;
        });

        return { html, nextRowNo: rowNo };
    }

    // Init desc UI
    const addVisaDescBtn = document.getElementById('addVisaDescBlockBtn');
    if (addVisaDescBtn) addVisaDescBtn.addEventListener('click', addVisaDescBlock);
    addVisaDescBlock();

    // ---------------- Existing AJAX logic ----------------
    document.getElementById('country_pair').addEventListener('change', function() {
        const [from, to] = this.value.split('|');

        fetch(`/admin/ajax/visas/by-country?from_country=${from}&to_country=${to}`)
            .then(res => res.json())
            .then(data => {
                const visaSelect = document.getElementById('visa_id');
                visaSelect.innerHTML = '<option value="">Select Visa Type</option>';
                data.forEach(v => {
                    visaSelect.innerHTML += `<option value="${v.id}">${v.visa_type}</option>`;
                });
            });

        fetch(`/admin/ajax/agents/by-country?from_country=${from}&to_country=${to}`)
            .then(res => res.json())
            .then(data => {
                const agentSelect = document.getElementById('agent_id');
                agentSelect.innerHTML = '<option value="">Select Agent</option>';

                if (!data.length) {
                    agentSelect.innerHTML += `<option disabled>No agents available</option>`;
                    return;
                }

                data.forEach(agent => {
                    agentSelect.innerHTML += `
                        <option value="${agent.id}">
                            ${agent.name} (${agent.company_name ?? 'N/A'})
                        </option>
                    `;
                });
            });
    });

    document.getElementById('visa_id').addEventListener('change', function() {
        fetch(`/admin/ajax/visa/${this.value}/categories`)
            .then(res => res.json())
            .then(data => {
                const cat = document.getElementById('visa_category_id');
                cat.innerHTML = '<option value="">Select Category</option>';

                data.forEach(c => {
                    cat.innerHTML += `
                        <option value="${c.id}"
                            data-price="${c.price}"
                            data-currency="${c.currency}">
                            ${c.visa_type} (${c.processing_time})
                        </option>`;
                });
            });
    });

    document.getElementById('visa_category_id').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        document.getElementById('base_price').value = opt?.dataset?.price || 0;
        document.getElementById('currency').value = opt?.dataset?.currency || '';
        calculate();
    });

    ['additional_price', 'discount', 'advanced_paid'].forEach(id => {
        document.getElementById(id).addEventListener('input', calculate);
    });

    function calculate() {
        const base = +base_price.value || 0;
        const add = +additional_price.value || 0;
        const disc = +discount.value || 0;
        const adv = +advanced_paid.value || 0;

        const total = base + add - disc;
        total_amount.value = total;
        balance.value = total - adv;
    }

    // ---------------- Preview (FIXED ORDER + FIXED numbering) ----------------
    function previewBooking() {
        const passportSelect = document.getElementById('passport_id');
        const passportOpt = passportSelect?.options[passportSelect.selectedIndex];

        const customerName = passportOpt?.dataset?.customerName || '-';
        const passportNo = passportOpt?.dataset?.passportNo || '-';
        const nationality = passportOpt?.dataset?.nationality || '-';
        const address = passportOpt?.dataset?.address || '';

        const countryPair = (document.getElementById('country_pair').value || '|').split('|');
        const fromCountry = countryPair[0] || '-';
        const toCountry = countryPair[1] || '-';

        const visaTypeSelect = document.getElementById('visa_id');
        const visaCategorySelect = document.getElementById('visa_category_id');
        const visaTypeText = visaTypeSelect?.options[visaTypeSelect.selectedIndex]?.text || '-';
        const visaCategoryText = visaCategorySelect?.options[visaCategorySelect.selectedIndex]?.text || '-';

        const agentSelect = document.getElementById('agent_id');
        const agentText = agentSelect?.options[agentSelect.selectedIndex]?.text || '-';

        const note = document.getElementById('note')?.value || '';

        const basePrice = parseFloat(document.getElementById('base_price').value) || 0;
        const addChargesVal = parseFloat(document.getElementById('additional_price').value) || 0;
        const discountVal = parseFloat(document.getElementById('discount').value) || 0;
        const advancedPaid = parseFloat(document.getElementById('advanced_paid').value) || 0;
        const currency = document.getElementById('currency').value || 'LKR';

        const total = basePrice + addChargesVal - discountVal;
        const balanceVal = total - advancedPaid;

        const status = document.querySelector('select[name="status"]').value || '-';
        const paymentStatus = document.querySelector('select[name="payment_status"]').value || '-';
        const bookingDate = new Date().toLocaleDateString('en-GB');

        // ✅ Numbering system
        let rowNo = 1;

        // ✅ Visa Charges always No = 1
        const baseRow = `
            <tr>
                <td style="padding:12px; border-bottom:1px solid #eee; text-align:center;">${rowNo++}</td>
                <td style="padding:12px; border-bottom:1px solid #eee;">Visa Charges</td>
                <td style="padding:12px; text-align:right; border-bottom:1px solid #eee;">${basePrice.toFixed(2)}</td>
            </tr>
        `;

        // ✅ Description starts from rowNo (which is now 2)
        const descItems = getVisaDescPointsForPreview();
        const descRendered = renderDescRows(descItems, rowNo);
        rowNo = descRendered.nextRowNo;

        const addRow = addChargesVal > 0 ? `
            <tr>
                <td style="padding:12px; border-bottom:1px solid #eee; text-align:center;">${rowNo++}</td>
                <td style="padding:12px; border-bottom:1px solid #eee;">Additional Services / Charges</td>
                <td style="padding:12px; text-align:right; border-bottom:1px solid #eee;">${addChargesVal.toFixed(2)}</td>
            </tr>` : '';

        const discRow = discountVal > 0 ? `
            <tr>
                <td style="padding:12px; border-bottom:1px solid #eee; text-align:center; color:#888;">${rowNo++}</td>
                <td style="padding:12px; border-bottom:1px solid #eee; color:#888; font-style:italic;">Discount Applied</td>
                <td style="padding:12px; text-align:right; border-bottom:1px solid #eee; color:#888;">(${discountVal.toFixed(2)})</td>
            </tr>` : '';

        const html = `
<div style="max-width:800px;margin:0 auto;font-family:'Helvetica Neue',Arial,sans-serif;color:#333;background:#fff;padding:25px;">

    <!-- HEADER -->
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
                    ${escapeHtml(String(status).toUpperCase())}
                </h1>
                <table style="margin-left:auto;margin-top:10px;font-size:13px;">
                    <tr>
                        <td style="color:#888;padding:2px 10px;text-align:left;">Booking Date:</td>
                        <td style="text-align:left;">${escapeHtml(bookingDate)}</td>
                    </tr>
                    <tr>
                        <td style="color:#888;padding:2px 10px;text-align:left;">Payment Status:</td>
                        <td style="text-align:left;">${escapeHtml(paymentStatus)}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- CUSTOMER / VISA INFO -->
    <table style="width:100%;margin-bottom:35px;font-size:13px;">
        <tr>
            <td style="width:50%;vertical-align:top;">
                <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">
                    Customer / Passport Information
                </h4>
                <div style="font-size:15px;font-weight:bold;margin-bottom:6px;">${escapeHtml(customerName)}</div>
                <div style="font-size:13px;color:#555;line-height:1.6;">
                    <div><strong>Passport No:</strong> ${escapeHtml(passportNo)}</div>
                    <div><strong>Nationality:</strong> ${escapeHtml(nationality)}</div>
                    ${address ? `<div><strong>Address:</strong> ${escapeHtml(address)}</div>` : ``}
                </div>
            </td>

            <td style="width:50%;vertical-align:top;border-left:1px solid #eee;padding-left:25px;">
                <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">
                    Visa Details
                </h4>
                <div style="font-size:13px;color:#555;line-height:1.7;">
                    <div><strong>Route:</strong> ${escapeHtml(fromCountry)} → ${escapeHtml(toCountry)}</div>
                    <div><strong>Visa Type:</strong> ${escapeHtml(visaTypeText)}</div>
                    <div><strong>Visa Category:</strong> ${escapeHtml(visaCategoryText)}</div>
                    <div><strong>Agent:</strong> ${escapeHtml(agentText)}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- DESCRIPTION TABLE -->
    <table style="width:100%; border-collapse:collapse; margin-bottom:30px; font-size:14px;">
        <thead>
            <tr style="background:#f9f9f9; border-top:1px solid #333; border-bottom:1px solid #333;">
                <th style="padding:12px; width:50px; text-align:center; text-transform:uppercase; font-size:11px;">No</th>
                <th style="padding:12px; text-align:left; text-transform:uppercase; font-size:11px;">Description</th>
                <th style="padding:12px; text-align:right; text-transform:uppercase; font-size:11px;">
                    Total (${escapeHtml(currency)})
                </th>
            </tr>
        </thead>
        <tbody>
            ${baseRow}
            ${descRendered.html}
            ${addRow}
            ${discRow}
        </tbody>
    </table>

    <!-- TOTALS -->
    <div style="width:40%;margin-left:auto;">
        <table style="width:100%;font-size:14px;">
            <tr>
                <td style="padding:8px 0;color:#888;">Total</td>
                <td style="padding:8px 0;text-align:right;">${total.toFixed(2)}</td>
            </tr>
            <tr>
                <td style="padding:8px 0;color:#198754;">Advanced Paid</td>
                <td style="padding:8px 0;text-align:right;color:#198754;">${advancedPaid.toFixed(2)}</td>
            </tr>
            <tr style="border-top:1px solid #333;">
                <td style="padding:12px 0;font-weight:bold;font-size:16px;">Balance</td>
                <td style="padding:12px 0;text-align:right;font-weight:bold;font-size:18px;">
                    ${escapeHtml(currency)} ${balanceVal.toFixed(2)}
                </td>
            </tr>
        </table>
    </div>

    ${note ? `
        <div style="margin-top:30px;">
            <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">Note</h4>
            <div style="font-size:14px; padding:12px; border:1px solid #eee; background:#f9f9f9;">
                ${escapeHtml(note).replace(/\n/g, '<br>')}
            </div>
        </div>
    ` : ''}

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

        fetch("{{ route('admin.visa-bookings.generatePdf') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ html: htmlContent })
        })
        .then(response => {
            if (!response.ok) throw new Error('PDF generation failed');
            return response.blob();
        })
        .then(blob => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'Visa_Booking.pdf';
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
