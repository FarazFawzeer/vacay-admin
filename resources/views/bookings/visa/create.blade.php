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
            <form id="visaBookingForm" action="{{ route('admin.visa-bookings.store') }}" method="POST">
                @csrf

                <div class="row">

                    {{-- Passport --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Passport</label>
                        <select name="passport_id" class="form-select" required>
                            <option value="">Select Passport</option>
                            @foreach ($passports as $passport)
                                <option value="{{ $passport->id }}">
                                    {{ $passport->passport_number }} -
                                    {{ $passport->first_name }} {{ $passport->second_name }}
                                    ({{ $passport->nationality }})
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
                            <option value="Accepted<">Accepted</option>
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
                                        <input type="text" id="currency" name="currency" class="form-control" readonly>
                                    </div>
                                </div>

                                {{-- Base Price --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Base Price</label>
                                    <div class="col-sm-10">
                                        <input type="number" id="base_price" name="base_price" class="form-control"
                                            readonly>
                                    </div>
                                </div>

                                {{-- Additional Price --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Additional Price</label>
                                    <div class="col-sm-10">
                                        <input type="number" id="additional_price" name="additional_price"
                                            class="form-control" value="0">
                                    </div>
                                </div>

                                {{-- Discount --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Discount</label>
                                    <div class="col-sm-10">
                                        <input type="number" id="discount" name="discount" class="form-control"
                                            value="0">
                                    </div>
                                </div>

                                <hr>

                                {{-- Total --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Total</label>
                                    <div class="col-sm-10">
                                        <input type="number" id="total_amount" name="total_amount" class="form-control"
                                            readonly>
                                    </div>
                                </div>

                                {{-- Advance Paid --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Advance Paid</label>
                                    <div class="col-sm-10">
                                        <input type="number" id="advanced_paid" name="advanced_paid" class="form-control"
                                            value="0">
                                    </div>
                                </div>

                                {{-- Balance --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Balance</label>
                                    <div class="col-sm-10">
                                        <input type="number" id="balance" name="balance" class="form-control"
                                            readonly>
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

                    <button type="button" class="btn btn-secondary me-2" onclick="previewBooking()"
                        style="width: 130px;">Preview</button>
                    <button type="submit" class="btn btn-primary" style="width: 130px;">Save </button>
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
        // From → To change
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

                    document.getElementById('visa_category_id').innerHTML =
                        '<option value="">Select Category</option>';
                });
        });

        // Visa Type change
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

        // Category change → price
        document.getElementById('visa_category_id').addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            document.getElementById('base_price').value = opt.dataset.price || 0;
            document.getElementById('currency').value = opt.dataset.currency || '';
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

        // Preview Booking
        function previewBooking() {
            const passportSelect = document.querySelector('select[name="passport_id"]');
            const countryPairSelect = document.getElementById('country_pair');
            const visaTypeSelect = document.getElementById('visa_id');
            const visaCategorySelect = document.getElementById('visa_category_id');

            const passportOption = passportSelect.options[passportSelect.selectedIndex];
            const countryPair = countryPairSelect.value.split('|');
            const visaTypeOption = visaTypeSelect.options[visaTypeSelect.selectedIndex];
            const visaCategoryOption = visaCategorySelect.options[visaCategorySelect.selectedIndex];

            const passportNumber = passportOption ? passportOption.text : '-';
            const fromCountry = countryPair[0] || '-';
            const toCountry = countryPair[1] || '-';
            const visaType = visaTypeOption ? visaTypeOption.text : '-';
            const visaCategory = visaCategoryOption ? visaCategoryOption.text : '-';

            const basePrice = parseFloat(document.getElementById('base_price').value) || 0;
            const additionalPrice = parseFloat(document.getElementById('additional_price').value) || 0;
            const discount = parseFloat(document.getElementById('discount').value) || 0;
            const advancedPaid = parseFloat(document.getElementById('advanced_paid').value) || 0;
            const total = basePrice + additionalPrice - discount;
            const balance = total - advancedPaid;
            const currency = document.getElementById('currency').value || 'LKR';
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
                <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">Passport Information</h4>
                <div style="font-size:15px;font-weight:bold;">${passportNumber}</div>
            </td>
            <td style="width:50%;vertical-align:top;border-left:1px solid #eee;padding-left:25px;">
                <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">Visa Details</h4>
                <div><strong>Route:</strong> ${fromCountry} - ${toCountry}</div>
                <div><strong>Visa Type:</strong> ${visaType}</div>
                <div><strong>Visa Category:</strong> ${visaCategory}</div>
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
                    body: JSON.stringify({
                        html: htmlContent
                    })
                })
                .then(response => response.blob())
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
