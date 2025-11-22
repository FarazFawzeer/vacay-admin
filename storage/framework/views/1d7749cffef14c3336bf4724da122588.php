

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Testimonials', 'subtitle' => 'Edit'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Testimonial</h5>
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

            <form action="<?php echo e(route('admin.testimonials.update', $testimonial->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Customer Name</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="<?php echo e(old('name', $testimonial->name)); ?>" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="source" class="form-label">Source</label>
                        <select name="source" id="source" class="form-select">
                            <option value="" disabled>Select Source</option>
                            <?php $__currentLoopData = ['Website', 'Google Review', 'Facebook', 'TripAdvisor', 'Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($source); ?>" <?php echo e(old('source', $testimonial->source) == $source ? 'selected' : ''); ?>>
                                    <?php echo e($source); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="postedate" class="form-label">Post Date</label>
                        <input type="date" name="postedate" id="postedate" class="form-control"
                            value="<?php echo e(old('postedate', $testimonial->postedate->format('Y-m-d'))); ?>">
                    </div>
                </div>

                
                <div class="mb-3 col-md-3">
                    <label for="rating" class="form-label">Rating (1 - 5)</label>
                    <select name="rating" id="rating" class="form-select">
                        <option value="" disabled>Select Rating</option>
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e(old('rating', $testimonial->rating) == $i ? 'selected' : ''); ?>>
                                <?php echo e($i); ?> ★
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                
                <div class="mb-3">
                    <label for="message" class="form-label">Testimonial Message</label>
                    <textarea name="message" id="message" class="form-control" rows="4" required><?php echo e(old('message', $testimonial->message)); ?></textarea>
                </div>

                
                <div class="mb-3">
                    <label for="image" class="form-label">Customer Image</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    <small class="text-muted">Upload a square image (optional)</small>

                    <?php if($testimonial->image): ?>
                        <div class="mt-2">
                            <img src="<?php echo e(asset('storage/' . $testimonial->image)); ?>" alt="Customer Image" width="80" height="80" style="object-fit: cover; border-radius: 6px;">
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="mb-3 col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="1" <?php echo e(old('status', $testimonial->status) == 1 ? 'selected' : ''); ?>>Active</option>
                        <option value="0" <?php echo e(old('status', $testimonial->status) == 0 ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>

                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Update Testimonial</button>
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
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Edit Testimonial'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/testimonials/edit.blade.php ENDPATH**/ ?>