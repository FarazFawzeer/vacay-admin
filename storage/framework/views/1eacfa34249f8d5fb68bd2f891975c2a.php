<div class="table-responsive" style="overflow-x:auto;">
<table class="table table-striped table-hover table-sm text-nowrap">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Service</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($lead->name); ?></td>
            <td><?php echo e($lead->email); ?></td>
            <td><?php echo e($lead->phone); ?></td>
            <td><?php echo e($lead->service); ?></td>
            <td>
                <select class="form-select form-select-sm changeStatus" data-id="<?php echo e($lead->id); ?>">
                    <option value="pending" <?php echo e($lead->status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="processing" <?php echo e($lead->status == 'processing' ? 'selected' : ''); ?>>Processing</option>
                    <option value="completed" <?php echo e($lead->status == 'completed' ? 'selected' : ''); ?>>Completed</option>
                </select>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="5" class="text-center">No chatbot leads found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</div>

<?php echo e($leads->links()); ?>

<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/enquiry/chatbot-table.blade.php ENDPATH**/ ?>