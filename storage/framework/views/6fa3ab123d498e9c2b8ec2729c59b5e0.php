

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', [
        'title' => 'Add Hotel',
        'subtitle' => 'Hotels',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <style>
        .btn-equal {
            width: 80px;
            text-align: center;
        }
    </style>

    <div class="card-t">

        
        <div class="mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <button type="button" id="toggleCreateForm" class="btn btn-primary">+ Add Hotel</button>
            </div>
        </div>

        
        <div class="card mb-4" id="createHotelCard" style="display: none;">
            <div class="card-body">
                <div id="message"></div>

                <form id="createHotelForm" action="<?php echo e(route('admin.hotels.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="hotel_name" class="form-label">Hotel Name</label>
                            <input type="text" name="hotel_name" id="hotel_name" class="form-control"
                                placeholder="Ex: Hilton Colombo" required>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="star" class="form-label">Star Rating</label>
                            <select name="star" id="star" class="form-select">
                                <option value="">Select Rating</option>
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?php echo e($i); ?>"><?php echo e($i); ?> Star</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Create Hotel</button>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" id="hotelTable">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Hotel Name</th>
                                <th>Star</th>
                                <th>Status</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $hotels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hotel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr id="hotel-<?php echo e($hotel->id); ?>">
                                    <td><?php echo e($hotel->hotel_name); ?></td>
                                    <td><?php echo e($hotel->star ? $hotel->star . ' ★' : '-'); ?></td>
                                    <td>
                                        <?php if($hotel->status): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($hotel->updated_at->format('d M Y, h:i A')); ?></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-info btn-sm btn-equal edit-hotel"
                                                data-id="<?php echo e($hotel->id); ?>" data-name="<?php echo e($hotel->hotel_name); ?>"
                                                data-star="<?php echo e($hotel->star); ?>" data-status="<?php echo e($hotel->status); ?>">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm btn-equal delete-hotel"
                                                data-id="<?php echo e($hotel->id); ?>">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hotels found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    
                    <div class="d-flex justify-content-end mt-3">
                        <?php echo e($hotels->links()); ?>

                    </div>
                </div>
            </div>

            <!-- Edit Hotel Modal -->
            <div class="modal fade" id="editHotelModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="editHotelForm" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Hotel</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div id="editMessage"></div>

                                
                                <div class="mb-3">
                                    <label for="edit_hotel_name" class="form-label">Hotel Name</label>
                                    <input type="text" name="hotel_name" id="edit_hotel_name" class="form-control"
                                        required>
                                </div>

                                
                                <div class="mb-3">
                                    <label for="edit_star" class="form-label">Star Rating</label>
                                    <select name="star" id="edit_star" class="form-select">
                                        <option value="">Select Rating</option>
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <option value="<?php echo e($i); ?>"><?php echo e($i); ?> Star</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                
                                <div class="mb-3">
                                    <label for="edit_status" class="form-label">Status</label>
                                    <select name="status" id="edit_status" class="form-select">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Hotel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("toggleCreateForm");
            const formCard = document.getElementById("createHotelCard");

            toggleBtn.addEventListener("click", function() {
                formCard.style.display = formCard.style.display === "none" ? "block" : "none";
                toggleBtn.textContent = formCard.style.display === "block" ? "Close Form" : "+ Add Hotel";
            });

            // Create Hotel AJAX
            document.getElementById('createHotelForm').addEventListener('submit', function(e) {
                e.preventDefault();
                let form = this;
                let formData = new FormData(form);

                fetch(form.action, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        let messageBox = document.getElementById('message');
                        if (data.success) {
                            messageBox.innerHTML =
                                `<div class="alert alert-success">${data.message}</div>`;
                                form.reset();
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            let errors = data.errors ? Object.values(data.errors).flat().join('<br>') :
                                data.message;
                            messageBox.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                        }
                    })
                    .catch(err => console.error(err));
            });

            // Edit Hotel
            document.querySelector("#hotelTable").addEventListener("click", function(e) {
                const btn = e.target.closest(".edit-hotel");
                if (!btn) return;

                const id = btn.dataset.id;
                document.getElementById("editHotelForm").action = `/admin/hotels/${id}`;
                document.getElementById("edit_hotel_name").value = btn.dataset.name;
                document.getElementById("edit_star").value = btn.dataset.star;
                document.getElementById("edit_status").value = btn.dataset.status;

                new bootstrap.Modal(document.getElementById("editHotelModal")).show();
            });

            // Submit Edit
            document.getElementById("editHotelForm").addEventListener("submit", function(e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);

                fetch(form.action, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        const messageBox = document.getElementById("editMessage");
                        if (data.success) {
                            messageBox.innerHTML =
                                `<div class="alert alert-success">${data.message}</div>`;
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            const errors = data.errors ? Object.values(data.errors).flat().join(
                                '<br>') : data.message;
                            messageBox.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                        }
                    })
                    .catch(err => console.error(err));
            });

            // Delete Hotel
            document.querySelector("#hotelTable").addEventListener("click", function(e) {
                const btn = e.target.closest(".delete-hotel");
                if (!btn) return;

                const id = btn.dataset.id;

                Swal.fire({
                    title: "Are you sure?",
                    text: "This hotel will be permanently deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/hotels/${id}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                                    "Accept": "application/json"
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    // Remove row from table
                                    document.getElementById('hotel-' + id).remove();

                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Hotel has been deleted successfully.",
                                        icon: "success",
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire("Error!", data.message ||
                                        "Failed to delete hotel.", "error");
                                }
                            })
                            .catch(err => {
                                Swal.fire("Error!", "Something went wrong.", "error");
                                console.error(err);
                            });
                    }
                });
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Hotels'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/details/hotel.blade.php ENDPATH**/ ?>