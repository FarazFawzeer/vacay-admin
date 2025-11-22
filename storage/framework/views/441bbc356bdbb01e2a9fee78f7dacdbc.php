

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Admin List</h5>
            <p class="card-subtitle">All admins in your system with details.</p>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <div class="table-responsive">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Updated At</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr id="user-<?php echo e($user->id); ?>">
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="<?php echo e($user->image_path ? asset($user->image_path) : asset('/images/users/avatar-6.jpg')); ?>"
                                                alt="<?php echo e($user->name); ?>" class="avatar-sm rounded-circle">
                                            <div>
                                                <h6 class="mb-0"><?php echo e($user->name); ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo e($user->email); ?></td>
                                    <td><?php echo e(ucfirst($user->type)); ?></td>
                                    <td><?php echo e($user->updated_at->format('d M Y, h:i A')); ?></td>
                                    <td>
                                        <?php if(auth()->user()->type === 'Super Admin'): ?>
                                            <button type="button"
                                                class="btn btn-sm p-0 text-primary border-0 bg-transparent edit-user"
                                                data-id="<?php echo e($user->id); ?>">
                                                <i class="fas fa-edit fa-lg"></i>
                                            </button>
                                        <?php endif; ?>

                                        <button type="button"
                                            class="btn btn-sm p-0 text-danger border-0 bg-transparent delete-user"
                                            data-id="<?php echo e($user->id); ?>">
                                            <i class="fas fa-trash-alt fa-lg"></i>
                                        </button>
                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <!-- Edit User Modal -->
                        <div class="modal fade" id="editUserModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div id="editMessage"></div>

                                        <form id="editUserForm" enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>

                                            <input type="hidden" id="edit_user_id">

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label>Name</label>
                                                    <input type="text" id="edit_name" name="name"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label>Email</label>
                                                    <input type="email" id="edit_email" name="email"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label>User Type</label>
                                                    <select id="edit_type" name="type" class="form-select">
                                                        <option value="Super Admin">Super Admin</option>
                                                        <option value="Admin">Admin</option>
                                                        <option value="Tour Assistant">Tour Assistant</option>
                                                        <option value="Staff">Staff</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label>Change Profile Image</label>
                                                    <input type="file" id="edit_image_path" name="image_path"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label>New Password (optional)</label>
                                                <input type="password" id="edit_password" name="password"
                                                    class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label>Confirm Password</label>
                                                <input type="password" id="edit_password_confirmation"
                                                    name="password_confirmation" class="form-control">
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-primary" type="submit">Update User</button>
                                            </div>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </table>

                    
                    <div class="d-flex justify-content-end mt-3">
                        <?php echo e($users->links()); ?>

                    </div>
                </div>


            </div>
        </div>
    </div>


    <script>
        document.querySelectorAll('.edit-user').forEach(button => {
            button.addEventListener('click', function() {
                let userId = this.dataset.id;

                fetch("<?php echo e(url('admin/users')); ?>/" + userId + "/edit")
                    .then(res => res.json())
                    .then(user => {
                        document.getElementById('edit_user_id').value = user.id;
                        document.getElementById('edit_name').value = user.name;
                        document.getElementById('edit_email').value = user.email;
                        document.getElementById('edit_type').value = user.type;

                        new bootstrap.Modal(document.getElementById('editUserModal')).show();
                    });
            });
        });

        // Save Updates
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let userId = document.getElementById('edit_user_id').value;
            let formData = new FormData(this);

            fetch("<?php echo e(url('admin/users')); ?>/" + userId, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                        "X-HTTP-Method-Override": "PUT"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    let msg = document.getElementById('editMessage');

                    if (data.success) {
                        msg.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        let errors = Object.values(data.errors).flat().join("<br>");
                        msg.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                    }
                });
        });
        document.querySelectorAll('.delete-user').forEach(button => {
            button.addEventListener('click', function() {
                let userId = this.dataset.id;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("<?php echo e(url('admin/users')); ?>/" + userId, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Remove the deleted row
                                    document.getElementById('user-' + userId).remove();

                                    Swal.fire(
                                        'Deleted!',
                                        data.message,
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        data.message || 'Something went wrong!',
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong!',
                                    'error'
                                );
                            });
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Admin View'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/admin/users.blade.php ENDPATH**/ ?>