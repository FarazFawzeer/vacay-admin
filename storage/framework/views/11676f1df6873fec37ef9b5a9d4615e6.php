

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Package Bookings', 'subtitle' => 'View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
            <h5 class="card-title mb-0">Package Booking List</h5>
            <p class="card-subtitle mb-0">All package bookings in your system.</p>
        </div>
    </div>

    <div class="card-body">

        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show"><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Filters -->
<div class="row mb-3 justify-content-end">
    <div class="col-md-3">
        <label for="searchName" class="form-label">Search by Full Name</label>
        <input type="text" id="searchName" class="form-control" placeholder="Enter full name">
    </div>
</div>

        <!-- Table -->
        <div class="table-responsive" id="bookingTable">

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Street</th>
                        <th>City</th>
                        <th>Country</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>WhatsApp</th>
                        <th>Adults</th>
                        <th>Children</th>
                        <th>Infants</th>
                        <th>Package</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Pickup</th>
                        <th>Hotel Type</th>
                        <th>Travelling From</th>
                        <th>Reason</th>
                        <th>Theme</th>
                        <th>Message</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr id="booking-<?php echo e($booking->id); ?>">

                            <td><?php echo e($booking->full_name); ?></td>
                            <td><?php echo e($booking->street); ?></td>
                            <td><?php echo e($booking->city); ?></td>
                            <td><?php echo e($booking->country); ?></td>
                            <td><?php echo e($booking->email); ?></td>
                            <td><?php echo e($booking->phone); ?></td>
                            <td><?php echo e($booking->whatsapp); ?></td>
                            <td><?php echo e($booking->adults); ?></td>
                            <td><?php echo e($booking->children); ?></td>
                            <td><?php echo e($booking->infants); ?></td>
                            <td><?php echo e($booking->package->heading ?? 'N/A'); ?></td>

                            <td><?php echo e($booking->start_date?->format('d M Y')); ?></td>
                            <td><?php echo e($booking->end_date?->format('d M Y')); ?></td>

                            <td><?php echo e($booking->pickup); ?></td>
                            <td><?php echo e($booking->hotel_type); ?></td>
                            <td><?php echo e($booking->travelling_from); ?></td>
                            <td><?php echo e($booking->travel_reason); ?></td>

                            <td>
                                <?php if(is_array($booking->theme)): ?>
                                    <?php echo e(implode(', ', $booking->theme)); ?>

                                <?php else: ?>
                                    <?php echo e($booking->theme); ?>

                                <?php endif; ?>
                            </td>

                            <td><?php echo e($booking->message); ?></td>

                            
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm status-dropdown-btn dropdown-toggle
                                        <?php switch($booking->status):
                                            case ('pending'): ?> btn-warning <?php break; ?>
                                            <?php case ('confirmed'): ?> btn-primary <?php break; ?>
                                            <?php case ('completed'): ?> btn-success <?php break; ?>
                                            <?php case ('cancelled'): ?> btn-danger <?php break; ?>
                                            <?php default: ?> btn-secondary
                                        <?php endswitch; ?>"
                                        type="button"
                                        id="statusDropdown<?php echo e($booking->id); ?>"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <?php echo e(ucfirst($booking->status)); ?>

                                    </button>

                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown<?php echo e($booking->id); ?>">
                                        <?php $__currentLoopData = ['pending','confirmed','completed','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <a class="dropdown-item change-status"
                                                   href="#"
                                                   data-id="<?php echo e($booking->id); ?>"
                                                   data-status="<?php echo e($statusOption); ?>">
                                                    <?php echo e(ucfirst($statusOption)); ?>

                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="20" class="text-center">No bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php echo e($bookings->links()); ?>


        </div>

    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.change-status').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            const bookingId = this.dataset.id;
            const newStatus = this.dataset.status;

            fetch(`/admin/enquiry/${bookingId}/status`, {
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

                    btn.className = `btn btn-sm status-dropdown-btn dropdown-toggle ${
                        newStatus === 'pending' ? 'btn-warning' :
                        newStatus === 'confirmed' ? 'btn-primary' :
                        newStatus === 'completed' ? 'btn-success' :
                        newStatus === 'cancelled' ? 'btn-danger' :
                        'btn-secondary'
                    }`;

                } else {
                    alert('Failed to update status.');
                }
            });
        });
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Package Bookings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/enquiry/tours.blade.php ENDPATH**/ ?>