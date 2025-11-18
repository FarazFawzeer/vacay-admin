

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Vehicle Bookings', 'subtitle' => 'View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <style>
        .status-dropdown-btn {
            min-width: 110px;
            text-align: center;
        }

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
    </style>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">Vehicle Booking List</h5>
                <p class="card-subtitle mb-0">All vehicle bookings in your system.</p>
            </div>
            <div>
                <a href="<?php echo e(route('admin.vehicle-inv-bookings.create')); ?>" class="btn btn-primary">
                    Add Booking
                </a>
            </div>
        </div>

        <div class="card-body">

            
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Filters -->
            <div class="row mb-3 justify-content-end">
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label">Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">All</option>
                        <option value="quotation">Quotation</option>
                        <option value="invoice">Invoice</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="searchInvoice" class="form-label">Invoice No</label>
                    <input type="text" id="searchInvoice" class="form-control" placeholder="Search Invoice No">
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive" id="bookingTable">
                <?php echo $__env->make('bookings.vehicle.table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('filterStatus');
            const searchInput = document.getElementById('searchInvoice');

            function fetchFilteredData(url = null) {
                url = url || "<?php echo e(route('admin.vehicle-bookings.index')); ?>";

                let params = new URLSearchParams({
                    status: statusSelect.value,
                    inv_no: searchInput.value
                });

                url += url.includes('?') ? '&' + params.toString() : '?' + params.toString();

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    })
                    .then(res => res.text())
                    .then(data => {
                        document.getElementById('bookingTable').innerHTML = data;
                    });
            }

            statusSelect.addEventListener('change', () => fetchFilteredData());
            searchInput.addEventListener('input', () => fetchFilteredData());

            // Pagination AJAX
            document.addEventListener('click', function(e) {
                const paginationLink = e.target.closest('#bookingTable .pagination a');
                if (paginationLink) {
                    e.preventDefault();
                    fetchFilteredData(paginationLink.href);
                }
            });
        });
    </script>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.change-status').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    const bookingId = this.dataset.id;
                    const newStatus = this.dataset.status;

                    fetch(`/admin/vehicle-bookings/${bookingId}/status`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                status: newStatus
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const btn = document.getElementById(
                                    `statusDropdown${bookingId}`);
                                btn.textContent = newStatus.replace('_', ' ').replace(/\b\w/g,
                                    c => c.toUpperCase());
                                btn.className =
                                    'btn btn-sm status-dropdown-btn dropdown-toggle ' + (
                                        newStatus === 'quotation' ? 'btn-secondary' :
                                        newStatus === 'invoice' ? 'btn-info' :
                                        newStatus === 'confirmed' ? 'btn-primary' :
                                        newStatus === 'completed' ? 'btn-success' :
                                        newStatus === 'cancelled' ? 'btn-danger' :
                                        'btn-secondary'
                                    );
                            } else {
                                alert('Failed to update status.');
                            }
                        });
                });
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-booking');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const bookingId = this.dataset.id;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("<?php echo e(url('admin/vehicle-bookings')); ?>/" + bookingId, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        // Remove the row from table
                                        const row = document.getElementById('booking-' +
                                            bookingId);
                                        row.remove();

                                        Swal.fire(
                                            'Deleted!',
                                            'Booking has been deleted successfully.',
                                            'success'
                                        );
                                    } else {
                                        Swal.fire(
                                            'Error!',
                                            'Failed to delete booking: ' + (data
                                                .error || 'Unknown error'),
                                            'error'
                                        );
                                    }
                                })
                                .catch(err => {
                                    console.error(err);
                                    Swal.fire(
                                        'Error!',
                                        'Error deleting booking. Try again.',
                                        'error'
                                    );
                                });
                        }
                    });
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Vehicle Bookings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/bookings/vehicle/index.blade.php ENDPATH**/ ?>