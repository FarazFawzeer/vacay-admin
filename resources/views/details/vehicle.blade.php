@extends('layouts.vertical', ['subtitle' => 'Vehicles'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Add Vehicle',
        'subtitle' => 'Vehicles',
    ])

    <style>
        .btn-equal {
            width: 80px;
            text-align: center;
        }

        .vehicle-img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
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

        {{-- Toggle Form --}}
        <div class="mb-4">
            <div class="card-body d-flex justify-content-end">
                <button type="button" id="toggleCreateForm" class="btn btn-primary">+ Add Vehicle</button>
            </div>
        </div>

        {{-- Create Vehicle Form --}}
        {{-- Create Vehicle Form --}}
        <div class="card mb-4" id="createVehicleCard" style="display: none;">
            <div class="card-body">
                <div id="message"></div>

                <form id="createVehicleForm" action="{{ route('admin.vehicles.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Ex: Toyota Prius"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Make</label>
                            <input type="text" name="make" class="form-control" placeholder="Ex: Toyota" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Model</label>
                            <input type="text" name="model" class="form-control" placeholder="Ex: 2021">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Condition</label>
                            <select name="condition" class="form-select">
                                <option value="">Select Condition</option>
                                <option value="new">New</option>
                                <option value="used">Used</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Transmission</label>
                            <select name="transmission" class="form-select">
                                <option value="">Select Transmission</option>
                                <option value="auto">Automatic</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Seats</label>
                            <input type="number" name="seats" class="form-control" placeholder="Ex: 4">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mileage (km/Unlimited Milage)</label>
                            <input type="text" name="milage" class="form-control" placeholder="Ex: 15">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Air Conditioned</label>
                            <select name="air_conditioned" class="form-select">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Helmet</label>
                            <select name="helmet" class="form-select">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Aid Kit</label>
                            <select name="first_aid_kit" class="form-select">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (USD)</label>
                            <input type="number" step="0.01" name="price" class="form-control"
                                placeholder="Ex: 15000">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vehicle Image</label>
                            <input type="file" name="vehicle_image" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="cycle">Cycle</option>
                                <option value="electricbike">Electric Bike</option>
                                <option value="scooter">Scooter</option>
                                <option value="motorcycle">Motorcycle</option>
                                <option value="tuktuk">Tuk Tuk</option>
                                <option value="car">Car</option>
                                <option value="van">Van</option>
                                <option value="other">Other</option>
                            </select>
                        </div>


                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Create Vehicle</button>
                    </div>
                </form>
            </div>
        </div>


        {{-- Vehicle List --}}
        {{-- Vehicle List --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" id="vehicleTable">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>Condition</th>
                                <th>Transmission</th>
                                <th>Seats</th>
                                <th>Mileage (KM)</th>
                                <th>Air Conditioned</th>
                                <th>Helmet</th>
                                <th>First Aid Kit</th>
                                <th>Price (LKR)</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($vehicles as $vehicle)
                                <tr id="vehicle-{{ $vehicle->id }}">
                                    <td>
                                        @if ($vehicle->vehicle_image)
                                            <img src="{{ asset('storage/' . $vehicle->vehicle_image) }}"
                                                class="vehicle-img" alt="{{ $vehicle->name }}">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $vehicle->name }}</td>
                                    <td>{{ $vehicle->make }}</td>
                                    <td>{{ $vehicle->model }}</td>
                                    <td>{{ ucfirst($vehicle->condition ?? '-') }}</td>
                                    <td>{{ ucfirst($vehicle->transmission ?? '-') }}</td>
                                    <td>{{ $vehicle->seats ?? '-' }}</td>
                                    <td>{{ $vehicle->milage ?? '-' }}</td>
                                    <td>{{ $vehicle->air_conditioned ? 'Yes' : 'No' }}</td>
                                    <td>{{ $vehicle->helmet ? 'Yes' : 'No' }}</td>
                                    <td>{{ $vehicle->first_aid_kit ? 'Yes' : 'No' }}</td>
                                    <td>{{ number_format($vehicle->price, 2) }}</td>
                                    <td>{{ ucfirst($vehicle->type ?? '-') }}</td>
                                    <td>
                                        @if ($vehicle->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $vehicle->updated_at ? $vehicle->updated_at->format('d M Y, h:i A') : '-' }}
                                    </td>
                                    <td class="text-center">

                                        {{-- Edit Vehicle --}}
                                        <button class="icon-btn text-primary editVehicleBtn"
                                            data-id="{{ $vehicle->id }}" data-name="{{ $vehicle->name }}"
                                            data-make="{{ $vehicle->make }}" data-model="{{ $vehicle->model }}"
                                            data-seats="{{ $vehicle->seats }}" data-milage="{{ $vehicle->milage }}"
                                            data-air_conditioned="{{ $vehicle->air_conditioned }}"
                                            data-helmet="{{ $vehicle->helmet }}"
                                            data-first_aid_kit="{{ $vehicle->first_aid_kit }}"
                                            data-condition="{{ $vehicle->condition }}"
                                            data-transmission="{{ $vehicle->transmission }}"
                                            data-price="{{ $vehicle->price }}" data-type="{{ $vehicle->type }}"
                                            data-status="{{ $vehicle->status }}" data-bs-toggle="modal"
                                            data-bs-target="#editVehicleModal" title="Edit Vehicle">
                                            <i class="bi bi-pencil-square fs-5"></i>
                                        </button>

                                        {{-- Toggle Status --}}
                                        <button type="button"
                                            class="icon-btn toggle-status-btn {{ $vehicle->status ? 'text-success' : 'text-warning' }}"
                                            data-id="{{ $vehicle->id }}" data-status="{{ $vehicle->status }}"
                                            title="{{ $vehicle->status ? 'Set as Inactive' : 'Set as Active' }}">
                                            @if ($vehicle->status)
                                                <i class="bi bi-check-circle-fill fs-5"></i>
                                            @else
                                                <i class="bi bi-slash-circle fs-5"></i>
                                            @endif
                                        </button>

                                        {{-- Delete Vehicle --}}
                                        <button type="button" class="icon-btn text-danger delete-vehicle"
                                            data-id="{{ $vehicle->id }}" title="Delete Vehicle">
                                            <i class="bi bi-trash-fill fs-5"></i>
                                        </button>

                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="16" class="text-center text-muted">No vehicles found.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $vehicles->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Vehicle Modal --}}
        {{-- Edit Vehicle Modal --}}
        <div class="modal fade" id="editVehicleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="editVehicleForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Vehicle</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div id="editMessage"></div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" id="edit_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Make</label>
                                    <input type="text" name="make" id="edit_make" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Model</label>
                                    <input type="text" name="model" id="edit_model" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Seats</label>
                                    <input type="number" name="seats" id="edit_seats" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mileage</label>
                                    <input type="text" name="milage" id="edit_milage" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Air Conditioned</label>
                                    <select name="air_conditioned" id="edit_air_conditioned" class="form-select">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Helmet</label>
                                    <select name="helmet" id="edit_helmet" class="form-select">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">First Aid Kit</label>
                                    <select name="first_aid_kit" id="edit_first_aid_kit" class="form-select">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="number" step="0.01" name="price" id="edit_price"
                                        class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Condition</label>
                                    <select name="condition" id="edit_condition" class="form-select">
                                        <option value="new">New</option>
                                        <option value="used">Used</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Transmission</label>
                                    <select name="transmission" id="edit_transmission" class="form-select">
                                        <option value="auto">Automatic</option>
                                        <option value="manual">Manual</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="type" id="edit_type" class="form-select" required>
                                        <option value="">Select Type</option>
                                        <option value="cycle">Cycle</option>
                                        <option value="electricbike">Electric Bike</option>
                                        <option value="scooter">Scooter</option>
                                        <option value="motorcycle">Motorcycle</option>
                                        <option value="tuktuk">Tuk Tuk</option>
                                        <option value="car">Car</option>
                                        <option value="van">Van</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Vehicle Image</label>
                                    <input type="file" name="vehicle_image" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="edit_status" class="form-select">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Vehicle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



    </div>

    {{-- Scripts --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("toggleCreateForm");
            const formCard = document.getElementById("createVehicleCard");

            toggleBtn.addEventListener("click", function() {
                formCard.style.display = formCard.style.display === "none" ? "block" : "none";
                toggleBtn.textContent = formCard.style.display === "block" ? "Close Form" : "+ Add Vehicle";
            });

            // Create Vehicle AJAX
            // Create Vehicle AJAX
            document.getElementById('createVehicleForm').addEventListener('submit', function(e) {
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
                        if (data.success) {
                            // Show SweetAlert success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Vehicle Created!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                form.reset();
                                location.reload(); // Reload to update the vehicle table
                            });
                        } else {
                            const errors = data.errors ? Object.values(data.errors).flat().join(
                                '<br>') : data.message;
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                html: errors
                            });
                        }
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong.'
                        });
                        console.error(err);
                    });
            });


            // Edit Vehicle
            document.querySelectorAll('.editVehicleBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const form = document.getElementById('editVehicleForm');
                    form.action = `/admin/vehicles/${btn.dataset.id}`;

                    // Input fields
                    document.getElementById('edit_name').value = btn.dataset.name || '';
                    document.getElementById('edit_make').value = btn.dataset.make || '';
                    document.getElementById('edit_model').value = btn.dataset.model || '';
                    document.getElementById('edit_milage').value = btn.dataset.milage || '';
                    document.getElementById('edit_price').value = btn.dataset.price || '';

                    // Dropdowns
                    document.getElementById('edit_air_conditioned').value = btn.dataset
                        .air_conditioned || '0';
                    document.getElementById('edit_helmet').value = btn.dataset.helmet || '0';
                    document.getElementById('edit_first_aid_kit').value = btn.dataset
                        .first_aid_kit || '0';
                    document.getElementById('edit_condition').value = (btn.dataset.condition || '')
                        .toLowerCase();
                    document.getElementById('edit_transmission').value = (btn.dataset
                        .transmission || '').toLowerCase();
                    document.getElementById('edit_status').value = btn.dataset.status || '1';

                    // ✅ Populate type select
                    document.getElementById('edit_type').value = btn.dataset.type || '';
                });
            });


            // Submit Edit
            document.getElementById("editVehicleForm").addEventListener("submit", function(e) {
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
                        if (data.success) {
                            // Show SweetAlert success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Vehicle Updated!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); // Reload to update the table
                            });
                        } else {
                            const errors = data.errors ? Object.values(data.errors).flat().join(
                                '<br>') : data.message;
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                html: errors
                            });
                        }
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Something went wrong.'
                        });
                        console.error(err);
                    });
            });


            // Delete Vehicle
            document.querySelector("#vehicleTable").addEventListener("click", function(e) {
                const btn = e.target.closest(".delete-vehicle");
                if (!btn) return;

                const id = btn.dataset.id;

                Swal.fire({
                    title: "Are you sure?",
                    text: "This vehicle will be permanently deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, delete it!"
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch(`/admin/vehicles/${id}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Accept": "application/json"
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('vehicle-' + id).remove();
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Vehicle has been deleted successfully.",
                                        icon: "success",
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire("Error!", data.message ||
                                        "Failed to delete vehicle.", "error");
                                }
                            })
                            .catch(err => {
                                Swal.fire("Error!", "Something went wrong.", "error");
                                console.error(err);
                            });
                    }
                });
            });

            document.querySelector("#vehicleTable").addEventListener("click", function(e) {
                const btn = e.target.closest(".toggle-status-btn");
                if (!btn) return;

                const id = btn.dataset.id;
                const currentStatus = btn.dataset.status;

                Swal.fire({
                    title: 'Are you sure?',
                    text: `This will ${currentStatus == 1 ? 'disable' : 'enable'} the vehicle!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, proceed!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/vehicles/${id}/toggle-status`, {
                                method: 'PATCH',
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Accept": "application/json"
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    // Update dataset
                                    btn.dataset.status = data.new_status;

                                    // Update icon & color
                                    const icon = btn.querySelector("i");
                                    if (data.new_status == 1) {
                                        icon.className = "bi bi-check-circle-fill fs-5";
                                        btn.classList.remove("text-warning");
                                        btn.classList.add("text-success");
                                        btn.title = "Set as Inactive";
                                    } else {
                                        icon.className = "bi bi-slash-circle fs-5";
                                        btn.classList.remove("text-success");
                                        btn.classList.add("text-warning");
                                        btn.title = "Set as Active";
                                    }

                                    // Update status badge
                                    const statusBadge = document.querySelector(
                                        `#vehicle-${id} td:nth-child(14) span`);
                                    if (statusBadge) {
                                        statusBadge.textContent = data.new_status == 1 ?
                                            'Active' : 'Inactive';
                                        statusBadge.className = data.new_status == 1 ?
                                            'badge bg-success' : 'badge bg-danger';
                                    }

                                    // Success alert
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire('Error!', data.message ||
                                        'Failed to update status.', 'error');
                                }
                            })
                            .catch(err => {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                                console.error(err);
                            });
                    }
                });
            });

        });

        document.querySelectorAll('.editVehicleBtn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('editVehicleForm').action = `/admin/vehicles/${btn.dataset.id}`;

                // Input fields
                document.getElementById('edit_name').value = btn.dataset.name || '';
                document.getElementById('edit_make').value = btn.dataset.make || '';
                document.getElementById('edit_model').value = btn.dataset.model || '';
                document.getElementById('edit_milage').value = btn.dataset.milage || '';
                document.getElementById('edit_price').value = btn.dataset.price || '';

                // Dropdowns — normalized
                document.getElementById('edit_air_conditioned').value = btn.dataset.air_conditioned || '0';
                document.getElementById('edit_helmet').value = btn.dataset.helmet || '0';
                document.getElementById('edit_first_aid_kit').value = btn.dataset.first_aid_kit || '0';
                document.getElementById('edit_condition').value = (btn.dataset.condition || '')
                    .toLowerCase();
                document.getElementById('edit_transmission').value = (btn.dataset.transmission || '')
                    .toLowerCase();
                document.getElementById('edit_status').value = btn.dataset.status || '1';
            });
        });
    </script>
@endsection
