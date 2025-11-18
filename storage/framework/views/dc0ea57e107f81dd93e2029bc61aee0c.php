<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Customer</th>
            <th>Vehicle</th>
            <th>Pickup Location</th>
            <th>Dropoff Location</th>
            <th>Total KM</th>
            <th>Price</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr id="booking-<?php echo e($booking->id); ?>">
                
                
                <td><?php echo e($booking->inv_no); ?></td>

                
                <td><?php echo e($booking->customer->name ?? $booking->customer_name ?? '-'); ?></td>

                
                <td>
                    <?php echo e($booking->vehicle->name ?? $booking->vehicle_model ?? '-'); ?>

                    <?php if(!empty($booking->vehicle_type)): ?>
                        <br><small class="text-muted"><?php echo e(ucfirst($booking->vehicle_type)); ?></small>
                    <?php endif; ?>
                </td>

                
                <td><?php echo e($booking->pickup_location); ?> <br>
                    <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($booking->pickup_datetime)->format('d M Y, h:i A')); ?></small>
                </td>

                
                <td><?php echo e($booking->dropoff_location); ?> <br>
                    <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($booking->dropoff_datetime)->format('d M Y, h:i A')); ?></small>
                </td>

                
                <td>
                    <?php if($booking->mileage == 'unlimited'): ?>
                        Unlimited
                    <?php else: ?>
                        <?php echo e($booking->total_km); ?> km
                    <?php endif; ?>
                </td>

                
                <td><?php echo e($booking->currency ?? 'LKR'); ?> <?php echo e(number_format($booking->total_price, 2)); ?></td>

                
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm status-dropdown-btn
                            <?php switch($booking->status):
                                case ('quotation'): ?> btn-secondary <?php break; ?>
                                <?php case ('invoice'): ?> btn-info <?php break; ?>
                                <?php case ('confirmed'): ?> btn-primary <?php break; ?>
                                <?php case ('completed'): ?> btn-success <?php break; ?>
                                <?php case ('cancelled'): ?> btn-danger <?php break; ?>
                                <?php default: ?> btn-secondary
                            <?php endswitch; ?>
                            dropdown-toggle"
                            type="button"
                            id="statusDropdown<?php echo e($booking->id); ?>"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <?php echo e(ucfirst(str_replace('_',' ', $booking->status))); ?>

                        </button>

                        <ul class="dropdown-menu" aria-labelledby="statusDropdown<?php echo e($booking->id); ?>">
                            <?php $__currentLoopData = ['quotation', 'invoice', 'confirmed', 'completed', 'cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a class="dropdown-item change-status"
                                       href="#"
                                       data-id="<?php echo e($booking->id); ?>"
                                       data-status="<?php echo e($statusOption); ?>">
                                        <?php echo e(ucfirst(str_replace('_',' ', $statusOption))); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </td>

                
                <td class="text-center">
                    <a href="<?php echo e(route('admin.vehicle-bookings.show', $booking->id)); ?>"
                       class="icon-btn text-info"
                       title="View Booking">
                        <i class="bi bi-eye-fill fs-5"></i>
                    </a>

                    <a href="<?php echo e(route('admin.vehicle-bookings.edit', $booking->id)); ?>"
                       class="icon-btn text-primary"
                       title="Edit Booking">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </a>

                    <button type="button"
                        data-id="<?php echo e($booking->id); ?>"
                        class="icon-btn text-danger delete-booking"
                        title="Delete Booking">
                        <i class="bi bi-trash-fill fs-5"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="9" class="text-center">No vehicle bookings found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo e($bookings->links()); ?>

<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/bookings/vehicle/table.blade.php ENDPATH**/ ?>