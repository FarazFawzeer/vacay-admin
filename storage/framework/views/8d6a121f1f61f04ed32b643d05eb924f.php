<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Customer</th>
            <th>Visa</th>
            <th>Passport No</th>
            <th>Issue Date</th>
            <th>Expiry Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr id="booking-<?php echo e($booking->id); ?>">
                <td><?php echo e($booking->inv_no); ?></td>
                <td><?php echo e($booking->customer->name ?? '-'); ?></td>
                <td><?php echo e($booking->visa->country ?? '-'); ?> - <?php echo e($booking->visa->visa_type ?? '-'); ?></td>
                <td><?php echo e($booking->passport_number ?? '-'); ?></td>
                <td><?php echo e($booking->visa_issue_date?->format('d M Y') ?? '-'); ?></td>
                <td><?php echo e($booking->visa_expiry_date?->format('d M Y') ?? '-'); ?></td>

                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                            id="statusDropdown<?php echo e($booking->id); ?>" data-bs-toggle="dropdown">
                            <?php echo e(ucfirst($booking->status)); ?>

                        </button>
                        <ul class="dropdown-menu">
                            <?php $__currentLoopData = ['pending', 'approved', 'rejected']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a class="dropdown-item change-status" href="#" data-id="<?php echo e($booking->id); ?>"
                                        data-status="<?php echo e($status); ?>">
                                        <?php echo e(ucfirst($status)); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </td>

                <td class="text-center">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <!-- View -->
                        <a href="<?php echo e(route('admin.visa-bookings.show', $booking->id)); ?>" class="text-info"
                            style="text-decoration:none;">
                            <i class="bi bi-eye-fill fs-5"></i>
                        </a>

                        <!-- Edit -->
                        <a href="<?php echo e(route('admin.visa-bookings.edit', $booking->id)); ?>" class="text-primary"
                            style="text-decoration:none;">
                            <i class="bi bi-pencil-square fs-5"></i>
                        </a>

                        <!-- Delete -->
                        <button type="button" data-id="<?php echo e($booking->id); ?>"
                            class="btn btn-link text-danger p-0 delete-booking">
                            <i class="bi bi-trash-fill fs-5"></i>
                        </button>
                    </div>
                </td>

            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="text-center">No visa bookings found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo e($bookings->links()); ?>

<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/bookings/visa/table.blade.php ENDPATH**/ ?>