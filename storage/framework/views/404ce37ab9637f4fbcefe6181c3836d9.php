<div class="table-responsive" style="overflow-x:auto;">
    <table class="table table-striped table-hover table-sm text-nowrap">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>License No</th>
                <th>WhatsApp</th>
                <th>Collection Method</th>
                <th>License Front</th>
                <th>License Back</th>
                <th>Selfie</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($request->guest_name); ?></td>
                    <td><?php echo e($request->email); ?></td>
                    <td><?php echo e($request->license_no); ?></td>
                    <td><?php echo e($request->whatsapp); ?></td>
                    <td><?php echo e(ucfirst($request->collection_method)); ?></td>
                    <td>
                        <?php if($request->license_front): ?>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal"
                                data-src="<?php echo e(config('app.fe_domain')); ?>/<?php echo e(ltrim($request->license_front, '/')); ?>">
                                View
                            </a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if($request->license_back): ?>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal"
                                data-src="<?php echo e(config('app.fe_domain')); ?>/<?php echo e(ltrim($request->license_back, '/')); ?>">
                                View
                            </a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if($request->selfie): ?>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal"
                                data-src="<?php echo e(config('app.fe_domain')); ?>/<?php echo e(ltrim($request->selfie, '/')); ?>">
                                View
                            </a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>

                    <td>
                        <select class="form-select form-select-sm changeStatus" data-id="<?php echo e($request->id); ?>">
                            <option value="pending" <?php echo e($request->status == 'pending' ? 'selected' : ''); ?>>Pending
                            </option>
                            <option value="viewed" <?php echo e($request->status == 'viewed' ? 'selected' : ''); ?>>Viewed</option>
                            <option value="completed" <?php echo e($request->status == 'completed' ? 'selected' : ''); ?>>Completed
                            </option>
                        </select>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="text-center">No driving permit requests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php echo e($requests->links()); ?>


<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageModal = document.getElementById('imageModal');
        imageModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const src = button.getAttribute('data-src');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = src;
        });
    });
</script>
<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/enquiry/driving-permit-table.blade.php ENDPATH**/ ?>