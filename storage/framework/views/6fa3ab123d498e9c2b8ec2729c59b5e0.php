

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

        .icon-btn {
            background: none;
            border: none;
            padding: 4px;
            margin: 0 3px;
            cursor: pointer;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .icon-btn:hover {
            transform: scale(1.2);
            opacity: 0.85;
            text-decoration: none;
        }
    </style>

    <div class="card-t">

        
        <div class="mb-4">
            <div class="card-body d-flex justify-content-end align-items-center">
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
                        <div class="col-md-6 mb-3">
                            <label for="room_type" class="form-label">Room Type</label>
                            <input type="text" name="room_type" id="room_type" class="form-control"
                                placeholder="Ex: Deluxe, Suite">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="meal_plan" class="form-label">Meal Plan</label>
                            <input type="text" name="meal_plan" id="meal_plan" class="form-control"
                                placeholder="Ex: Bed & Breakfast">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control"
                                placeholder="Short description about the hotel"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="facilities" class="form-label">Facilities</label>
                            <textarea name="facilities" id="facilities" rows="2" class="form-control" placeholder="List of facilities"></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="entertainment" class="form-label">Entertainment</label>
                            <textarea name="entertainment" id="entertainment" rows="2" class="form-control"
                                placeholder="List of entertainment options"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="pictures" class="form-label">Hotel Pictures</label>
                            <input type="file" name="pictures[]" id="pictures" class="form-control" multiple
                                accept="image/*">
                            <small class="text-muted">You can upload multiple images.</small>
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
                                <th>Room Type</th>
                                <th>Meal Plan</th>
                                <th>Facilities</th>
                                <th>Entertainment</th>
                                <th>Pictures</th>
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
                                    <td><?php echo e($hotel->room_type ?? '-'); ?></td>
                                    <td><?php echo e($hotel->meal_plan ?? '-'); ?></td>
                                    <td><?php echo e(Str::limit($hotel->facilities, 30) ?? '-'); ?></td>
                                    <td><?php echo e(Str::limit($hotel->entertainment, 30) ?? '-'); ?></td>
                                    <td>
                                        <?php if($hotel->pictures): ?>
                                            <?php $__currentLoopData = $hotel->pictures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <img src="<?php echo e(asset('storage/' . $pic)); ?>" width="40" height="40"
                                                    class="rounded me-1">
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <span class="text-muted">No images</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($hotel->status): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($hotel->updated_at->format('d M Y, h:i A')); ?></td>
                                    <td>

                                        
                                        <button type="button" class="icon-btn text-primary edit-hotel"
                                            data-id="<?php echo e($hotel->id); ?>" data-name="<?php echo e($hotel->hotel_name); ?>"
                                            data-star="<?php echo e($hotel->star); ?>" data-status="<?php echo e($hotel->status); ?>"
                                            data-room_type="<?php echo e($hotel->room_type); ?>"
                                            data-meal_plan="<?php echo e($hotel->meal_plan); ?>"
                                            data-description="<?php echo e($hotel->description); ?>"
                                            data-facilities="<?php echo e($hotel->facilities); ?>"
                                            data-entertainment="<?php echo e($hotel->entertainment); ?>"  data-pictures='<?php echo json_encode($hotel->pictures, 15, 512) ?>'
                                             title="Edit Hotel">
                                            <i class="bi bi-pencil-square fs-5"></i>
                                        </button>


                                        
                                        <button type="button" class="icon-btn text-danger delete-hotel"
                                            data-id="<?php echo e($hotel->id); ?>" title="Delete Hotel">
                                            <i class="bi bi-trash-fill fs-5"></i>
                                        </button>

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
                                    <label for="edit_room_type" class="form-label">Room Type</label>
                                    <input type="text" name="room_type" id="edit_room_type" class="form-control">
                                </div>

                                
                                <div class="mb-3">
                                    <label for="edit_meal_plan" class="form-label">Meal Plan</label>
                                    <input type="text" name="meal_plan" id="edit_meal_plan" class="form-control">
                                </div>

                                
                                <div class="mb-3">
                                    <label for="edit_description" class="form-label">Description</label>
                                    <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                                </div>

                                
                                <div class="mb-3">
                                    <label for="edit_facilities" class="form-label">Facilities</label>
                                    <textarea name="facilities" id="edit_facilities" class="form-control" rows="2"></textarea>
                                </div>

                                
                                <div class="mb-3">
                                    <label for="edit_entertainment" class="form-label">Entertainment</label>
                                    <textarea name="entertainment" id="edit_entertainment" class="form-control" rows="2"></textarea>
                                </div>

                                
                                <div class="mb-3">
                                    <label for="edit_pictures" class="form-label">Hotel Pictures</label>

                                    <!-- Existing Images Preview -->
                                    <div id="existingPictures" class="d-flex flex-wrap mb-2 gap-2">
                                        <!-- existing images will be loaded here via JS -->
                                    </div>

                                    <input type="file" name="pictures[]" id="edit_pictures" class="form-control"
                                        multiple accept="image/*">

                                    <small class="text-muted d-block mt-1">
                                        Uploading new images will replace all existing ones.
                                    </small>
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
                document.getElementById("edit_room_type").value = btn.dataset.room_type || '';
                document.getElementById("edit_meal_plan").value = btn.dataset.meal_plan || '';
                document.getElementById("edit_description").value = btn.dataset.description || '';
                document.getElementById("edit_facilities").value = btn.dataset.facilities || '';
                document.getElementById("edit_entertainment").value = btn.dataset.entertainment || '';
                document.getElementById("edit_status").value = btn.dataset.status;

                const pictureContainer = document.getElementById("existingPictures");
                pictureContainer.innerHTML = "";

                let pictures = btn.dataset.pictures ? JSON.parse(btn.dataset.pictures) : [];

                if (pictures.length > 0) {
                    pictures.forEach(pic => {
                        pictureContainer.innerHTML += `
                    <div class="position-relative d-inline-block m-1">
                        <img src="/storage/${pic}" class="rounded" width="80" height="80">
                    </div>
                `;
                    });
                } else {
                    pictureContainer.innerHTML = `<span class="text-muted">No existing images</span>`;
                }

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