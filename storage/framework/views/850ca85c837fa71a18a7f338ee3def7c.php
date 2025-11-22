

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Add Visa', 'subtitle' => 'Visa'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
        }
    </style>

    <div class="card-t">
        
        <div class="mb-4">
            <div class="card-body d-flex justify-content-end align-items-center">
                <button type="button" id="toggleCreateForm" class="btn btn-primary">+ Add Visa</button>
            </div>
        </div>

        
        <div class="card mb-4" id="createVisaCard" style="display: none;">
            <div class="card-body">
                <div id="message"></div>

                <form id="createVisaForm" action="<?php echo e(route('admin.visa.store')); ?>" method="POST"
                    enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control" placeholder="Enter country name"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Visa Type</label>
                            <select name="visa_type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="e-Visa (Online Applying)">e-Visa (Online Applying)</option>
                                <option value="On Arrival (No Visa Needed)">On Arrival (No Visa Needed)</option>
                                <option value="Apply Visa (To Embassy)">Apply Visa (To Embassy)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Visa Details</label>
                        <textarea name="visa_details" class="form-control" rows="3" placeholder="Enter visa details..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Documents (Image)</label>
                        <input type="file" name="documents" class="form-control" accept="image/*">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Create Visa</button>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" id="visaTable">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Country</th>
                                <th>Visa Type</th>
                                <th>Visa Details</th>
                                <th>Documents</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $visas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr id="visa-<?php echo e($visa->id); ?>">
                                    <td><?php echo e($visa->country); ?></td>
                                    <td><?php echo e($visa->visa_type); ?></td>
                                    <td><?php echo e(Str::limit($visa->visa_details, 40) ?? '-'); ?></td>
                                    <td>
                                        <?php if($visa->documents): ?>
                                            <img src="<?php echo e(asset('storage/' . $visa->documents)); ?>" width="50"
                                                height="50" class="rounded">
                                        <?php else: ?>
                                            <span class="text-muted">No image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($visa->updated_at->format('d M Y, h:i A')); ?></td>
                                    <td>
                                        <button type="button" class="icon-btn text-primary edit-visa"
                                            data-id="<?php echo e($visa->id); ?>" data-country="<?php echo e($visa->country); ?>"
                                            data-type="<?php echo e($visa->visa_type); ?>" data-details="<?php echo e($visa->visa_details); ?>"
                                            data-documents="<?php echo e($visa->documents); ?>">
                                            <i class="bi bi-pencil-square fs-5"></i>
                                        </button>

                                        <button type="button" class="icon-btn text-danger delete-visa"
                                            data-id="<?php echo e($visa->id); ?>">
                                            <i class="bi bi-trash-fill fs-5"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No visas found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end mt-3">
                        <?php echo e($visas->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="editVisaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editVisaForm" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Visa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="editMessage"></div>

                        <div class="mb-3">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" id="edit_country" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Visa Type</label>
                            <select name="visa_type" id="edit_type" class="form-select" required>
                                <option value="e-Visa (Online Applying)">e-Visa (Online Applying)</option>
                                <option value="On Arrival (No Visa Needed)">On Arrival (No Visa Needed)</option>
                                <option value="Apply Visa (To Embassy)">Apply Visa (To Embassy)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Visa Details</label>
                            <textarea name="visa_details" id="edit_details" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Documents</label>
                            <div id="existingDoc" class="mb-2"></div>
                            <input type="file" name="documents" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Visa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("toggleCreateForm");
            const formCard = document.getElementById("createVisaCard");

            // 🔹 Toggle Create Form
            toggleBtn.addEventListener("click", function() {
                formCard.style.display = formCard.style.display === "none" ? "block" : "none";
                toggleBtn.textContent = formCard.style.display === "block" ? "Close Form" : "+ Add Visa";
            });

            // 🔹 Create Visa
            document.getElementById("createVisaForm").addEventListener("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                fetch(this.action, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: "Visa Created!",
                                text: data.message,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            this.reset();
                            setTimeout(() => location.reload(), 1200);
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: data.message || "Something went wrong!",
                                icon: "error"
                            });
                        }
                    });
            });

            // 🔹 Edit Visa - Open Modal
            document.querySelector("#visaTable").addEventListener("click", e => {
                const btn = e.target.closest(".edit-visa");
                if (!btn) return;

                const id = btn.dataset.id;
                document.getElementById("editVisaForm").action = `/admin/visa/${id}`;
                document.getElementById("edit_country").value = btn.dataset.country;
                document.getElementById("edit_type").value = btn.dataset.type;
                document.getElementById("edit_details").value = btn.dataset.details || "";

                const docContainer = document.getElementById("existingDoc");
                docContainer.innerHTML = btn.dataset.documents ?
                    `<img src="/storage/${btn.dataset.documents}" width="80" height="80" class="rounded">` :
                    `<span class="text-muted">No existing image</span>`;

                new bootstrap.Modal(document.getElementById("editVisaModal")).show();
            });

            // 🔹 Update Visa
            document.getElementById("editVisaForm").addEventListener("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                fetch(this.action, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: "Updated!",
                                text: data.message,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(() => location.reload(), 1200);
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: data.message || "Update failed!",
                                icon: "error"
                            });
                        }
                    });
            });

            // 🔹 Delete Visa
            document.querySelector("#visaTable").addEventListener("click", e => {
                const btn = e.target.closest(".delete-visa");
                if (!btn) return;

                const id = btn.dataset.id;

                Swal.fire({
                    title: "Are you sure?",
                    text: "This record will be permanently deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, delete it!"
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch(`/admin/visa/${id}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                                    "Accept": "application/json"
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('visa-' + id).remove();
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: data.message,
                                        icon: "success",
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error!",
                                        text: data.message || "Delete failed!",
                                        icon: "error"
                                    });
                                }
                            });
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Visa'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/details/visa.blade.php ENDPATH**/ ?>