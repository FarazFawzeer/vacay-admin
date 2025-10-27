

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Tour Packages', 'subtitle' => 'View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <style>
        .btn-equal {
            width: 80px;
            /* or any fixed width you want */
            text-align: center;
        }

        .icon-btn {
            background: none;
            border: none;
            padding: 4px;
            margin: 0 2px;
            cursor: pointer;
            transition: transform 0.2s, color 0.2s;
        }

        .icon-btn:hover {
            transform: scale(1.2);
            opacity: 0.8;
            text-decoration: none;
        }
    </style>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Tour Package List</h5>
            <p class="card-subtitle">All tour packages in your system.</p>
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
            <!-- Filters -->
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
                    <label for="filterStatus" class="form-label">Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">All</option>
                        <option value="active">Published</option>
                        <option value="inactive">Unpublished</option>
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
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.classList.remove('show');
                alert.classList.add('hide');
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);

      document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('filterType');
    const statusSelect = document.getElementById('filterStatus');
    const tableContainer = document.getElementById('packageTable');

    function fetchFilteredData(url = null) {
        url = url || "<?php echo e(route('admin.blogs.index')); ?>";

        const params = new URLSearchParams({
            type: typeSelect.value || '',
            status: statusSelect.value || ''
        });

        // Append params to URL safely
        url = url.split('?')[0] + '?' + params.toString();

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
        .then(res => res.text())
        .then(data => {
            tableContainer.innerHTML = data;
        })
        .catch(err => console.error('AJAX fetch error:', err));
    }

    // Listen for filter changes
    [typeSelect, statusSelect].forEach(el => {
        if (el) el.addEventListener('change', fetchFilteredData);
    });

    // Pagination links inside table
    document.addEventListener('click', function(e) {
        if (e.target.closest('#packageTable .pagination a')) {
            e.preventDefault();
            const url = e.target.getAttribute('href');
            fetchFilteredData(url);
        }
    });
});


    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Tour Packages'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/tour/view.blade.php ENDPATH**/ ?>