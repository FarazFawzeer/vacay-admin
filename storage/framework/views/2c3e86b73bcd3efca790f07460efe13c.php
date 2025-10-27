

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Blog Posts', 'subtitle' => 'Edit'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Blog Post</h5>
        </div>

        <div class="card-body">
            
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.blogs.update', $blog->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Blog Title</label>
                        <input type="text" name="title" id="title" class="form-control"
                            value="<?php echo e(old('title', $blog->title)); ?>" placeholder="Enter blog title" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="posted_time" class="form-label">Posted Time</label>
                        <input type="datetime-local" name="posted_time" id="posted_time" class="form-control"
                            value="<?php echo e(old('posted_time', $blog->posted_time ? \Carbon\Carbon::parse($blog->posted_time)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i'))); ?>">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="type" class="form-label">Blog Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="" disabled>Select Type</option>
                            <?php $__currentLoopData = ['Tour', 'Airline Tickets', 'Vehicle Rental', 'Transportation', 'Visa Assistance', 'Sponsored']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type); ?>"
                                    <?php echo e(old('type', $blog->type) == $type ? 'selected' : ''); ?>>
                                    <?php echo e($type); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                
                <div class="mb-3">
                    <label for="description" class="form-label">Content / Description</label>
                    <textarea name="description" id="description" class="form-control" rows="5"
                        placeholder="Write your blog content here..."><?php echo e(old('description', $blog->description)); ?></textarea>
                </div>

                
                
                <?php
                    $images = is_array($blog->image_post)
                        ? $blog->image_post
                        : json_decode($blog->image_post, true) ?? [];
                ?>
                <?php if(!empty($images)): ?>
                    <div class="mb-3">
                        <label class="form-label d-block">Existing Images</label>
                        <div class="d-flex flex-wrap gap-3">
                            <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="position-relative border rounded p-1" style="width: 120px;">
                                    <img src="<?php echo e(asset('storage/' . $img)); ?>" alt="Blog Image" class="img-thumbnail"
                                        style="width: 100%; height: 100px; object-fit: cover;">
                                    <button type="button"
                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-image"
                                        data-index="<?php echo e($index); ?>" title="Remove Image">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    
                                    <input type="hidden" name="existing_images[]" value="<?php echo e($img); ?>">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <input type="hidden" name="remove_images" id="remove_images" value="">
                    </div>
                <?php endif; ?>

                
                <div class="mb-3">
                    <label for="image_post" class="form-label">Add More Images</label>
                    <input type="file" name="image_post[]" id="image_post" class="form-control" multiple>
                    <small class="text-muted">You can select multiple images (existing ones will remain unless
                        removed)</small>
                </div>


                
                <?php
                    $hashtags = is_array($blog->hashtags) ? $blog->hashtags : json_decode($blog->hashtags, true) ?? [];
                ?>
                <div class="mb-3">
                    <label for="hashtags" class="form-label">Hashtags</label>
                   <input type="text" name="hashtags" id="hashtags" class="form-control"
       value="<?php echo e(old('hashtags', implode(',', $hashtags))); ?>"
       placeholder="e.g. travel, adventure, blog">

                    <small class="text-muted">Separate hashtags with commas</small>
                </div>

                
                <div class="mb-3 col-md-3">
                    <label for="likes_count" class="form-label">Likes Count</label>
                    <input type="number" name="likes_count" id="likes_count" class="form-control"
                        value="<?php echo e(old('likes_count', $blog->likes_count ?? 0)); ?>" min="0">
                </div>

                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update Blog Post</button>
                </div>
            </form>
        </div>
    </div>

    
    <script>
        // Automatically fade alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.classList.remove('show');
                alert.classList.add('hide');
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);

          document.querySelectorAll('.remove-image').forEach(btn => {
        btn.addEventListener('click', function () {
            const index = this.dataset.index;
            const wrapper = this.closest('.position-relative');
            wrapper.remove();

            // Add index to hidden remove_images input
            const removed = document.getElementById('remove_images');
            let current = removed.value ? JSON.parse(removed.value) : [];
            current.push(index);
            removed.value = JSON.stringify(current);
        });
    });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Edit Blog Post'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/blog/edit.blade.php ENDPATH**/ ?>