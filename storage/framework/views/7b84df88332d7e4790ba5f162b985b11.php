

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Testimonials', 'subtitle' => 'View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <style>
        .btn-equal {
            width: 80px;
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

        .table td img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Testimonials List</h5>
            <p class="card-subtitle">All testimonials in the system.</p>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3 justify-content-end">
                <div class="col-md-3">
                    <label for="filterSource" class="form-label">Source</label>
                    <select id="filterSource" class="form-select">
                        <option value="">All</option>
                        <?php $__currentLoopData = $sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($source); ?>"><?php echo e($source); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filterStatus" class="form-label">Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">All</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive" id="testimonialTable">
                <?php echo $__env->make('testimonials.table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sourceSelect = document.getElementById('filterSource');
            const statusSelect = document.getElementById('filterStatus');

            function fetchFilteredData(url = null) {
                const source = sourceSelect.value;
                const status = statusSelect.value;

                let baseUrl = "<?php echo e(route('admin.testimonials.index')); ?>";

                if (url && typeof url === "object") url = url.href || baseUrl;
                url = url ? String(url) : baseUrl;

                const params = new URLSearchParams({ source, status }).toString();
                url += url.includes("?") ? `&${params}` : `?${params}`;

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                    .then(res => res.text())
                    .then(data => {
                        document.getElementById('testimonialTable').innerHTML = data;
                        attachDeleteEvents();
                        attachStatusToggleEvents();
                    })
                    .catch(err => console.error("Filter fetch error:", err));
            }

            // Pagination clicks
            document.addEventListener('click', function(e) {
                if (e.target.closest('#testimonialTable .pagination a')) {
                    e.preventDefault();
                    fetchFilteredData(e.target.getAttribute('href'));
                }
            });

            // Filter change
            sourceSelect.addEventListener('change', fetchFilteredData);
            statusSelect.addEventListener('change', fetchFilteredData);

            function attachDeleteEvents() {
                document.querySelectorAll('.delete-testimonial').forEach(button => {
                    button.addEventListener('click', function() {
                        let id = this.dataset.id;

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "This cannot be undone!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch("<?php echo e(url('admin/testimonials')); ?>/" + id, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                                        'Accept': 'application/json'
                                    },
                                    credentials: 'same-origin'
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if(data.success) {
                                        document.getElementById('testimonial-' + id).remove();
                                        Swal.fire('Deleted!', data.message, 'success');
                                    } else {
                                        Swal.fire('Error!', data.message || 'Something went wrong!', 'error');
                                    }
                                })
                                .catch(() => Swal.fire('Error!', 'Something went wrong!', 'error'));
                            }
                        });
                    });
                });
            }

            function attachStatusToggleEvents() {
                document.querySelectorAll('.toggle-status').forEach(button => {
                    button.addEventListener('click', function() {
                        let id = this.dataset.id;
                        let currentStatus = this.dataset.status;
                        let newStatus = currentStatus == 1 ? 0 : 1;

                        fetch("<?php echo e(url('admin/testimonials/toggle-status')); ?>/" + id, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ status: newStatus }),
                            credentials: 'same-origin'
                        })
                        .then(res => res.json())
                        .then(data => {
                            if(data.success){
                                button.dataset.status = newStatus;
                                if(newStatus == 1){
                                    button.classList.remove('text-warning');
                                    button.classList.add('text-success');
                                    button.innerHTML = '<i class="bi bi-check-circle-fill fs-5"></i>';
                                } else {
                                    button.classList.remove('text-success');
                                    button.classList.add('text-warning');
                                    button.innerHTML = '<i class="bi bi-slash-circle fs-5"></i>';
                                }
                                Swal.fire('Success!', data.message, 'success');
                            } else {
                                Swal.fire('Error!', data.message || 'Something went wrong!', 'error');
                            }
                        })
                        .catch(() => Swal.fire('Error!', 'Something went wrong!', 'error'));
                    });
                });
            }

            attachDeleteEvents();
            attachStatusToggleEvents();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Testimonials'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/testimonials/view.blade.php ENDPATH**/ ?>