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
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Date & Time</label>
                        <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-control"
                            required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Date & Time</label>
                        <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control" required>
                    </div>

                    <div class="col-md-3 mb-3">
                          <label class="form-label">Currency</label>
                        <select name="currency" id="currency" class="form-control" required>
                            <option value="LKR">LKR</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Base Price</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control calc"
                            required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Additional Charges</label>
                        <input type="number" step="0.01" name="additional_price" id="additional_price"
                            class="form-control calc">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Discount</label>
                        <input type="number" step="0.01" name="discount" id="discount" class="form-control calc">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total Price</label>
                        <input type="number" step="0.01" name="total_price" id="total_price" class="form-control"
                            readonly required>
                    </div>

                    <!-- Status / Payment -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Booking Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="quotation">Quotation</option>
                            <option value="invoice">Invoice</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
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
                </div>

                <div class="text-end gap-2 d-flex justify-content-end">
                       <a href="{{ route('admin.rent-vehicle-bookings.index') }}" class="btn btn-light" style="width:130px;">Back</a>
                    <button type="button" class="btn btn-secondary" onclick="previewBooking()"
                        style="width:130px;">Preview</button>
                    <button type="submit" class="btn btn-primary" style="width:130px;">Save Booking</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Preview Modal --}}
    <div class="modal fade" id="bookingPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Rent Vehicle Booking Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="bookingPreviewBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="generatePdf()">Generate PDF</button>
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

            function calculateTotal() {
                const price = parseFloat(priceInput.value) || 0;
                const add = parseFloat(additionalInput.value) || 0;
                const discount = parseFloat(discountInput.value) || 0;
                totalInput.value = (price + add) - discount;
            }

            [priceInput, additionalInput, discountInput].forEach(el => el.addEventListener('input',
                calculateTotal));
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

            const customerName = customerSelect.options[customerSelect.selectedIndex].text;
            const customerEmail = customerSelect.options[customerSelect.selectedIndex].dataset.email;
            const customerPhone = customerSelect.options[customerSelect.selectedIndex].dataset.phone;

            const vehicleName = vehicleSelect.options[vehicleSelect.selectedIndex].text;

            // Get images array
            let images = [];
            const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.images) {
                try {
                    images = JSON.parse(selectedOption.dataset.images);
                } catch (e) {
                    console.error("Invalid vehicle images JSON", e);
                    images = [];
                }
            }

            const mainImage = images.length ? getImageUrl(images[0]) : "https://via.placeholder.com/280x180?text=No+Image";
            const thumbnails = images.slice(1).map(img =>
                `<img src="${getImageUrl(img)}" style="width:60px;height:45px;object-fit:cover;margin:0 3px;border-radius:3px;border:1px solid #ddd;">`
                ).join("");

            const startDatetime = document.getElementById('start_datetime').value || 'N/A';
            const endDatetime = document.getElementById('end_datetime').value || 'N/A';

            const price = parseFloat(document.getElementById('price').value || 0);
            const addCharges = parseFloat(document.getElementById('additional_price').value || 0);
            const discount = parseFloat(document.getElementById('discount').value || 0);
            const total = price + addCharges - discount;

            const currency = document.getElementById('currency').value || 'LKR';
            const paymentStatus = document.getElementById('payment_status').value.toUpperCase();
            const status = document.getElementById('status').value;
            const note = document.getElementById('notes').value;

            const invoiceNo = document.getElementById('invoice_no').value;
            const invoiceDate = new Date().toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const badgeColors = {
                quotation: '#6c757d',
                invoice: '#0d6efd',
                confirmed: '#198754',
                completed: '#20c997',
                cancelled: '#dc3545'
            };
            const badgeColor = badgeColors[status] || '#6c757d';

            const html = `
<div style="max-width:800px; margin:0 auto; font-family:Arial, sans-serif; background:#fff; padding:40px; border:1px solid #ddd;">
    <!-- Company Logo & Details -->
    <div style="text-align:center; margin-bottom:30px; padding-bottom:20px; border-bottom:2px solid #333;">
        <div style="margin-bottom:15px;">
            <img src="{{ asset('images/vacayguider.png') }}" alt="Company Logo" style="max-width:150px; height:auto;" onerror="this.style.display='none'">
        </div>
        <p style="margin:5px 0; color:#666; font-size:14px;">123 Business Street, City, State 12345</p>
        <p style="margin:5px 0; color:#666; font-size:14px;">Phone:  +94 114 272 372 | Email: info@vacayguider.com</p>
        <p style="margin:5px 0; color:#666; font-size:14px;">Website: www.vacayguider.com</p>
    </div>

    <!-- Invoice Header -->
    <div style="text-align:center; margin-bottom:30px;">
        <h2 style="margin:0 0 10px 0; font-size:24px; color:#333;">RENT INVOICE</h2>
        <span style="background:${badgeColor}; color:white; padding:5px 15px; border-radius:3px; font-size:12px; font-weight:bold;">${status.toUpperCase()}</span>
    </div>

    <!-- Customer & Invoice Info -->
    <table style="width:100%; margin-bottom:30px; border-collapse: collapse;border: none;">
        <tr>
            <td style="vertical-align: top; width:50%; padding-right:15px;">
                <h3 style="margin:0 0 10px 0; font-size:14px; color:#333; font-weight:bold; text-transform:uppercase;">Bill To:</h3>
                <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Name:</strong> ${customerName}</p>
                <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Email:</strong> ${customerEmail}</p>
                <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Phone:</strong> ${customerPhone}</p>
            </td>
            <td style="vertical-align: top; width:50%; padding-left:15px; text-align:right;">
                <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Invoice No:</strong> ${invoiceNo}</p>
                <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Invoice Date:</strong> ${invoiceDate}</p>
                <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Payment Status:</strong> 
                    <span style="color:${badgeColor}; font-weight:bold;">${paymentStatus}</span>
                </p>
            </td>
        </tr>
    </table>

    <!-- Vehicle Details -->
    <div style="width:100%; text-align:center; margin:30px 0;">
        <table style="max-width:600px; width:100%; margin:0 auto; background:#f9f9f9; border-radius:5px; border-collapse:collapse;">
            <tr>
                <td style="padding:20px;">
                    <h3 style="margin:0 0 15px 0; font-size:16px; color:#333; font-weight:bold; text-align:center;">Vehicle Details</h3>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="width:280px; vertical-align:top; text-align:center; padding-right:15px;">
                                <img src="${mainImage}" style="width:280px; height:auto; max-height:180px; object-fit:cover; border-radius:5px; border:1px solid #ddd;" alt="Vehicle">
                                <div style="margin-top:8px;">${thumbnails}</div>
                            </td>
                            <td style="vertical-align:top; text-align:left; padding-left:15px;">
                                <p style="margin:0 0 10px 0; font-size:18px; color:#333; font-weight:bold;">${vehicleName}</p>
                                <p style="margin:8px 0; color:#666; font-size:14px;"><strong>Start Date/Time:</strong> ${startDatetime}</p>
                                <p style="margin:8px 0; color:#666; font-size:14px;"><strong>End Date/Time:</strong> ${endDatetime}</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- Pricing Table -->
    <table style="width:100%; border-collapse:collapse; margin-bottom:20px;page-break-before: always;">
        <thead>
            <tr style="background:#333; color:white;">
                <th style="padding:12px; text-align:left; font-size:14px; font-weight:600;">Description</th>
                <th style="padding:12px; text-align:right; font-size:14px; font-weight:600;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr style="border-bottom:1px solid #ddd;">
                <td style="padding:12px; font-size:14px; color:#666;">Base Rental Price</td>
                <td style="padding:12px; text-align:right; font-size:14px; color:#666;">${currency} ${price.toFixed(2)}</td>
            </tr>
            <tr style="border-bottom:1px solid #ddd;">
                <td style="padding:12px; font-size:14px; color:#666;">Additional Charges</td>
                <td style="padding:12px; text-align:right; font-size:14px; color:#666;">${currency} ${addCharges.toFixed(2)}</td>
            </tr>
            <tr style="border-bottom:1px solid #ddd;">
                <td style="padding:12px; font-size:14px; color:#666;">Discount</td>
                <td style="padding:12px; text-align:right; font-size:14px; color:#28a745;">- ${currency} ${discount.toFixed(2)}</td>
            </tr>
            <tr style="background:#f0f0f0;">
                <td style="padding:15px 12px; font-size:16px; color:#333; font-weight:bold;">TOTAL AMOUNT</td>
                <td style="padding:15px 12px; text-align:right; font-size:18px; color:#333; font-weight:bold;">${currency} ${total.toFixed(2)}</td>
            </tr>
        </tbody>
    </table>

    <!-- Notes -->
    ${note ? `<div style="margin-bottom:20px; padding:15px; background:#fffbea; border-left:4px solid #ffc107;">
            <p style="margin:0; font-size:14px; color:#666;"><strong>Note:</strong> ${note}</p>
        </div>` : ''}

    <!-- Footer -->
    <div style="margin-top:40px; padding-top:20px; border-top:1px solid #ddd; text-align:center;">
        <p style="margin:5px 0; color:#999; font-size:12px;">Thank you for your business!</p>
        <p style="margin:5px 0; color:#999; font-size:12px;">For questions about this invoice, please contact us at info@vacayguider.com</p>
    </div>
</div>
`;

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
