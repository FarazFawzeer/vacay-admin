

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Add Details', 'subtitle' => 'Destination'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <style>
        .btn-equal {
            width: 80px;
            /* or any fixed width you want */
            text-align: center;
        }
    </style>

    <div class="card-t">


        <div class=" mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">

                <button type="button" id="toggleCreateForm" class="btn btn-primary">
                    + Add Destination
                </button>
            </div>
        </div>

        
        <div class="card mb-4" id="createDestinationCard" style="display: none;">
            <div class="card-body">
                <div id="message"></div> 

                <form id="createDestinationForm" action="<?php echo e(route('admin.destinations.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="name" class="form-label">Destination Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="<?php echo e(old('name')); ?>" placeholder="Ex: Kandy" required>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Program Points</label>
                            <div id="programPointsWrapper">
                                <div class="input-group mb-2">
                                    <input type="text" name="program_points[]" class="form-control"
                                        placeholder="Ex: Visit Temple of Tooth">
                                    <button type="button" class="btn btn-outline-secondary remove-point">Remove</button>
                                </div>
                            </div>
                            <button type="button" id="addProgramPoint" class="btn btn-sm btn-success mt-2">+ Add
                                Point</button>
                        </div>
                    </div>

                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Create Destination</button>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" id="destinationTable">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Program Points</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr id="destination-<?php echo e($destination->id); ?>">
                                    <td><?php echo e($destination->name); ?></td>
                                    <td>
                                        <?php if(is_array($destination->program_points)): ?>
                                            <ul class="mb-0">
                                                <?php $__currentLoopData = $destination->program_points; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($point['point'] ?? '-'); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($destination->updated_at->format('d M Y, h:i A')); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm btn-equal edit-destination"
                                            data-id="<?php echo e($destination->id); ?>" data-name="<?php echo e($destination->name); ?>"
                                            data-points='<?php echo json_encode($destination->program_points, 15, 512) ?>'>
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-danger btn-equal btn-sm delete-destination"
                                            data-id="<?php echo e($destination->id); ?>">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No destinations found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="modal fade" id="editDestinationModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form id="editDestinationForm" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Destination</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="editMessage"></div>

                                        <div class="mb-3">
                                            <label class="form-label">Destination Name</label>
                                            <input type="text" name="name" id="editName" class="form-control"
                                                required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Program Points</label>
                                            <div id="editProgramPointsWrapper"></div>
                                            <button type="button" id="editAddPoint" class="btn btn-sm btn-success mt-2">+
                                                Add Point</button>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update Destination</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-3">
                        <?php echo e($destinations->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        // Add/remove program points
        document.getElementById('addProgramPoint').addEventListener('click', function() {
            const wrapper = document.getElementById('programPointsWrapper');
            const newInput = document.createElement('div');
            newInput.classList.add('input-group', 'mb-2');
            newInput.innerHTML = `
            <input type="text" name="program_points[]" class="form-control" placeholder="Ex: New Program Point">
            <button type="button" class="btn btn-outline-secondary remove-point">Remove</button>
        `;
            wrapper.appendChild(newInput);
        });

        // Remove point
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-point')) {
                e.target.closest('.input-group').remove();
            }
        });

        // AJAX Submit
        document.getElementById('createDestinationForm').addEventListener('submit', function(e) {
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
                .then(response => response.json())
                .then(data => {
                    let messageBox = document.getElementById('message');
                    if (data.success) {
                        messageBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        form.reset();
                        document.getElementById('programPointsWrapper').innerHTML = `
                    <div class="input-group mb-2">
                        <input type="text" name="program_points[]" class="form-control" placeholder="Ex: Visit Temple of Tooth">
                        <button type="button" class="btn btn-outline-secondary remove-point">Remove</button>
                    </div>
                `;

                        fetch("<?php echo e(route('admin.destinations.index')); ?>")
                            .then(res => res.text())
                            .then(html => {
                                // Parse the response HTML
                                let parser = new DOMParser();
                                let doc = parser.parseFromString(html, "text/html");
                                let newTable = doc.querySelector("#destinationTable").innerHTML;

                                // Replace old table with new one
                                document.querySelector("#destinationTable").innerHTML = newTable;
                            });

                        setTimeout(() => {
                            messageBox.innerHTML = "";
                        }, 3000);
                    } else {
                        let errors = data.errors ? Object.values(data.errors).flat().join('<br>') : data
                            .message;
                        messageBox.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                    }
                })
                .catch(error => {
                    document.getElementById('message').innerHTML =
                        `<div class="alert alert-danger">Something went wrong. Please try again.</div>`;
                    console.error(error);
                });
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-destination')) {
                let btn = e.target.closest('.delete-destination');
                let id = btn.dataset.id;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the destination.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("<?php echo e(url('admin/destinations')); ?>/" + id, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('destination-' + id).remove();
                                    Swal.fire('Deleted!', data.message, 'success');
                                } else {
                                    Swal.fire('Error!', data.message || 'Something went wrong!',
                                        'error');
                                }
                            })
                            .catch(() => {
                                Swal.fire('Error!', 'Something went wrong!', 'error');
                            });
                    }
                });
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("toggleCreateForm");
            const formCard = document.getElementById("createDestinationCard");

            toggleBtn.addEventListener("click", function() {
                if (formCard.style.display === "none") {
                    formCard.style.display = "block";
                    toggleBtn.textContent = "Close Form";
                } else {
                    formCard.style.display = "none";
                    toggleBtn.textContent = "+ Add Destination";
                }
            });
        });


        document.addEventListener('click', function(e) {
            if (e.target.closest('.edit-destination')) {
                let btn = e.target.closest('.edit-destination');
                let id = btn.dataset.id;
                let name = btn.dataset.name;
                let points = JSON.parse(btn.dataset.points || '[]');

                document.getElementById('editName').value = name;
                const wrapper = document.getElementById('editProgramPointsWrapper');
                wrapper.innerHTML = '';

                points.forEach(p => {
                    let div = document.createElement('div');
                    div.classList.add('input-group', 'mb-2');
                    div.innerHTML = `
                <input type="text" name="program_points[]" class="form-control" value="${p.point ?? ''}">
                <button type="button" class="btn btn-outline-secondary remove-point">Remove</button>
            `;
                    wrapper.appendChild(div);
                });

                // open modal
                let editModal = new bootstrap.Modal(document.getElementById('editDestinationModal'));
                editModal.show();

                // store action URL in form
                document.getElementById('editDestinationForm').action = `/admin/destinations/${id}`;
            }
        });

        // Add new program point in edit form
        document.getElementById('editAddPoint').addEventListener('click', function() {
            const wrapper = document.getElementById('editProgramPointsWrapper');
            const div = document.createElement('div');
            div.classList.add('input-group', 'mb-2');
            div.innerHTML = `
        <input type="text" name="program_points[]" class="form-control" placeholder="New Program Point">
        <button type="button" class="btn btn-outline-secondary remove-point">Remove</button>
    `;
            wrapper.appendChild(div);
        });

        // AJAX submit for edit
        document.getElementById('editDestinationForm').addEventListener('submit', function(e) {
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
                    let messageBox = document.getElementById('editMessage');
                    if (data.success) {
                        // reload table
                        fetch("<?php echo e(route('admin.destinations.index')); ?>")
                            .then(res => res.text())
                            .then(html => {
                                let parser = new DOMParser();
                                let doc = parser.parseFromString(html, "text/html");
                                let newTable = doc.querySelector("#destinationTable").innerHTML;
                                document.querySelector("#destinationTable").innerHTML = newTable;
                            });

                        messageBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        setTimeout(() => {
                            messageBox.innerHTML = "";

                            // Hide modal safely
                            const modalEl = document.getElementById('editDestinationModal');
                            const modalInstance = bootstrap.Modal.getInstance(modalEl);
                            if (modalInstance) modalInstance.hide();

                            // Remove any leftover modal backdrops
                            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        }, 1000);
                    } else {
                        let errors = data.errors ? Object.values(data.errors).flat().join('<br>') : data
                            .message;
                        messageBox.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                    }
                })
                .catch(err => console.error(err));
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Destination'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/details/destination.blade.php ENDPATH**/ ?>