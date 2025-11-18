<div class="table-responsive" style="overflow-x:auto;">
<table class="table table-striped table-hover table-sm text-nowrap">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Country</th>
            <th>Service</th>
            <th>Message</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr>
        <td><?php echo e($contact->name); ?></td>
        <td><?php echo e($contact->email); ?></td>
        <td><?php echo e($contact->phone); ?></td>
        <td><?php echo e($contact->country); ?></td>
        <td><?php echo e($contact->service); ?></td>
        <td><?php echo e($contact->message); ?></td>
        <td>
            <select class="form-select form-select-sm changeStatus" data-id="<?php echo e($contact->id); ?>">
                <option value="pending" <?php echo e($contact->status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="viewed" <?php echo e($contact->status == 'viewed' ? 'selected' : ''); ?>>Viewed</option>
                <option value="completed" <?php echo e($contact->status == 'completed' ? 'selected' : ''); ?>>Completed</option>
            </select>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="7" class="text-center">No contact inquiries found.</td>
    </tr>
    <?php endif; ?>
    </tbody>
</table>
</div>

<?php echo e($contacts->links()); ?>

<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/enquiry/contact-infor-table.blade.php ENDPATH**/ ?>