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
                <td>
                    <a href="<?php echo e(route('admin.packages.edit', $package->id)); ?>" class="btn btn-sm btn-equal btn-primary">Edit</a>
                    <button type="button" data-id="<?php echo e($package->id); ?>" class="btn btn-sm btn-danger btn-equal delete-package">Delete</button>
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