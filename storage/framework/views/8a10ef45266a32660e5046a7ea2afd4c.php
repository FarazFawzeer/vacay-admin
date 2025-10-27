<table class="table table-striped table-hover align-middle">
    <thead>
        <tr>
            <th>Name</th>
            <th>Source</th>
            <th>Posted On</th>
            <th>Rating</th>
            <th>Message</th>
            <th>Image</th>
            <th>Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr id="testimonial-<?php echo e($testimonial->id); ?>">
                <td><?php echo e($testimonial->name); ?></td>
                <td><?php echo e($testimonial->source ?? '-'); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($testimonial->postedate)->format('d M Y')); ?></td>
                <td><?php echo e($testimonial->rating ?? '-'); ?></td>
                <td><?php echo e(Str::limit($testimonial->message, 50)); ?></td>
                <td>
                    <?php if($testimonial->image): ?>
                        <img src="<?php echo e(asset('storage/' . $testimonial->image)); ?>" alt="Customer Image">
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?php echo e($testimonial->status ? 'Active' : 'Inactive'); ?></td>
                <td class="text-center">
                    
                    <a href="<?php echo e(route('admin.testimonials.edit', $testimonial->id)); ?>" class="icon-btn text-primary" title="Edit">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </a>

                    
                    <button type="button" data-id="<?php echo e($testimonial->id); ?>" class="icon-btn <?php echo e($testimonial->status ? 'text-success' : 'text-warning'); ?> toggle-status" data-status="<?php echo e($testimonial->status); ?>">
                        <?php if($testimonial->status): ?>
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        <?php else: ?>
                            <i class="bi bi-slash-circle fs-5"></i>
                        <?php endif; ?>
                    </button>

                    
                    <button type="button" data-id="<?php echo e($testimonial->id); ?>" class="icon-btn text-danger delete-testimonial" title="Delete">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="text-center">No testimonials found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo e($testimonials->links()); ?>

<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/testimonials/table.blade.php ENDPATH**/ ?>