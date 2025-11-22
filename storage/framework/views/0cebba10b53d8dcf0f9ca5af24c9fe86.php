<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Vehicle</th>
            <th>Start</th>
            <th>End</th>
            <th>Message</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($item->full_name); ?></td>
                <td><?php echo e($item->email); ?></td>
                <td><?php echo e($item->phone); ?></td>
                <td><?php echo e(optional($item->vehicle)->title ?? 'N/A'); ?></td>
                <td><?php echo e($item->start_date?->format('Y-m-d')); ?></td>
                <td><?php echo e($item->end_date?->format('Y-m-d')); ?></td>
                <td><?php echo e($item->message); ?></td>

                <td>
                    <select class="form-select form-select-sm changeStatus" data-id="<?php echo e($item->id); ?>">
                        <option value="pending" <?php echo e($item->status=='pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="viewed" <?php echo e($item->status=='viewed' ? 'selected' : ''); ?>>Viewed</option>
                        <option value="completed" <?php echo e($item->status=='completed' ? 'selected' : ''); ?>>Completed</option>
                    </select>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="text-center">No vehicle bookings found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo e($bookings->links()); ?>

<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/enquiry/rent-table.blade.php ENDPATH**/ ?>