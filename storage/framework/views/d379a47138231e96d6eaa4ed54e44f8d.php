

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', [
        'title' => 'Manage Policies',
        'subtitle' => 'Inclusion | Exclusion | Cancellation',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <style>

        .point-input .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}


        .card-policy {
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .policy-heading {
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
        }

        .point-input {
            margin-bottom: 10px;
        }
    </style>

    <div class="container-fluid">

        <?php
            $types = [
                'inclusion' => 'Inclusion',
                'exclusion' => 'Exclusion',
                'cancellation' => 'Cancellation',
            ];
        ?>

        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card card-policy">
                <div class="card-body">
                    <h5 class="policy-heading mb-3"><?php echo e($label); ?></h5>

                    <form class="policyForm" data-type="<?php echo e($key); ?>" method="POST"
                        action="<?php echo e(route('admin.inclusions.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="type" value="<?php echo e($key); ?>">

                        <div class="mb-3">
                            <label>Heading</label>
                            <input type="text" name="heading" class="form-control" value="<?php echo e(${$key}->heading ?? ''); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Points</label>
                            <div class="points-container">
                                <?php
                                    $points = ${$key}->points ?? [''];
                                ?>
                                <?php $__currentLoopData = $points; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="input-group point-input mb-2">
                                        <input type="text" name="points[]" class="form-control"
                                            value="<?php echo e($point); ?>" placeholder="Enter a point">
                                        <button type="button" class="btn btn-outline-danger remove-point">−</button>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary add-point mt-2">+ Add
                                Point</button>
                        </div>


                        <div class="mb-3">
                            <label>Note</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Optional note..."><?php echo e(${$key}->note ?? ''); ?></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Save <?php echo e($label); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add a new point
    document.querySelectorAll('.add-point').forEach(btn => {
        btn.addEventListener('click', function() {
            const container = this.closest('.mb-3').querySelector('.points-container');
            const div = document.createElement('div');
            div.className = 'input-group point-input mb-2';
            div.innerHTML = `
                <input type="text" name="points[]" class="form-control" placeholder="Enter a point">
                <button type="button" class="btn btn-outline-danger remove-point">−</button>
            `;
            container.appendChild(div);
        });
    });

    // Remove a point
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-point')) {
            e.target.closest('.point-input').remove();
        }
    });

    // Form submission (same as before)
    document.querySelectorAll('.policyForm').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', data.message || 'Something went wrong', 'error');
                }
            });
        });
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Inclusions / Exclusions / Cancellation'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/details/inclusion.blade.php ENDPATH**/ ?>