<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Preferred Dates</th>
            <th>Travelers</th>
            <th>Message</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($req->name); ?></td>
                <td><?php echo e($req->email); ?></td>
                <td><?php echo e($req->phone); ?></td>
                <td><?php echo e($req->preferred_dates); ?></td>
                <td><?php echo e($req->travelers); ?></td>
                <td><?php echo e($req->message); ?></td>

                <td>
                    <select class="form-select form-select-sm changeStatus" data-id="<?php echo e($req->id); ?>">
                        <option value="pending" <?php echo e($req->status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="viewed" <?php echo e($req->status == 'viewed' ? 'selected' : ''); ?>>Viewed</option>
                        <option value="completed" <?php echo e($req->status == 'completed' ? 'selected' : ''); ?>>Completed</option>
                    </select>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="7" class="text-center">No custom tour requests found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo e($requests->links()); ?>

<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/enquiry/custom-tour-table.blade.php ENDPATH**/ ?>