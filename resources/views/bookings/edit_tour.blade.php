@extends('layouts.vertical', ['subtitle' => 'Edit Tour Quotation / Invoice'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Tour Quotation / Invoice',
        'subtitle' => 'Edit',
    ])

    <style>
        #travel_end_date_error {
            font-size: 0.875rem;
            margin-top: 0.25rem;
            color: #dc3545;
        }
    </style>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Tour Quotation / Invoice</h5>
        </div>
        <div class="card-body">
            {{-- Success / Error Alerts --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form id="quotationForm" action="{{ route('admin.tour-bookings.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Customer & Package Info --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="">-- Select Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" data-email="{{ $customer->email }}"
                                    data-phone="{{ $customer->contact ?? 'N/A' }}"
                                    {{ $booking->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="tour_category" class="form-label">Tour Category</label>
                        <select id="tour_category" class="form-select">
                            <option value="">-- Select Category --</option>
                            @foreach ($packages->pluck('tour_category')->unique()->filter() as $category)
                                <option value="{{ $category }}"
                                    {{ $booking->package?->tour_category == $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="package_id" class="form-label">Tour Package</label>
                        <select name="package_id" id="package_id" class="form-select" required>
                            <option value="">-- Select Package --</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}" data-category="{{ $package->tour_category }}"
                                    data-price="{{ $package->price }}" data-tour-ref="{{ $package->tour_ref_no }}"
                                    {{ $booking->package_id == $package->id ? 'selected' : '' }}>
                                    {{ $package->heading }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Travel Details --}}
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="travel_start_date" class="form-label">Travel Start Date</label>
                        <input type="date" name="travel_start_date" id="travel_start_date" class="form-control"
                            value="{{ $booking->travel_date?->format('Y-m-d') ?? '' }}" required>
                    </div>
                    <div class="col-md-2">
                        <label for="travel_end_date" class="form-label">Travel End Date</label>
                        <input type="date" name="travel_end_date" id="travel_end_date" class="form-control"
                            value="{{ $booking->travel_end_date?->format('Y-m-d') ?? '' }}" required>
                        <div id="travel_end_date_error" class="text-danger mt-1"></div>
                    </div>
                    <div class="col-md-2">
                        <label for="adults" class="form-label">Adults</label>
                        <input type="number" name="adults" id="adults" class="form-control"
                            value="{{ $booking->adults }}" min="0" required>
                    </div>
                    <div class="col-md-2">
                        <label for="children" class="form-label">Children</label>
                        <input type="number" name="children" id="children" class="form-control"
                            value="{{ $booking->children }}" min="0">
                    </div>
                    <div class="col-md-2">
                        <label for="infants" class="form-label">Infants</label>
                        <input type="number" name="infants" id="infants" class="form-control"
                            value="{{ $booking->infants }}" min="0">
                    </div>
                    <div class="col-md-2">
                        <label for="visit_country" class="form-label">Visit Country</label>
                        <input type="text" name="visit_country" id="visit_country" class="form-control"
                            placeholder="e.g. Sri Lanka" value="{{ $booking->visit_country }}" required>
                    </div>

                </div>



                {{-- Status --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="quotation" {{ $booking->status == 'quotation' ? 'selected' : '' }}>Quotation
                            </option>
                            <option value="accepted" {{ $booking->status == 'accepted' ? 'selected' : '' }}>Accepted
                            </option>
                            <option value="invoiced" {{ $booking->status == 'invoiced' ? 'selected' : '' }}>Invoiced
                            </option>
                            <option value="partially_paid" {{ $booking->status == 'partially_paid' ? 'selected' : '' }}>
                                Partially Paid</option>
                            <option value="paid" {{ $booking->status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-select" required>
                            <option value="pending" {{ $booking->payment_status == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="partial" {{ $booking->payment_status == 'partial' ? 'selected' : '' }}>Partial
                            </option>
                            <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Special Requirements --}}
                <div class="mb-3">
                    <label for="special_requirements" class="form-label">Special Requirements / Notes</label>
                    <textarea name="special_requirements" id="special_requirements" class="form-control" rows="3">{{ $booking->special_requirements }}</textarea>
                </div>

                {{-- Price & Payment Details --}}
                <div class="col-md-6 mb-3">
                    <div class="card border-secondary">
                        <div class="card-header bg-light">
                            <strong>Price & Payment Details</strong>
                        </div>
                        <div class="card-body">
                            <div class="mb-2 row">
                                <label class="col-sm-4 col-form-label"> Currency</label>
                                <div class="col-sm-8">
                                    <select name="currency" id="currency" class="form-select">
                                        <option value="USD" {{ $booking->currency == 'USD' ? 'selected' : '' }}>USD
                                        </option>
                                        <option value="LKR" {{ $booking->currency == 'LKR' ? 'selected' : '' }}>LKR
                                        </option>
                                        <option value="EUR" {{ $booking->currency == 'EUR' ? 'selected' : '' }}>EUR
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- Package Price --}}
                            <div class="mb-2 row">
                                <label class="col-sm-4 col-form-label">Package Price</label>
                                <div class="col-sm-8">
                                    <input type="number" name="package_price" id="package_price" class="form-control"
                                        step="0.01" value="{{ $booking->package_price }}" required>
                                </div>
                            </div>

                            {{-- Additional Charges --}}
                            <div class="mb-2 row">
                                <label class="col-sm-4 col-form-label">Additional Charges</label>
                                <div class="col-sm-8">
                                    <input type="number" name="additional_charges" id="additional_charges"
                                        class="form-control" step="0.01" value="{{ $booking->tax }}">
                                </div>
                            </div>

                            {{-- Discount --}}
                            <div class="mb-2 row">
                                <label class="col-sm-4 col-form-label">Discount</label>
                                <div class="col-sm-8">
                                    <input type="number" name="discount" id="discount" class="form-control"
                                        step="0.01" value="{{ $booking->discount }}">
                                </div>
                            </div>

                            <hr>

                            {{-- Total Price --}}
                            <div class="mb-2 row">
                                <label class="col-sm-4 col-form-label">Total Price</label>
                                <div class="col-sm-8">
                                    <input type="number" name="total_price" id="total_price" class="form-control"
                                        step="0.01" value="{{ $booking->total_price }}" readonly>
                                </div>
                            </div>

                            {{-- Advance Paid --}}
                            <div class="mb-2 row">
                                <label class="col-sm-4 col-form-label">Advance Paid</label>
                                <div class="col-sm-8">
                                    <input type="number" name="advance_paid" id="advance_paid" class="form-control"
                                        step="0.01" value="{{ $booking->advance_paid }}">
                                </div>
                            </div>

                            {{-- Balance Amount --}}
                            <div class="mb-2 row">
                                <label class="col-sm-4 col-form-label">Balance Amount</label>
                                <div class="col-sm-8">
                                    <input type="number" id="balance_amount" class="form-control" step="0.01"
                                        value="{{ $booking->balance_amount }}" readonly>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-warning" style="width:120px;"
                        onclick="window.location='{{ route('admin.tour-bookings.index') }}'">Back</button>
                    <button type="button" class="btn btn-secondary" style="width:120px;"
                        onclick="previewQuotation()">Preview</button>
                    <button type="submit" class="btn btn-success" style="width:120px;">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Preview Modal (same as create) --}}
    <div class="modal fade" id="quotationPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="quotationPreviewBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"style="width:120px;"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" style="width:120px;" onclick="generatePdf()">Generate
                        PDF</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts (same as create) --}}
    <script>
        // Travel date validation
        document.addEventListener('DOMContentLoaded', function() {
            const travelStart = document.getElementById('travel_start_date');
            const travelEnd = document.getElementById('travel_end_date');
            const travelEndError = document.getElementById('travel_end_date_error');

            travelEnd.addEventListener('input', function() {
                const startDate = new Date(travelStart.value);
                const endDate = new Date(travelEnd.value);

                if (endDate < startDate) {
                    travelEndError.textContent = "Travel End Date cannot be before Travel Start Date.";
                    travelEnd.classList.add('is-invalid');
                } else {
                    travelEndError.textContent = '';
                    travelEnd.classList.remove('is-invalid');
                }
            });
        });

        // PDF generation
        function generatePdf() {
            const htmlContent = document.getElementById('quotationPreviewBody').innerHTML;

            fetch("{{ route('admin.tour-quotations.generatePdf') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        html: htmlContent
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error("PDF generation failed");
                    return response.blob();
                })
                .then(blob => {
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'Tour_Quotation_Invoice.pdf';
                    link.click();
                    URL.revokeObjectURL(link.href);
                })
                .catch(error => {
                    console.error("Error generating PDF:", error);
                    alert("Failed to generate PDF. Please try again.");
                });
        }

        // Price calculations
        const packagePrice = document.getElementById('package_price');
        const additionalCharges = document.getElementById('additional_charges');
        const discount = document.getElementById('discount');
        const totalPrice = document.getElementById('total_price');
        const advancePaid = document.getElementById('advance_paid');
        const balanceAmount = document.getElementById('balance_amount');

        function calculateTotal() {
            const price = parseFloat(packagePrice.value) || 0;
            const addCharges = parseFloat(additionalCharges.value) || 0;
            const disc = parseFloat(discount.value) || 0;
            const advance = parseFloat(advancePaid.value) || 0;

            const total = (price + addCharges) - disc;
            totalPrice.value = total.toFixed(2);
            balanceAmount.value = Math.max(0, total - advance).toFixed(2);
        }

        packagePrice.addEventListener('input', calculateTotal);
        additionalCharges.addEventListener('input', calculateTotal);
        discount.addEventListener('input', calculateTotal);
        advancePaid.addEventListener('input', calculateTotal);

        // Initial calculation on load
        calculateTotal();

        // Preview
        function previewQuotation() {
            const customerSelect = document.getElementById('customer_id');
            const packageSelect = document.getElementById('package_id');
            const statusSelect = document.getElementById('status');

            const travelStart = document.getElementById('travel_start_date').value;
            const travelEnd = document.getElementById('travel_end_date').value;
            const paymentStatus = document.getElementById('payment_status').value;
            const adults = document.getElementById('adults').value;
            const children = document.getElementById('children').value;
            const infants = document.getElementById('infants').value;
            const currency = document.getElementById('currency').value;
            const specialReq = document.getElementById('special_requirements').value;

            const packagePriceVal = parseFloat(document.getElementById('package_price').value) || 0;
            const addChargesVal = parseFloat(document.getElementById('additional_charges').value) || 0;
            const discountVal = parseFloat(document.getElementById('discount').value) || 0;
            const advancePaidVal = parseFloat(document.getElementById('advance_paid').value) || 0;

            const totalPriceVal = Math.max(0, (packagePriceVal + addChargesVal) - discountVal);
            const balanceVal = Math.max(0, totalPriceVal - advancePaidVal);

            const status = statusSelect.value;

            const customerOption = customerSelect.options[customerSelect.selectedIndex];
            const customerName = customerOption.text;
            const customerEmail = customerOption.dataset.email;
            const customerPhone = customerOption.dataset.phone;

            const packageOption = packageSelect.options[packageSelect.selectedIndex];
            const packageName = packageOption.text;
            const packageRef = packageOption.dataset.tourRef;

            const currentDate = new Date().toLocaleDateString('en-GB');

            let badgeText = '',
                badgeColor = '';
            switch (status) {
                case 'quotation':
                    badgeText = 'QUOTATION';
                    badgeColor = '#6c757d';
                    break;
                case 'accepted':
                    badgeText = 'ACCEPTED';
                    badgeColor = '#0d6efd';
                    break;
                case 'invoiced':
                    badgeText = 'INVOICED';
                    badgeColor = '#6610f2';
                    break;
                case 'partially_paid':
                    badgeText = 'PARTIALLY PAID';
                    badgeColor = '#ffc107';
                    break;
                case 'paid':
                    badgeText = 'PAID';
                    badgeColor = '#198754';
                    break;
                case 'cancelled':
                    badgeText = 'CANCELLED';
                    badgeColor = '#dc3545';
                    break;
                default:
                    badgeText = 'QUOTATION';
                    badgeColor = '#6c757d';
            }

            // Generate HTML (same style as create)
            const html = `
<div style="max-width:800px; margin:0 auto; font-family:'Helvetica Neue', Helvetica, Arial, sans-serif; color:#333; background:#fff; padding:20px;">
    <table style="width:100%; border-bottom:2px solid #333; padding-bottom:20px; margin-bottom:30px;">
        <tr>
            <td style="vertical-align: top;">
                <img src="{{ asset('images/vacayguider.png') }}" alt="Logo" style="height:80px;">
                <div style="margin-top:15px; font-size:12px; line-height:1.4; color:#666;">
                    <strong>Vacay Guider (Pvt) Ltd.</strong><br>
                    22/14 C Asarappa Rd, Negombo 11400<br>
                    +94 114 272 372 | info@vacayguider.com
                </div>
            </td>
            <td style="text-align:right; vertical-align: top;">
                <h1 style="margin:0; font-size:24px; font-weight:300; letter-spacing:2px; text-transform:uppercase; color:${badgeColor};">${badgeText}</h1>
                <table style="margin-left:auto; margin-top:10px; font-size:13px; border-collapse:collapse;">
                    <tr>
                        <td style="padding:2px 10px; text-align:left; color:#888;">Reference:</td>
                        <td style="padding:2px 10px; font-weight:bold;">${packageRef || '-'}</td>
                    </tr>
                    <tr>
                        <td style="padding:2px 10px; text-align:left; color:#888;">Date:</td>
                        <td style="padding:2px 10px;">${currentDate}</td>
                    </tr>
                    <tr>
                        <td style="padding:2px 10px; text-align:left; color:#888;">Currency:</td>
                        <td style="padding:2px 10px;">${currency}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:40px; font-size:13px;">
        <tr>
            <td style="width:50%; vertical-align:top;">
                <h4 style="text-transform:uppercase; font-size:11px; color:#888; margin-bottom:10px; letter-spacing:1px;">Client Information</h4>
                <div style="font-size:15px; font-weight:bold; margin-bottom:5px;">${customerName}</div>
                <div style="color:#555;">${customerEmail}</div>
                <div style="color:#555;">${customerPhone}</div>
            </td>
            <td style="width:50%; vertical-align:top; border-left:1px solid #eee; padding-left:30px;">
                <h4 style="text-transform:uppercase; font-size:11px; color:#888; margin-bottom:10px; letter-spacing:1px;">Tour Information</h4>
                <div style="margin-bottom:3px;"><strong>Package:</strong> ${packageName}</div>
                <div style="margin-bottom:3px;"><strong>Reference:</strong> ${packageRef}</div>
                <div style="margin-bottom:3px;"><strong>Duration:</strong> ${travelStart} to ${travelEnd}</div>
                <div style="margin-bottom:3px;"><strong>Pax:</strong> ${adults} Adults, ${children} Children</div>
            </td>
        </tr>
    </table>

    <table style="width:100%; border-collapse:collapse; margin-bottom:30px; font-size:14px;">
        <thead>
            <tr style="background:#f9f9f9; border-top:1px solid #333; border-bottom:1px solid #333;">
                <th style="padding:12px; text-align:left; text-transform:uppercase; font-size:11px;">Description</th>
                <th style="padding:12px; text-align:right; text-transform:uppercase; font-size:11px;">Total (${currency})</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding:15px 12px; border-bottom:1px solid #eee;">
                    <strong>Travel Package Arrangement</strong><br>

                </td>
                <td style="padding:15px 12px; text-align:right; border-bottom:1px solid #eee; vertical-align:top;">
                    ${packagePriceVal.toFixed(2)}
                </td>
            </tr>
            ${addChargesVal > 0 ? `
                                    <tr>
                                        <td style="padding:12px; border-bottom:1px solid #eee;">Additional Services / Charges</td>
                                        <td style="padding:12px; text-align:right; border-bottom:1px solid #eee;">${addChargesVal.toFixed(2)}</td>
                                    </tr>` : ''}
            ${discountVal > 0 ? `
                                    <tr>
                                        <td style="padding:12px; border-bottom:1px solid #eee; color:#888; font-style:italic;">Discount Applied</td>
                                        <td style="padding:12px; text-align:right; border-bottom:1px solid #eee; color:#888;">(${discountVal.toFixed(2)})</td>
                                    </tr>` : ''}
        </tbody>
    </table>

    <div style="width:40%; margin-left:auto;">
        <table style="width:100%; font-size:14px; border-collapse:collapse;">
            <tr>
                <td style="padding:8px 0; color:#888;">Subtotal:</td>
                <td style="padding:8px 0; text-align:right;">${totalPriceVal.toFixed(2)}</td>
            </tr>
            <tr>
                <td style="padding:8px 0; color:#888;">Advance Paid:</td>
                <td style="padding:8px 0; text-align:right; color:#1a7f37;">${advancePaidVal.toFixed(2)}</td>
            </tr>
            <tr style="border-top:1px solid #333;">
                <td style="padding:12px 0; font-weight:bold; font-size:16px;">Balance Due:</td>
                <td style="padding:12px 0; text-align:right; font-weight:bold; font-size:18px; color:#000;">${currency} ${balanceVal.toFixed(2)}</td>
            </tr>
        </table>
    </div>

    ${specialReq ? `
                            <div style="margin-top:50px; border-top:1px solid #eee; padding-top:20px;">
                                <h4 style="font-size:11px; text-transform:uppercase; color:#888; margin-bottom:10px;">Terms & Notes</h4>
                                <div style="font-size:12px; color:#666; line-height:1.6; white-space: pre-wrap;">${specialReq}</div>
                            </div>` : ''}

    <div style="margin-top:60px; text-align:center; border-top:1px solid #eee; padding-top:20px; font-size:11px; color:#aaa;">
        <p style="margin-bottom:5px;">This is a computer-generated document. No signature is required.</p>
        <p><strong>Vacay Guider</strong> | www.vacayguider.com | Thank you for your business.</p>
    </div>
</div>`;

            document.getElementById('quotationPreviewBody').innerHTML = html;
            new bootstrap.Modal(document.getElementById('quotationPreviewModal')).show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('tour_category');
            const packageSelect = document.getElementById('package_id');

            function filterPackages() {
                const selectedCategory = categorySelect.value;

                Array.from(packageSelect.options).forEach(option => {
                    if (!option.value) return;

                    option.style.display =
                        option.dataset.category === selectedCategory ? 'block' : 'none';
                });
            }

            categorySelect.addEventListener('change', function() {
                packageSelect.value = '';
                document.getElementById('package_price').value = 0;
                calculateTotal();
                filterPackages();
            });

            // Run once on page load (EDIT mode)
            if (categorySelect.value) {
                filterPackages();
            }
        });

        document.getElementById('package_id').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (!option || !option.dataset.price) return;

            document.getElementById('package_price').value =
                parseFloat(option.dataset.price).toFixed(2);

            calculateTotal();
        });

        document.getElementById('advance_paid').addEventListener('input', function() {
            const total = parseFloat(document.getElementById('total_price').value) || 0;
            const advance = parseFloat(this.value) || 0;
            const paymentStatus = document.getElementById('payment_status');

            if (advance <= 0) paymentStatus.value = 'pending';
            else if (advance < total) paymentStatus.value = 'partial';
            else paymentStatus.value = 'paid';
        });
    </script>
@endsection
