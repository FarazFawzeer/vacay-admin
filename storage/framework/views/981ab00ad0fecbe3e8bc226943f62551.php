<div class="table-responsive" style="overflow-x:auto;">
<table class="table table-striped table-hover table-sm text-nowrap">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>WhatsApp</th>
            <th>Country</th>
            <th>Trip Type</th>
            <th>Airline</th>
            <th>From</th>
            <th>To</th>
            <th>Departure</th>
            <th>Return</th>
            <th>Passengers</th>
            <th style="min-width: 200px;">Message</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr>
        <td><?php echo e($booking->full_name); ?></td>
        <td><?php echo e($booking->email); ?></td>
        <td><?php echo e($booking->phone); ?></td>
        <td><?php echo e($booking->whatsapp); ?></td>
        <td><?php echo e($booking->country); ?></td>
        <td><?php echo e(ucfirst($booking->trip_type)); ?></td>
        <td><?php echo e($booking->airline); ?></td>
        <td><?php echo e($booking->from); ?></td>
        <td><?php echo e($booking->to); ?></td>
        <td><?php echo e($booking->departure_date); ?></td>
        <td><?php echo e($booking->return_date ?? '-'); ?></td>
        <td><?php echo e($booking->passengers); ?></td>
        <td><?php echo e($booking->message); ?></td>
        <td>
            <select class="form-select form-select-sm changeStatus" data-id="<?php echo e($booking->id); ?>">
                <option value="pending" <?php echo e($booking->status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="viewed" <?php echo e($booking->status == 'viewed' ? 'selected' : ''); ?>>Viewed</option>
                <option value="completed" <?php echo e($booking->status == 'completed' ? 'selected' : ''); ?>>Completed</option>
            </select>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="14" class="text-center">No airline bookings found.</td>
    </tr>
<?php endif; ?>

    </tbody>
</table>
</div>

<?php echo e($bookings->links()); ?>

<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/enquiry/air-ticket-table.blade.php ENDPATH**/ ?>