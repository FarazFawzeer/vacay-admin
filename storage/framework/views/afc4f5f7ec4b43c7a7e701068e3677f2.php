

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', [
    'title' => 'Airline Bookings',
    'subtitle' => 'View',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0">Airline Booking List</h5>
            <p class="card-subtitle mb-0">All airline ticket enquiries submitted by customers.</p>
        </div>
    </div>

    <div class="card-body">

        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        
        <div class="row mb-3 justify-content-end">
            <div class="col-md-3">
                <label class="form-label">Search by Name</label>
                <input type="text" id="searchName" class="form-control" placeholder="Enter full name">
            </div>
        </div>

        
        <div class="table-responsive" id="airTicketTable">
            <?php echo $__env->make('enquiry.air-ticket-table', ['bookings' => $bookings], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search
    const searchInput = document.getElementById('searchName');
    searchInput.addEventListener('keyup', function() {
        const search = this.value;
        fetch(`<?php echo e(route('admin.enquiry.airTicket')); ?>?name=${search}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('airTicketTable').innerHTML = html;
        });
    });

    // Status Update
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('changeStatus')) {
            const id = e.target.dataset.id;
            const status = e.target.value;

            fetch("<?php echo e(route('admin.enquiry.airTicket.updateStatus')); ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                },
                body: JSON.stringify({ id, status })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Status updated successfully.',
                        timer: 1300,
                        showConfirmButton: false
                    });
                }
            });
        }
    });

});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Airline Bookings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/enquiry/air-ticket.blade.php ENDPATH**/ ?>