<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Heading</th>
            <th>Ref No</th>
            <th>Country</th>
            <th>Place</th>
            <th>Type</th>
            <th>Category</th>
            <th>Days / Nights</th>
            <th>Price</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr id="package-<?php echo e($package->id); ?>">
                <td><?php echo e($package->heading); ?></td>
                <td><?php echo e($package->tour_ref_no); ?></td>
                <td><?php echo e($package->country_name ?? '-'); ?></td>
                <td><?php echo e($package->place ?? '-'); ?></td>
                <td><?php echo e($package->type ?? '-'); ?></td>
                <td><?php echo e($package->tour_category ?? '-'); ?></td>
                <td><?php echo e($package->days ?? 0); ?> / <?php echo e($package->nights ?? 0); ?></td>
                <td><?php echo e($package->price ?? '-'); ?></td>
                <td><?php echo e($package->status ?? '-'); ?></td>
                <td class="text-center">

                    
                    <a href="<?php echo e(route('admin.packages.show', $package->id)); ?>" class="icon-btn text-info"
                        title="View Package">
                        <i class="bi bi-eye-fill fs-5"></i>
                    </a>

                    
                    <a href="<?php echo e(route('admin.packages.edit', $package->id)); ?>" class="icon-btn text-primary"
                        title="Edit Package">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </a>

                    
                    <button type="button" data-id="<?php echo e($package->id); ?>"
                        class="icon-btn <?php echo e($package->status ? 'text-success' : 'text-warning'); ?> toggle-status"
                        data-status="<?php echo e($package->status); ?>"
                        title="<?php echo e($package->status ? 'Change to Not Published' : 'Change to Published'); ?>">
                        <?php if($package->status): ?>
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        <?php else: ?>
                            <i class="bi bi-slash-circle fs-5"></i>
                        <?php endif; ?>
                    </button>

                    
                    <button type="button" data-id="<?php echo e($package->id); ?>" class="icon-btn text-danger delete-package"
                        title="Delete Package">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>

                </td>



            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="10" class="text-center">No packages found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo e($packages->links()); ?>

<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/tour/table.blade.php ENDPATH**/ ?>