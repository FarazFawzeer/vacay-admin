@extends('layouts.vertical', ['subtitle' => 'Edit Tour Quotation / Invoice'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Tour Quotation / Invoice',
        'subtitle' => 'Edit',
    ])

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
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                        <label for="package_id" class="form-label">Tour Package</label>
                        <select name="package_id" id="package_id" class="form-select" required>
                            <option value="">-- Select Package --</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}" data-price="{{ $package->price }}"
                                    data-tour-ref="{{ $package->tour_ref_no }}"
                                    {{ $booking->package_id == $package->id ? 'selected' : '' }}>
                                    {{ $package->heading }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Travel Details --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="travel_start_date" class="form-label">Travel Start Date</label>
                        <input type="date" id="travel_start_date" class="form-control" name="travel_start_date"
                            value="{{ \Carbon\Carbon::parse($booking->travel_date)->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="travel_end_date" class="form-label">Travel End Date</label>
                        <input type="date" id="travel_end_date" class="form-control" name="travel_end_date"
                            value="{{ \Carbon\Carbon::parse($booking->travel_end_date)->format('Y-m-d') }}">
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
                        <label for="currency" class="form-label">Currency</label>
                        <select name="currency" id="currency" class="form-select">
                            <option value="USD" {{ $booking->currency == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="LKR" {{ $booking->currency == 'LKR' ? 'selected' : '' }}>LKR</option>
                        </select>
                    </div>
                </div>

                {{-- Status --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="quotation" {{ $booking->status == 'quotation' ? 'selected' : '' }}>Quotation
                            </option>
                            <option value="invoiced" {{ $booking->status == 'invoiced' ? 'selected' : '' }}>Invoice
                            </option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed
                            </option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
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

                {{-- Pricing --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="package_price" class="form-label">Package Price</label>
                        <input type="number" name="package_price" id="package_price" class="form-control"
                            step="0.01" value="{{ $booking->package_price }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="additional_charges" class="form-label">Additional Charges</label>
                        <input type="number" name="additional_charges" id="additional_charges" class="form-control"
                            step="0.01" value="{{ $booking->tax ?? 0 }}">
                    </div>
                    <div class="col-md-3">
                        <label for="discount" class="form-label">Discount</label>
                        <input type="number" name="discount" id="discount" class="form-control" step="0.01"
                            value="{{ $booking->discount ?? 0 }}">
                    </div>
                    <div class="col-md-3">
                        <label for="total_price" class="form-label">Total Price</label>
                        <input type="number" name="total_price" id="total_price" class="form-control" step="0.01"
                            value="{{ $booking->total_price }}" readonly>
                    </div>
                </div>

                {{-- Special Requirements --}}
                <div class="mb-3">
                    <label for="special_requirements" class="form-label">Special Requirements / Notes</label>
                    <textarea name="special_requirements" id="special_requirements" class="form-control" rows="3">{{ $booking->special_requirements }}</textarea>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-warning" style="width:120px;"
                        onclick="window.location='{{ route('admin.tour-bookings.index') }}'">
                        Back
                    </button>
                    <button type="button" class="btn btn-secondary" style="width:120px;"
                        onclick="previewQuotation()">Preview</button>
                    <button type="submit" class="btn btn-success" style="width:120px;">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Quotation / Invoice Preview Modal --}}
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

    {{-- JS for preview & calculation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const travelStart = document.getElementById('travel_start_date');
            const travelEnd = document.getElementById('travel_end_date');
            const travelEndError = document.createElement('div');
            travelEndError.id = 'travel_end_date_error';
            travelEndError.classList.add('text-danger', 'mt-1');
            travelEnd.after(travelEndError);

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

        function generatePdf() {
            const htmlContent = document.getElementById('quotationPreviewBody').innerHTML; // <- correct ID

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
        const packagePrice = document.getElementById('package_price');
        const additionalCharges = document.getElementById('additional_charges');
        const discount = document.getElementById('discount');
        const totalPrice = document.getElementById('total_price');

        function calculateTotal() {
            const price = parseFloat(packagePrice.value) || 0;
            const addCharges = parseFloat(additionalCharges.value) || 0;
            const disc = parseFloat(discount.value) || 0;
            totalPrice.value = (price + addCharges) - disc;
        }

        packagePrice.addEventListener('input', calculateTotal);
        additionalCharges.addEventListener('input', calculateTotal);
        discount.addEventListener('input', calculateTotal);

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
            const discountVal = parseFloat(document.getElementById('discount').value) || 0;
            const addChargesVal = parseFloat(document.getElementById('additional_charges').value) || 0;
            const specialReq = document.getElementById('special_requirements').value;
            const status = statusSelect.value;

            const customerOption = customerSelect.options[customerSelect.selectedIndex];
            const customerName = customerOption.text;
            const customerEmail = customerOption.dataset.email;
            const customerPhone = customerOption.dataset.phone;

            const packageOption = packageSelect.options[packageSelect.selectedIndex];
            const packageName = packageOption.text;
            const packagePriceVal = parseFloat(document.getElementById('package_price').value) || 0;
            const packageRef = packageOption.dataset['tour-ref'];

            const totalPriceVal = (packagePriceVal + addChargesVal) - discountVal;

            const invoiceNumber = "{{ $booking->invoice_number }}"; // or generate dynamically if needed
            const currentDate = new Date().toLocaleDateString('en-GB');
            let badgeText = '';
let badgeColor = '';

switch(status) {
    case 'quotation':
        badgeText = 'QUOTATION';
        badgeColor = '#6c757d'; // gray
        break;
    case 'invoiced':
        badgeText = 'INVOICE';
        badgeColor = '#0d6efd'; // blue
        break;
    case 'confirmed':
        badgeText = 'CONFIRMED';
        badgeColor = '#198754'; // green
        break;
    case 'completed':
        badgeText = 'COMPLETED';
        badgeColor = '#20c997'; // teal
        break;
    case 'cancelled':
        badgeText = 'CANCELLED';
        badgeColor = '#dc3545'; // red
        break;
    default:
        badgeText = 'QUOTATION';
        badgeColor = '#6c757d';
}

            const html = `
<div style="max-width:900px; margin:0 auto; font-family:'Segoe UI', sans-serif; background:#fff; box-shadow:0 0 20px rgba(0,0,0,0.1);">
    <div style="padding:40px; border-bottom:2px solid #e0e0e0;">
        <div style="text-align:center; margin-bottom:30px;">
            <img src="{{ asset('images/vacayguider.png') }}" alt="Company Logo" style=" height:120px; object-fit:contain;">
        </div>
        <table style="width:100%; border:none; margin-top:10px;">
            <tr>
                <td style="vertical-align:top; width:60%;">
                    <h4 style="margin:0 0 12px 0; font-size:15px; font-weight:600; color:#2c3e50;">COMPANY DETAILS</h4>
                    <p style="margin:5px 0; font-size:14px;"><strong>Name:</strong> Vacay Guider</p>
                    <p style="margin:5px 0; font-size:14px;"><strong>Address:</strong> 123 Business Street</p>
                    <p style="margin:5px 0; font-size:14px;"><strong>Phone:</strong> +94 114 272 372</p>
                    <p style="margin:5px 0; font-size:14px;"><strong>Email:</strong> info@vacayguider.com</p>
                </td>
                <td style="vertical-align:top; text-align:right; width:40%;">
                    <div style="margin-bottom:5px;">
                         <h2 style="background:${badgeColor}; display:inline-block; margin:0; font-size:14px; border-radius:4px; font-weight:700; color:white; padding:3px 6px;">${badgeText}</h2>
                    </div>
                    <p style="margin:2px 0; font-size:13px;"><strong>Number:</strong> ${invoiceNumber}</p>
                    <p style="margin:2px 0; font-size:13px;"><strong>Date:</strong> ${currentDate}</p>
                </td>
            </tr>
        </table>
        <div style="margin-top:20px;">
            <h4 style="margin:0 0 12px 0; font-size:15px; font-weight:600; color:#2c3e50;">CUSTOMER DETAILS</h4>
            <p style="margin:5px 0; font-size:14px;"><strong>Name:</strong> ${customerName}</p>
            <p style="margin:5px 0; font-size:14px;"><strong>Email:</strong> ${customerEmail}</p>
            <p style="margin:5px 0; font-size:14px;"><strong>Contact:</strong> ${customerPhone}</p>
        </div>
    </div>

    <div style="padding-right:40px; padding-left:40px;padding-top:40px">
        <h3 style="margin:0 0 15px 0; font-size:17px; font-weight:600; color:#2c3e50; padding-bottom:8px;">Package Details</h3>
        <div style="background:#f8f9fa; padding:20px; border-radius:6px; margin-bottom:15px;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:50%; vertical-align:top; padding-right:15px;">
                        <p style="margin:8px 0; font-size:14px; color:#333;"><strong>Tour Package:</strong> ${packageName}</p>
                        <p style="margin:8px 0; font-size:14px; color:#333;"><strong>Reference No:</strong> ${packageRef}</p>
                        <p style="margin:8px 0; font-size:14px; color:#333;"><strong>Travel Dates:</strong> ${travelStart || 'Not specified'} to ${travelEnd || 'Not specified'}</p>
                    </td>
                    <td style="width:50%; vertical-align:top; padding-left:15px;">
                        <p style="margin:8px 0; font-size:14px; color:#333;"><strong>Passengers:</strong> ${adults} Adult(s)${children > 0 ? ', '+children+' Child(ren)' : ''}${infants > 0 ? ', '+infants+' Infant(s)' : ''}</p>
                        <p style="margin:8px 0; font-size:14px; color:#333;"><strong>Payment Status:</strong> ${paymentStatus.toUpperCase()}</p>
                        ${specialReq ? `<p style="margin:8px 0; font-size:14px; color:#333; white-space: pre-wrap;"><strong>Special Requirements:</strong> ${specialReq}</p>` : ''}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div style="padding-right:40px; padding-left:40px;padding-bottom:40px">
        <h3 style="margin:0 0 10px 0; font-size:16px; font-weight:600; color:#2c3e50; border-bottom:2px solid #2c3e50; padding-bottom:6px;">Price Breakdown</h3>
        <table style="width:100%; border-collapse:collapse; background:#fff; border:1px solid #ddd;">
            <thead>
                <tr style="background:#f4f6f8;">
                    <th style="padding:8px 12px; text-align:left; font-size:13px; font-weight:600; color:#2c3e50; border-bottom:1px solid #ddd;">Description</th>
                    <th style="padding:8px 12px; text-align:right; font-size:13px; font-weight:600; color:#2c3e50; border-bottom:1px solid #ddd; width:180px;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding:8px 12px; font-size:13px; color:#333; border-bottom:1px solid #eee;">Package Price</td>
                    <td style="padding:8px 12px; text-align:right; font-size:13px; color:#333; border-bottom:1px solid #eee;">${currency} ${packagePriceVal.toFixed(2)}</td>
                </tr>
                ${addChargesVal > 0 ? `<tr><td style="padding:8px 12px; font-size:13px; color:#333; border-bottom:1px solid #eee;">Additional Charges</td><td style="padding:8px 12px; text-align:right; font-size:13px; color:#333; border-bottom:1px solid #eee;">${currency} ${addChargesVal.toFixed(2)}</td></tr>` : ''}
                ${discountVal > 0 ? `<tr><td style="padding:8px 12px; font-size:13px; color:#dc3545; border-bottom:1px solid #eee;">Discount</td><td style="padding:8px 12px; text-align:right; font-size:13px; color:#dc3545; border-bottom:1px solid #eee;">- ${currency} ${discountVal.toFixed(2)}</td></tr>` : ''}
                <tr style="background:#2c3e50;">
                    <td style="padding:10px 12px; font-size:14px; font-weight:700; color:white;">TOTAL AMOUNT</td>
                    <td style="padding:10px 12px; text-align:right; font-size:15px; font-weight:700; color:white;">${currency} ${totalPriceVal.toFixed(2)}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="page-break-before: always;"></div>
    <div style="padding:30px 40px; background:#f8f9fa; border-top:2px solid #e0e0e0; text-align:center;">
        <h4 style="margin:0 0 10px 0; font-size:18px; color:#2c3e50; font-weight:600;">Thank You for Your Business!</h4>
        <p style="margin:8px 0; font-size:14px; color:#666;">We look forward to serving you and making your travel experience memorable.</p>
        <p style="margin:8px 0; font-size:14px; color:#666;">For any questions or assistance, please contact us.</p>
        <p style="margin:5px 0; font-size:13px; color:#888;">Email: info@vacayguider.com | Phone: +94 114 272 372 | Website: www.vacayguider.com</p>
    </div>
</div>`;

            document.getElementById('quotationPreviewBody').innerHTML = html;
            new bootstrap.Modal(document.getElementById('quotationPreviewModal')).show();
        }
    </script>
@endsection
