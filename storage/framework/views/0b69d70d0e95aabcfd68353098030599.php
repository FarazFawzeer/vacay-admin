

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', [
        'title' => 'Vehicle Booking',
        'subtitle' => 'Create',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Vehicle Booking</h5>
        </div>

        <div class="card-body">

            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            <form id="vehicleBookingForm" action="<?php echo e(route('admin.vehicle-inv-bookings.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="row">
                    <input type="hidden" id="invoice_no" value="<?php echo e($nextInvoice); ?>">
                    <!-- Customer -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($customer->id); ?>" data-email="<?php echo e($customer->email ?? 'N/A'); ?>"
                                    data-phone="<?php echo e($customer->contact ?? 'N/A'); ?>">
                                    <?php echo e($customer->name); ?> (<?php echo e($customer->customer_code); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Vehicle -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vehicle</label>
                        <select name="vehicle_id" id="vehicle_id" class="form-select" required>
                            <option value="">Select Vehicle</option>
                            <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
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
                                ?>
                                <option value="<?php echo e($vehicle->id); ?>" data-images='<?php echo json_encode($images, 15, 512) ?>'>
                                    <?php echo e($vehicle->name); ?> - <?php echo e($vehicle->model); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                    </div>

                    <!-- Pickup & Drop-off Details -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pickup Location</label>
                        <input type="text" name="pickup_location" id="pickup_location" class="form-control"
                            placeholder="e.g., Colombo Airport">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pickup Date & Time</label>
                        <input type="datetime-local" name="pickup_datetime" id="pickup_datetime" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Drop-off Location</label>
                        <input type="text" name="dropoff_location" id="dropoff_location" class="form-control"
                            placeholder="e.g., Kandy City Centre">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Drop-off Date & Time</label>
                        <input type="datetime-local" name="dropoff_datetime" id="dropoff_datetime" class="form-control">
                    </div>

                    <!-- Pricing -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Base Price</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Additional Charges</label>
                        <input type="number" step="0.01" name="additional_charges" id="additional_charges"
                            class="form-control" value="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Discount</label>
                        <input type="number" step="0.01" name="discount" id="discount" class="form-control"
                            value="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total Price</label>
                        <input type="number" step="0.01" name="total_price" id="total_price" class="form-control"
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
                        <label class="form-label">Currency</label>
                        <select name="currency" id="currency" class="form-select">
                            <option value="LKR">LKR</option>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="quotation">Quotation</option>
                            <option value="invoice">Invoice</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

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

                    <!-- Note -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Note</label>
                        <textarea name="note" id="note" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="text-end gap-2 d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" onclick="previewBooking()"
                        style="width:120px;">Preview</button>
                    <button type="submit" class="btn btn-primary" style="width:120px;">Save Booking</button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal fade" id="bookingPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
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

            // Total price calculation
            const priceInput = document.getElementById("price");
            const additionalInput = document.getElementById("additional_charges");
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


        const STORAGE_BASE = "<?php echo e(asset('storage')); ?>";

        function getImageUrl(imagePath) {
            if (imagePath && imagePath.trim() !== '') {
                return "<?php echo e(asset('storage')); ?>/" + imagePath.replace(/^\/+/, '');
            }
            return "https://via.placeholder.com/280x180?text=No+Image";
        }


        // Preview function
        // Preview function
        // Preview function
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

            // --- 🚨 CRITICAL CHANGE HERE ---
            // MAIN IMAGE
            const mainImage = getImageUrl(images[0]);

            // SUB IMAGES (THUMBNAILS)
            const thumbnails = images.slice(1).map(img => {
                const url = getImageUrl(img); // Use the helper function

                return `
            <img src="${url}" 
                style="width:60px;height:45px;object-fit:cover;margin:0 3px;
                border-radius:3px;border:1px solid #ddd;">
        `;
            }).join("");

            const pickupLocation = document.getElementById('pickup_location').value;
            const pickupDatetime = document.getElementById('pickup_datetime').value;
            const dropoffLocation = document.getElementById('dropoff_location').value;
            const dropoffDatetime = document.getElementById('dropoff_datetime').value;

            const price = parseFloat(document.getElementById('price').value || 0);
            const addCharges = parseFloat(document.getElementById('additional_charges').value || 0);
            const discount = parseFloat(document.getElementById('discount').value || 0);
            const total = parseFloat(document.getElementById('total_price').value || 0);

            const paymentStatus = document.getElementById('payment_status').value;
            const currency = document.getElementById('currency').value;
            const status = document.getElementById('status').value;
            const note = document.getElementById('note').value;

            // Generate invoice number (you can replace this with actual invoice number from database)
            const invoiceNo = document.getElementById('invoice_no').value;
            const invoiceDate = new Date().toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const badgeColors = {
                quotation: '#6c757d',
                confirmed: '#198754',
                completed: '#20c997',
                cancelled: '#dc3545'
            };
            const badgeColor = badgeColors[status] || '#6c757d';

            const html = `
    <div style="max-width:800px; margin:0 auto; font-family:Arial, sans-serif; background:#fff; padding:40px; border:1px solid #ddd;">
        
        <!-- Company Logo & Details (Centered) -->
        <div style="text-align:center; margin-bottom:30px; padding-bottom:20px; border-bottom:2px solid #333;">
            <div style="margin-bottom:15px;">
                <img src="<?php echo e(asset('images/vacayguider.png')); ?>" alt="Company Logo" style="max-width:150px; height:auto;" onerror="this.style.display='none'">
            </div>
            <p style="margin:5px 0; color:#666; font-size:14px;">123 Business Street, City, State 12345</p>
            <p style="margin:5px 0; color:#666; font-size:14px;">Phone:  +94 114 272 372 | Email: info@vacayguider.com</p>
            <p style="margin:5px 0; color:#666; font-size:14px;">Website: www.vacayguider.com</p>
        </div>

        <!-- Invoice Header -->
        <div style="text-align:center; margin-bottom:30px;">
            <h2 style="margin:0 0 10px 0; font-size:24px; color:#333;">TRANSPORT INVOICE</h2>
            <span style="background:${badgeColor}; color:white; padding:5px 15px; border-radius:3px; font-size:12px; font-weight:bold;">${status.toUpperCase()}</span>
        </div>

        <!-- Customer Details & Invoice Info -->
      <!-- Customer Details & Invoice Info -->
<table style="width:100%; margin-bottom:30px; border-collapse: collapse;border: none;">
    <tr>
        <!-- Left: Bill To -->
        <td style="vertical-align: top; width:50%; padding-right:15px;">
            <h3 style="margin:0 0 10px 0; font-size:14px; color:#333; font-weight:bold; text-transform:uppercase;">Bill To:</h3>
            <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Name:</strong> ${customerName}</p>
            <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Email:</strong> ${customerEmail}</p>
            <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Phone:</strong> ${customerPhone}</p>
        </td>

        <!-- Right: Invoice Info -->
        <td style="vertical-align: top; width:50%; padding-left:15px; text-align:right;">
            <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Invoice No:</strong> ${invoiceNo}</p>
            <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Invoice Date:</strong> ${invoiceDate}</p>
            <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Payment Status:</strong> 
                <span style="color:${badgeColor}; font-weight:bold;">${paymentStatus.toUpperCase()}</span>
            </p>
        </td>
    </tr>
</table>


        <!-- Vehicle Details -->
<div style="width:100%; text-align:center; margin:30px 0;">
    <table style="max-width:600px; width:100%; margin:0 auto; background:#f9f9f9; border-radius:5px; border-collapse:collapse;">
        <tr>
            <td style="padding:20px;"> <!-- Add padding here -->
                <!-- Section Header -->
                <h3 style="margin:0 0 15px 0; font-size:16px; color:#333; font-weight:bold; text-align:center;">
                    Vehicle Details
                </h3>

                <!-- Vehicle Row -->
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="width:280px; vertical-align:top; text-align:center; padding-right:15px;">
                            <img src="${mainImage}" style="width:280px; height:auto; max-height:180px; object-fit:cover; border-radius:5px; border:1px solid #ddd;" alt="Vehicle">
                            <div style="margin-top:8px;">
                                ${thumbnails}
                            </div>
                        </td>
                        <td style="vertical-align:top; text-align:left; padding-left:15px;">
                            <p style="margin:0 0 10px 0; font-size:18px; color:#333; font-weight:bold;">${vehicleName}</p>
                            <p style="margin:8px 0; color:#666; font-size:14px;"><strong>Pickup:</strong> ${pickupLocation}</p>
                            <p style="margin:8px 0; color:#666; font-size:14px;"><strong>Date/Time:</strong> ${pickupDatetime || 'N/A'}</p>
                            <p style="margin:8px 0; color:#666; font-size:14px;"><strong>Drop-off:</strong> ${dropoffLocation}</p>
                            <p style="margin:8px 0; color:#666; font-size:14px;"><strong>Date/Time:</strong> ${dropoffDatetime || 'N/A'}</p>
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

            fetch("<?php echo e(route('admin.vehicle-bookings.generatePdf')); ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Create Vehicle Booking'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/bookings/vehicle/create.blade.php ENDPATH**/ ?>