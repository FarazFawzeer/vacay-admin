

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', [
        'title' => 'Vehicle Booking Details',
        'subtitle' => 'Show',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Vehicle Booking Details</h5>
            <div>
                <a href="<?php echo e(route('admin.vehicle-bookings.index')); ?>" class="btn btn-light me-2" style="width: 130px;">Back</a>
                <a class="btn btn-primary" onclick="generatePdf()" style="width: 130px;">Generate PDF</a>
            </div>
        </div>

        <div class="card-body">
            <!-- Wrap invoice in a div with id="invoiceContent" for JS to pick -->
            <div id="invoiceContent">
                <?php
                    $images = [];
                    if ($booking->vehicle->vehicle_image) {
                        $images[] = $booking->vehicle->vehicle_image;
                    }
                    if (!empty($booking->vehicle->sub_image) && is_array($booking->vehicle->sub_image)) {
                        $images = array_merge($images, $booking->vehicle->sub_image);
                    }
                    if (empty($images)) {
                        $images[] = null;
                    }

                    $mainImage = $images[0]
                        ? asset('storage/' . ltrim($images[0], '/'))
                        : 'https://via.placeholder.com/280x180?text=No+Image';
                    $thumbnails = array_slice($images, 1);
                    $badgeColors = [
                        'quotation' => '#6c757d',
                        'confirmed' => '#198754',
                        'completed' => '#20c997',
                        'cancelled' => '#dc3545',
                    ];
                    $badgeColor = $badgeColors[$booking->status] ?? '#6c757d';
                ?>

                <div
                    style="max-width:800px; margin:0 auto; font-family:Arial, sans-serif; background:#fff; padding:40px; border:1px solid #ddd;">
                    <!-- Company Info -->
                    <div style="text-align:center; margin-bottom:30px; padding-bottom:20px; border-bottom:2px solid #333;">
                        <div style="margin-bottom:15px;">
                            <img src="<?php echo e(asset('images/vacayguider.png')); ?>" alt="Company Logo"
                                style="max-width:150px; height:auto;" onerror="this.style.display='none'">
                        </div>
                        <p style="margin:5px 0; color:#666; font-size:14px;">123 Business Street, City, State 12345</p>
                        <p style="margin:5px 0; color:#666; font-size:14px;">Phone: +94 114 272 372 | Email:
                            info@vacayguider.com</p>
                        <p style="margin:5px 0; color:#666; font-size:14px;">Website: www.vacayguider.com</p>
                    </div>

                    <!-- Invoice Header -->
                    <div style="text-align:center; margin-bottom:30px;">
                        <h2 style="margin:0 0 10px 0; font-size:24px; color:#333;">TRANSPORT INVOICE</h2>
                        <span
                            style="background:<?php echo e($badgeColor); ?>; color:white; padding:5px 15px; border-radius:3px; font-size:12px; font-weight:bold;"><?php echo e(strtoupper($booking->status)); ?></span>
                    </div>

                    <!-- Customer & Invoice Details -->
                    <table style="width:100%; margin-bottom:30px; border-collapse: collapse;border: none;">
                        <tr>
                            <td style="vertical-align: top; width:50%; padding-right:15px;">
                                <h3
                                    style="margin:0 0 10px 0; font-size:14px; color:#333; font-weight:bold; text-transform:uppercase;">
                                    Bill To:</h3>
                                <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Name:</strong>
                                    <?php echo e($booking->customer->name); ?></p>
                                <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Email:</strong>
                                    <?php echo e($booking->customer->email ?? 'N/A'); ?></p>
                                <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Phone:</strong>
                                    <?php echo e($booking->customer->contact ?? 'N/A'); ?></p>
                            </td>
                            <td style="vertical-align: top; width:50%; padding-left:15px; text-align:right;">
                                <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Invoice No:</strong>
                                    <?php echo e($booking->inv_no); ?></p>
                                <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Invoice Date:</strong>
                                    <?php echo e($booking->created_at->format('F d, Y')); ?></p>
                                <p style="margin:5px 0; color:#666; font-size:14px;"><strong>Payment Status:</strong> <span
                                        style="color:<?php echo e($badgeColor); ?>; font-weight:bold;"><?php echo e(strtoupper($booking->payment_status)); ?></span>
                                </p>
                            </td>
                        </tr>
                    </table>

                    <!-- Vehicle Details -->
                    <div style="width:100%; text-align:center; margin:30px 0;">
                        <table
                            style="max-width:600px; width:100%; margin:0 auto; background:#f9f9f9; border-radius:5px; border-collapse:collapse;">
                            <tr>
                                <td style="padding:20px;">
                                    <h3
                                        style="margin:0 0 15px 0; font-size:16px; color:#333; font-weight:bold; text-align:center;">
                                        Vehicle Details</h3>
                                    <table style="width:100%; border-collapse:collapse;">
                                        <tr>
                                            <td
                                                style="width:280px; vertical-align:top; text-align:center; padding-right:15px;">
                                                <img src="<?php echo e($mainImage); ?>"
                                                    style="width:280px; height:auto; max-height:180px; object-fit:cover; border-radius:5px; border:1px solid #ddd;"
                                                    alt="Vehicle">
                                                <div style="margin-top:8px;">
                                                    <?php $__currentLoopData = $thumbnails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <img src="<?php echo e($thumb ? asset('storage/' . ltrim($thumb, '/')) : 'https://via.placeholder.com/60x45?text=No+Image'); ?>"
                                                            style="width:60px;height:45px;object-fit:cover;margin:0 3px;border-radius:3px;border:1px solid #ddd;">
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </td>
                                            <td style="vertical-align:top; text-align:left; padding-left:15px;">
                                                <p style="margin:0 0 10px 0; font-size:18px; color:#333; font-weight:bold;">
                                                    <?php echo e($booking->vehicle->name); ?> - <?php echo e($booking->vehicle->model); ?></p>
                                                <p style="margin:8px 0; color:#666; font-size:14px;">
                                                    <strong>Pickup:</strong> <?php echo e($booking->pickup_location); ?></p>
                                                <p style="margin:8px 0; color:#666; font-size:14px;">
                                                    <strong>Date/Time:</strong>
                                                    <?php echo e($booking->pickup_datetime?->format('Y-m-d H:i') ?? 'N/A'); ?></p>
                                                <p style="margin:8px 0; color:#666; font-size:14px;">
                                                    <strong>Drop-off:</strong> <?php echo e($booking->dropoff_location); ?></p>
                                                <p style="margin:8px 0; color:#666; font-size:14px;">
                                                    <strong>Date/Time:</strong>
                                                    <?php echo e($booking->dropoff_datetime?->format('Y-m-d H:i') ?? 'N/A'); ?></p>
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
                                <td style="padding:12px; text-align:right; font-size:14px; color:#666;">
                                    <?php echo e($booking->currency); ?> <?php echo e(number_format($booking->price, 2)); ?></td>
                            </tr>
                            <tr style="border-bottom:1px solid #ddd;">
                                <td style="padding:12px; font-size:14px; color:#666;">Additional Charges</td>
                                <td style="padding:12px; text-align:right; font-size:14px; color:#666;">
                                    <?php echo e($booking->currency); ?> <?php echo e(number_format($booking->additional_charges, 2)); ?></td>
                            </tr>
                            <tr style="border-bottom:1px solid #ddd;">
                                <td style="padding:12px; font-size:14px; color:#666;">Discount</td>
                                <td style="padding:12px; text-align:right; font-size:14px; color:#28a745;">-
                                    <?php echo e($booking->currency); ?> <?php echo e(number_format($booking->discount, 2)); ?></td>
                            </tr>
                            <tr style="background:#f0f0f0;">
                                <td style="padding:15px 12px; font-size:16px; color:#333; font-weight:bold;">TOTAL AMOUNT
                                </td>
                                <td
                                    style="padding:15px 12px; text-align:right; font-size:18px; color:#333; font-weight:bold;">
                                    <?php echo e($booking->currency); ?> <?php echo e(number_format($booking->total_price, 2)); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <?php if($booking->note): ?>
                        <div style="margin-bottom:20px; padding:15px; background:#fffbea; border-left:4px solid #ffc107;">
                            <p style="margin:0; font-size:14px; color:#666;"><strong>Note:</strong> <?php echo e($booking->note); ?>

                            </p>
                        </div>
                    <?php endif; ?>

                    <div style="margin-top:40px; padding-top:20px; border-top:1px solid #ddd; text-align:center;">
                        <p style="margin:5px 0; color:#999; font-size:12px;">Thank you for your business!</p>
                        <p style="margin:5px 0; color:#999; font-size:12px;">For questions about this invoice, please
                            contact us at info@vacayguider.com</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function generatePdf() {
            const htmlContent = document.querySelector('#invoiceContent').innerHTML;

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
                .then(response => {
                    if (!response.ok) throw new Error("PDF generation failed");
                    return response.blob();
                })
                .then(blob => {
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'Vehicle_Booking_Invoice.pdf';
                    link.click();
                    URL.revokeObjectURL(link.href);
                })
                .catch(error => {
                    console.error("Error generating PDF:", error);
                    alert("Failed to generate PDF. Please try again.");
                });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Vehicle Booking Details'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/bookings/vehicle/show.blade.php ENDPATH**/ ?>