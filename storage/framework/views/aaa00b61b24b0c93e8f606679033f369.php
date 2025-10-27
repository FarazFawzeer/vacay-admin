

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Blog Posts', 'subtitle' => 'View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
            height: 40px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Blog Post List</h5>
            <p class="card-subtitle">All blog posts in your system.</p>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3 justify-content-end">
                <div class="col-md-3">
                    <label for="filterType" class="form-label">Type</label>
                    <select id="filterType" class="form-select">
                        <option value="">All</option>
                        <option value="Tour">Tour</option>
                        <option value="Airline Tickets">Airline Tickets</option>
                        <option value="Vehicle Rental">Vehicle Rental</option>
                        <option value="Transportation">Transportation</option>
                        <option value="Visa Assistance">Visa Assistance</option>
                        <option value="Sponsored">Sponsored</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="filterStatus" class="form-label">Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">All</option>
                        <option value="1">Published</option>
                        <option value="0">Unpublished</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive" id="blogTable">
                <?php echo $__env->make('blog.table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('filterType');
            const statusSelect = document.getElementById('filterStatus');

            function fetchFilteredData(url = null) {
                const type = typeSelect.value;
                const status = statusSelect.value;

                // Default URL if not provided
                let baseUrl = "<?php echo e(route('admin.blogs.index')); ?>";

                // If pagination link passed, get href string
                if (url && typeof url === "object") {
                    url = url.href || baseUrl;
                }

                // Ensure URL is always a string
                url = url ? String(url) : baseUrl;

                // Build parameters
                const params = new URLSearchParams({
                    type: type,
                    status: status
                }).toString();

                // Append query params properly
                if (url.includes("?")) {
                    url += `&${params}`;
                } else {
                    url += `?${params}`;
                }

                // Fetch and update table
                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    })
                    .then(res => res.text())
                    .then(data => {
                        document.getElementById('blogTable').innerHTML = data;
                        attachDeleteEvents();
                        attachStatusToggleEvents();
                    })
                    .catch(err => console.error("Filter fetch error:", err));
            }


            // Pagination clicks
            document.addEventListener('click', function(e) {
                if (e.target.closest('#blogTable .pagination a')) {
                    e.preventDefault();
                    let url = e.target.getAttribute('href');
                    fetchFilteredData(url);
                }
            });

            // Filter change
            typeSelect.addEventListener('change', fetchFilteredData);
            statusSelect.addEventListener('change', fetchFilteredData);

            // Delete Blog
            function attachDeleteEvents() {
                document.querySelectorAll('.delete-blog').forEach(button => {
                    button.addEventListener('click', function() {
                        let blogId = this.dataset.id;

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "This action cannot be undone!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch("<?php echo e(url('admin/blogs')); ?>/" + blogId, {
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
                                            document.getElementById('blog-' + blogId)
                                                .remove();
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

            // Toggle Status
            function attachStatusToggleEvents() {
                document.querySelectorAll('.toggle-status').forEach(button => {
                    button.addEventListener('click', function() {
                        let blogId = this.dataset.id;
                        let currentStatus = this.dataset.status;
                        let newStatus = currentStatus == 1 ? 0 : 1;

                        fetch("<?php echo e(url('admin/blogs/status')); ?>/" + blogId, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    status: newStatus
                                }),
                                credentials: 'same-origin'
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    button.dataset.status = newStatus;

                                    if (newStatus == 1) {
                                        button.classList.remove('text-warning');
                                        button.classList.add('text-success');
                                        button.innerHTML =
                                            '<i class="bi bi-check-circle-fill fs-5"></i>';
                                    } else {
                                        button.classList.remove('text-success');
                                        button.classList.add('text-warning');
                                        button.innerHTML =
                                            '<i class="bi bi-slash-circle fs-5"></i>';
                                    }

                                    Swal.fire('Success!', data.message, 'success');
                                } else {
                                    Swal.fire('Error!', data.message || 'Something went wrong!',
                                        'error');
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

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Blog Posts'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/blog/view.blade.php ENDPATH**/ ?>