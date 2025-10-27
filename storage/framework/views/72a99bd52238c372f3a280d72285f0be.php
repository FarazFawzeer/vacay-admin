<table class="table table-striped table-hover align-middle">
    <thead>
        <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Posted On</th>
            <th>Likes</th>
            <th>Hashtags</th>
            <th>Image</th>
            <th>Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr id="blog-<?php echo e($blog->id); ?>">
                <td><?php echo e($blog->title); ?></td>
                <td><?php echo e($blog->type ?? '-'); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($blog->posted_time)->format('d M Y, h:i A')); ?></td>
                <td><?php echo e($blog->likes_count ?? 0); ?></td>
                <td>
                    <?php if(!empty($blog->hashtags)): ?>
                        <?php $__currentLoopData = $blog->hashtags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge bg-light text-dark me-1">#<?php echo e($tag); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td>
                    <?php if(!empty($blog->image_post) && is_array($blog->image_post)): ?>
                        <img src="<?php echo e(asset('storage/' . $blog->image_post[0])); ?>" alt="Blog Image">
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?php echo e($blog->status ? 'Published' : 'Unpublished'); ?></td>
                <td class="text-center">
                   
                    
                    <a href="<?php echo e(route('admin.blogs.edit', $blog->id)); ?>" class="icon-btn text-primary" title="Edit Blog">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </a>

                    
                    <button type="button" data-id="<?php echo e($blog->id); ?>"
                        class="icon-btn <?php echo e($blog->status ? 'text-success' : 'text-warning'); ?> toggle-status"
                        data-status="<?php echo e($blog->status); ?>">
                        <?php if($blog->status): ?>
                            <i class="bi bi-check-circle-fill fs-5"></i>
                        <?php else: ?>
                            <i class="bi bi-slash-circle fs-5"></i>
                        <?php endif; ?>
                    </button>

                    
                    <button type="button" data-id="<?php echo e($blog->id); ?>" class="icon-btn text-danger delete-blog"
                        title="Delete Blog">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="text-center">No blog posts found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo e($blogs->links()); ?>

<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/blog/table.blade.php ENDPATH**/ ?>