

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Tour Packages', 'subtitle' => 'View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <style>
        .btn-equal {
            width: 80px;
            /* or any fixed width you want */
            text-align: center;
        }
    </style>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Tour Package List</h5>
            <p class="card-subtitle">All tour packages in your system.</p>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3 justify-content-end">
                <div class="col-md-3">
                    <label for="filterType" class="form-label">Type</label>
                    <select id="filterType" class="form-select">
                        <option value="">All</option>
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type); ?>"><?php echo e(ucfirst($type)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filterCategory" class="form-label">Category</label>
                    <select id="filterCategory" class="form-select">
                        <option value="">All</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category); ?>"><?php echo e(ucfirst($category)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filterStatus" class="form-label">Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive" id="packageTable">
                <?php echo $__env->make('tour.table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('filterType');
            const categorySelect = document.getElementById('filterCategory');
            const statusSelect = document.getElementById('filterStatus');

          function fetchFilteredData(url = null) {
    let params = new URLSearchParams({
        type: typeSelect.value,
        category: categorySelect.value,
        status: statusSelect.value
    });

    url = url || "<?php echo e(route('admin.packages.index')); ?>";
    if (url.includes('?')) {
        url += `&${params.toString()}`;
    } else {
        url += `?${params.toString()}`;
    }

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(res => res.text())
    .then(data => {
        document.getElementById('packageTable').innerHTML = data;
        attachDeleteEvents();
    });
}

// Pagination clicks
document.addEventListener('click', function(e) {
    if (e.target.closest('#packageTable .pagination a')) {
        e.preventDefault();
        let url = e.target.getAttribute('href');
        fetchFilteredData(url);
    }
});


            typeSelect.addEventListener('change', function() {
                fetchFilteredData();
            });
            categorySelect.addEventListener('change', function() {
                fetchFilteredData();
            });
            statusSelect.addEventListener('change', function() {
                fetchFilteredData();
            });
            // AJAX Pagination
            document.addEventListener('click', function(e) {
                if (e.target.closest('#packageTable .pagination a')) {
                    e.preventDefault();
                    let url = e.target.getAttribute('href');
                    fetchFilteredData(url);
                }
            });

            // Delete Package
            function attachDeleteEvents() {
                document.querySelectorAll('.delete-package').forEach(button => {
                    button.addEventListener('click', function() {
                        let packageId = this.dataset.id;

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch("<?php echo e(url('admin/packages')); ?>/" + packageId, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                                            'Accept': 'application/json'
                                        },
                                        credentials: 'same-origin'
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            document.getElementById('package-' +
                                                packageId).remove();
                                            Swal.fire('Deleted!', data.message,
                                                'success');
                                        } else {
                                            Swal.fire('Error!', data.message ||
                                                'Something went wrong!', 'error');
                                        }
                                    })
                                    .catch(() => Swal.fire('Error!',
                                        'Something went wrong!', 'error'));
                            }
                        });
                    });
                });
            }

            attachDeleteEvents();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Tour Packages'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/tour/view.blade.php ENDPATH**/ ?>