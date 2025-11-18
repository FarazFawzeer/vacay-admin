<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Customer</th>
            <th>Vehicle</th>
            <th>Start</th>
            <th>End</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr id="booking-<?php echo e($booking->id); ?>">

                <td><?php echo e($booking->inv_no); ?></td>

                <td><?php echo e($booking->customer->name ?? '-'); ?></td>

                <td><?php echo e($booking->vehicle->name ?? '-'); ?></td>

                <td>
                    <?php echo e($booking->start_datetime?->format('d M Y h:i A') ?? '-'); ?>

                </td>

                <td>
                    <?php echo e($booking->end_datetime?->format('d M Y h:i A') ?? '-'); ?>

                </td>

                <td><?php echo e($booking->currency); ?> <?php echo e(number_format($booking->total_price, 2)); ?></td>

              <td>
    <div class="dropdown">
        <button class="btn btn-sm btn-secondary dropdown-toggle"
                type="button"
                id="statusDropdown<?php echo e($booking->id); ?>"
                data-bs-toggle="dropdown">
            <?php echo e(ucfirst($booking->status)); ?>

        </button>

        <ul class="dropdown-menu">
            <?php $__currentLoopData = ['quotation', 'invoice', 'confirmed', 'completed', 'cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <a class="dropdown-item change-status"
                       href="#"
                       data-id="<?php echo e($booking->id); ?>"
                       data-status="<?php echo e($status); ?>">
                        <?php echo e(ucfirst($status)); ?>

                    </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</td>


                <td><?php echo e(ucfirst($booking->payment_status)); ?></td>

                <td class="text-center">

                    <a href="<?php echo e(route('admin.rent-vehicle-bookings.show', $booking->id)); ?>"
                        class="icon-btn text-info">
                        <i class="bi bi-eye-fill fs-5"></i>
                    </a>

                    <a href="<?php echo e(route('admin.rent-vehicle-bookings.edit', $booking->id)); ?>"
                        class="icon-btn text-primary">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </a>

                    <button data-id="<?php echo e($booking->id); ?>"
                            class="icon-btn text-danger delete-booking">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>

                </td>

            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="9" class="text-center">No rent vehicle bookings found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo e($bookings->links()); ?>

<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/bookings/rent/table.blade.php ENDPATH**/ ?>