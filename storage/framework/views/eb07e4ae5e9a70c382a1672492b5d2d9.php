

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', [
        'title' => 'Visa Bookings',
        'subtitle' => 'View',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">Visa Booking List</h5>
                <p class="card-subtitle mb-0">All visa bookings in your system.</p>
            </div>

            <div>
                <a href="<?php echo e(route('admin.visa-bookings.create')); ?>" class="btn btn-primary">
                    Add Booking
                </a>
            </div>
        </div>

        <div class="card-body">

            
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo e(session('success')); ?>

                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo e(session('error')); ?>

                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Filters -->
            <div class="row mb-3 justify-content-end">
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label">Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="searchInvoice" class="form-label">Invoice No</label>
                    <input type="text" id="searchInvoice" class="form-control" placeholder="Search Invoice No">
                </div>
            </div>

            <!-- Table -->
            <div id="bookingTable" class="table-responsive">
                <?php echo $__env->make('bookings.visa.table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const statusSelect = document.getElementById('filterStatus');
            const searchInput = document.getElementById('searchInvoice');
            const tableContainer = document.getElementById('bookingTable');

            function fetchFilteredData(url = null) {
                url = url ? url.toString() : "<?php echo e(route('admin.visa-bookings.index')); ?>";

                const params = new URLSearchParams({
                    status: statusSelect.value,
                    inv_no: searchInput.value
                });

                url += (url.includes('?') ? '&' : '?') + params.toString();

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.text())
                    .then(data => tableContainer.innerHTML = data)
                    .catch(err => console.error('Error fetching data:', err));
            }

            statusSelect.addEventListener('change', () => fetchFilteredData());
            searchInput.addEventListener('input', () => fetchFilteredData());

            // AJAX Pagination
            tableContainer.addEventListener('click', function(e) {
                const link = e.target.closest('.pagination a');
                if (link) {
                    e.preventDefault();
                    fetchFilteredData(link.href);
                }
            });
        });
    </script>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableContainer = document.getElementById('bookingTable');

            tableContainer.addEventListener('click', function(e) {
                const target = e.target.closest('.change-status');
                if (!target) return;

                e.preventDefault();

                const bookingId = target.dataset.id;
                const newStatus = target.dataset.status;

                fetch(`/admin/visa-bookings/${bookingId}/status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const btn = document.getElementById(`statusDropdown${bookingId}`);
                        btn.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                    } else {
                        alert(data.message || 'Failed to update status');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error updating status.');
                });
            });
        });
    </script>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-booking').forEach(btn => {
                btn.addEventListener('click', function() {
                    const bookingId = this.dataset.id;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This booking will be deleted permanently!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/admin/visa-bookings/${bookingId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    const row = document.getElementById(`booking-${bookingId}`);
                                    if (row) row.remove();

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: data.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire('Error', data.message || 'Failed to delete booking.', 'error');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire('Error', 'Something went wrong.', 'error');
                            });
                        }
                    });
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Visa Bookings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/bookings/visa/index.blade.php ENDPATH**/ ?>