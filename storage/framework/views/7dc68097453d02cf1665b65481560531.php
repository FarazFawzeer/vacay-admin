<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>WhatsApp</th>
            <th>Country</th>
            <th>Pickup</th>
            <th>Dropoff</th>
            <th>Start</th>
            <th>End</th>
            <th>Vehicle</th>
            <th>Service Type</th>
            <th>Hour Count</th>
            <th>Message</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($req->full_name); ?></td>
                <td><?php echo e($req->email); ?></td>
                <td><?php echo e($req->phone); ?></td>
                <td><?php echo e($req->whatsapp); ?></td>
                <td><?php echo e($req->country); ?></td>
                <td><?php echo e($req->pickup_location); ?></td>
                <td><?php echo e($req->drop_location); ?></td>
                <td><?php echo e($req->start_date?->format('d M Y')); ?> <?php echo e($req->start_time?->format('H:i')); ?></td>
                <td><?php echo e($req->end_date?->format('d M Y')); ?> <?php echo e($req->end_time?->format('H:i')); ?></td>
                <td><?php echo e($req->vehicle->name ?? 'N/A'); ?></td>
                <td><?php echo e(ucfirst($req->service_type)); ?></td>
                <td><?php echo e($req->hour_count); ?></td>
                <td><?php echo e($req->message); ?></td>
                <td>
                    <select class="form-select form-select-sm changeStatus" data-id="<?php echo e($req->id); ?>">
                        <option value="pending" <?php echo e($req->status=='pending'?'selected':''); ?>>Pending</option>
                        <option value="confirmed" <?php echo e($req->status=='confirmed'?'selected':''); ?>>Confirmed</option>
                        <option value="completed" <?php echo e($req->status=='completed'?'selected':''); ?>>Completed</option>
                        <option value="cancelled" <?php echo e($req->status=='cancelled'?'selected':''); ?>>Cancelled</option>
                    </select>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="14" class="text-center">No transportation bookings found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo e($requests->links()); ?>

<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/enquiry/transport-table.blade.php ENDPATH**/ ?>