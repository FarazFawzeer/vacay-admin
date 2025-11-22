

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', [
        'title' => 'Visa Booking',
        'subtitle' => 'Create',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Visa Booking</h5>
        </div>

        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.visa-bookings.store')); ?>" method="POST" id="visaBookingForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="invoice_no" value="<?php echo e($nextInvoice ?? 'VB-0001'); ?>">

                <div class="row">
                    <!-- Customer -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($customer->id); ?>" data-email="<?php echo e($customer->email ?? 'N/A'); ?>"
                                    data-phone="<?php echo e($customer->contact ?? 'N/A'); ?>">
                                    <?php echo e($customer->name); ?> (<?php echo e($customer->customer_code ?? 'N/A'); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Visa -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Visa</label>
                        <select name="visa_id" id="visa_id" class="form-select" required>
                            <option value="">Select Visa</option>
                            <?php $__currentLoopData = $visas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($visa->id); ?>" data-country="<?php echo e($visa->country); ?>">
                                    <?php echo e($visa->country); ?> - <?php echo e($visa->visa_type); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Passport Number -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Passport Number</label>
                        <input type="text" name="passport_number" id="passport_number" class="form-control" required>
                    </div>

                    <!-- Visa Type -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Visa Type</label>
                        <input type="text" name="type" id="type" class="form-control" readonly>
                    </div>

                    <!-- Agent -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agent</label>
                        <input type="text" name="agent" id="agent" class="form-control">
                    </div>

                    <!-- Visa Issue Date -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Visa Issue Date</label>
                        <input type="date" name="visa_issue_date" id="visa_issue_date" class="form-control" required>
                    </div>

                    <!-- Visa Expiry Date -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Visa Expiry Date</label>
                        <input type="date" name="visa_expiry_date" id="visa_expiry_date" class="form-control" required>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="notes" rows="4" class="form-control"></textarea>
                    </div>
                </div>

                <div class="text-end gap-2 d-flex justify-content-end">
                    <a href="<?php echo e(route('admin.visa-bookings.index')); ?>" class="btn btn-light" style="width:130px;">Back</a>
                    <button type="button" class="btn btn-secondary" onclick="previewVisaBooking()"
                        style="width:130px;">Preview</button>
                    <button type="submit" class="btn btn-primary" style="width:130px;">Save Booking</button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal fade" id="bookingPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Visa Booking Preview</h5>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        // Fill visa type automatically on selection
        document.getElementById('visa_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            document.getElementById('type').value = selected.dataset.country ? selected.text.split(' - ')[1] : '';
        });

        function previewVisaBooking() {
            const customerSelect = document.getElementById('customer_id');
            const visaSelect = document.getElementById('visa_id');

            const customerName = customerSelect.options[customerSelect.selectedIndex].text;
            const customerEmail = customerSelect.options[customerSelect.selectedIndex].dataset.email;
            const customerPhone = customerSelect.options[customerSelect.selectedIndex].dataset.phone;

            const visaName = visaSelect.options[visaSelect.selectedIndex].text;

            const passportNumber = document.getElementById('passport_number').value;
            const agent = document.getElementById('agent').value;
            const visaIssueDate = document.getElementById('visa_issue_date').value || 'N/A';
            const visaExpiryDate = document.getElementById('visa_expiry_date').value || 'N/A';
            const status = document.getElementById('status').value || 'pending';
            const note = document.getElementById('notes').value;

            const invoiceNo = document.getElementById('invoice_no').value || 'N/A';
            const invoiceDate = new Date().toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Status badge colors
            const badgeColors = {
                pending: '#ffc107',
                approved: '#198754',
                rejected: '#dc3545'
            };
            const badgeColor = badgeColors[status] || '#6c757d';

            const html = `
<div style="max-width:800px;margin:0 auto;font-family:Arial,sans-serif;background:#fff;padding:40px;border:1px solid #ddd;">
    <!-- Company Logo & Details -->
    <div style="text-align:center;margin-bottom:30px;border-bottom:2px solid #333;padding-bottom:20px;">
        <img src="<?php echo e(asset('images/vacayguider.png')); ?>" alt="Company Logo" style="max-width:150px;margin-bottom:15px;">
        <p style="margin:5px 0;color:#666;font-size:14px;">123 Business Street, City, State 12345</p>
        <p style="margin:5px 0;color:#666;font-size:14px;">Phone: +94 114 272 372 | Email: info@vacayguider.com</p>
        <p style="margin:5px 0;color:#666;font-size:14px;">Website: www.vacayguider.com</p>
    </div>

    <!-- Invoice Header -->
    <div style="text-align:center;margin-bottom:30px;">
        <h2 style="margin:0 0 10px 0;font-size:24px;color:#333;">VISA INVOICE</h2>
        <span style="background:${badgeColor};color:white;padding:5px 15px;border-radius:3px;font-size:12px;font-weight:bold;">
            ${status.toUpperCase()}
        </span>
    </div>

    <!-- Customer & Invoice Info -->
    <table style="width:100%;margin-bottom:30px;border-collapse:collapse;">
        <tr>
            <td style="width:50%;vertical-align:top;padding-right:15px;">
                <h3 style="margin:0 0 10px 0;font-size:14px;color:#333;font-weight:bold;text-transform:uppercase;">Bill To:</h3>
                <p style="margin:5px 0;color:#666;font-size:14px;"><strong>Name:</strong> ${customerName}</p>
                <p style="margin:5px 0;color:#666;font-size:14px;"><strong>Email:</strong> ${customerEmail}</p>
                <p style="margin:5px 0;color:#666;font-size:14px;"><strong>Phone:</strong> ${customerPhone}</p>
            </td>
            <td style="width:50%;vertical-align:top;padding-left:15px;text-align:right;">
                <p style="margin:5px 0;color:#666;font-size:14px;"><strong>Invoice No:</strong> ${invoiceNo}</p>
                <p style="margin:5px 0;color:#666;font-size:14px;"><strong>Invoice Date:</strong> ${invoiceDate}</p>
            </td>
        </tr>
    </table>

    <!-- Visa Details -->
    <div style="width:100%;margin-bottom:30px;">
        <table style="width:100%;border-collapse:collapse;background:#f9f9f9;border-radius:5px;">
            <tr>
                <td style="padding:20px;">
                    <h3 style="margin:0 0 15px 0;font-size:16px;color:#333;font-weight:bold;text-align:center;">Visa Details</h3>
                    <table style="width:100%;border-collapse:collapse;">
                        <tr>
                            <td style="padding:8px 0;font-size:14px;color:#666;"><strong>Visa:</strong> ${visaName}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;font-size:14px;color:#666;"><strong>Passport No:</strong> ${passportNumber}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;font-size:14px;color:#666;"><strong>Agent:</strong> ${agent}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;font-size:14px;color:#666;"><strong>Issue Date:</strong> ${visaIssueDate}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;font-size:14px;color:#666;"><strong>Expiry Date:</strong> ${visaExpiryDate}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- Notes -->
    ${note ? `<div style="margin-bottom:20px;padding:15px;background:#fffbea;border-left:4px solid #ffc107;">
            <p style="margin:0;font-size:14px;color:#666;"><strong>Note:</strong> ${note}</p>
        </div>` : ''}

    <!-- Footer -->
    <div style="margin-top:40px;padding-top:20px;border-top:1px solid #ddd;text-align:center;">
        <p style="margin:5px 0;color:#999;font-size:12px;">Thank you for your business!</p>
        <p style="margin:5px 0;color:#999;font-size:12px;">For inquiries, contact info@vacayguider.com</p>
    </div>
</div>
    `;

            document.getElementById('bookingPreviewBody').innerHTML = html;
            new bootstrap.Modal(document.getElementById('bookingPreviewModal')).show();
        }


        function generatePdf() {
            const htmlContent = document.getElementById('bookingPreviewBody').innerHTML;
            fetch("<?php echo e(route('admin.visa-bookings.generatePdf')); ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                    },
                    body: JSON.stringify({
                        html: htmlContent
                    })
                }).then(res => res.blob())
                .then(blob => {
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'VisaBooking.pdf';
                    link.click();
                    URL.revokeObjectURL(link.href);
                }).catch(err => {
                    console.error(err);
                    alert("PDF generation failed");
                });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Create Visa Booking'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/bookings/visa/create.blade.php ENDPATH**/ ?>