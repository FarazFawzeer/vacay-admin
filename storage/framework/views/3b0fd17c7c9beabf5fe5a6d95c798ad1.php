

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Tour Bookings', 'subtitle' => 'View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <style>

            .status-dropdown-btn {
        min-width: 110px;  /* adjust width as needed */
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
                <h5 class="card-title mb-0">Tour Booking List</h5>
                <p class="card-subtitle mb-0">All tour bookings in your system.</p>
            </div>
            <div>
                <a href="<?php echo e(route('admin.tour-quotations.create')); ?>" class="btn btn-primary">
                    </i> Add Booking
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
            <!-- Filters -->
            <div class="row mb-3 justify-content-end">
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label">Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">All</option>
                        <option value="quotation">Quotation</option>
                        <option value="invoiced">Invoiced</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="searchBooking" class="form-label">Booking ID</label>
                    <input type="text" id="searchBooking" class="form-control" placeholder="Search Booking ID">
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive" id="bookingTable">
                <?php echo $__env->make('bookings.tour_table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('filterStatus');
            const searchInput = document.getElementById('searchBooking');

            function fetchFilteredData(url = null) {
                // Ensure url is a string
                url = url || "<?php echo e(route('admin.tour-bookings.index')); ?>";

                let params = new URLSearchParams({
                    status: statusSelect.value,
                    booking_ref: searchInput.value
                });

                // If URL already has query params, append with &
                if (url.indexOf('?') !== -1) {
                    url += '&' + params.toString();
                } else {
                    url += '?' + params.toString();
                }

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

            fetch(`/admin/tour-bookings/${bookingId}/status`, {
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
btn.className = 'btn btn-sm status-dropdown-btn dropdown-toggle ' + (
    newStatus === 'quotation' ? 'btn-secondary' :
    newStatus === 'invoiced' ? 'btn-info' :
    newStatus === 'confirmed' ? 'btn-primary' :
    newStatus === 'completed' ? 'btn-success' :
    newStatus === 'cancelled' ? 'btn-danger' : 'btn-secondary'
);

                    // Optional: small toast/alert
                    // alert('Status updated successfully!');
                } else {
                    alert('Failed to update status.');
                }
            });
        });
    });
});

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Tour Bookings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/bookings/tour_view.blade.php ENDPATH**/ ?>