@extends('layouts.vertical', ['subtitle' => 'Vehicles'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Add Vehicle',
        'subtitle' => 'Vehicles',
    ])

    <style>
        /* Scrollable table wrapper */
        #vehicleTableWrapper {
            max-height: calc(100vh - 250px);
            /* Adjust for header/filter/pagination */
            overflow-y: auto;
        }

        /* Make table header sticky */
        #vehicleTable thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            /* Header background */
            z-index: 10;
        }
    </style>

    <style>
        #existingSubImages img {
            border-radius: 6px;
            transition: transform 0.2s;
        }

        #existingSubImages img:hover {
            transform: scale(1.05);
        }

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

                        {{-- Type --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" id="vehicle_type" class="form-select" required>
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

                        {{-- Manufacturer --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Manufacturer</label>
                            <input type="text" name="make" class="form-control" placeholder="Enter Manufacturer"
                                required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Vehicle Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Vehicle Name"
                                required>
                        </div>

                        {{-- Model --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Model</label>
                            <input type="text" name="model" class="form-control" placeholder="Enter Vehicle Model"
                                required>
                        </div>


                        {{-- Price --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Price </label>
                            <input type="number" step="0.01" name="price" class="form-control"
                                placeholder="Ex: 50000">
                        </div>
                        {{-- Dynamic Fields --}}
                        <div class="col-md-6 mb-3 dynamic-field"
                            data-types="cycle,electricbike,scooter,motorcycle,tuktuk,car,van">
                            <label class="form-label">Condition</label>
                            <select name="condition" class="form-select">
                                <option value="">Select Condition</option>
                                <option value="new">New</option>
                                <option value="used">Used</option>
                            </select>
                        </div>


                        <div class="col-md-6 mb-3 dynamic-field" data-types="car,van,motorcycle,scooter,tuktuk">
                            <label class="form-label">Transmission</label>
                            <select name="transmission" class="form-select">
                                <option value="">Select Transmission</option>
                                <option value="auto">Automatic</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>



                        <div class="col-md-6 mb-3 dynamic-field" data-types="car,van,motorcycle,scooter,tuktuk">
                            <label class="form-label">Mileage (km)</label>
                            <input type="text" name="milage" class="form-control" placeholder="Ex: 15">
                        </div>

                        <div class="col-md-6 mb-3 dynamic-field" data-types="car,van">
                            <label class="form-label">Fuel Type</label>
                            <select name="fuel_type" class="form-select">
                                <option value="">Select Fuel Type</option>
                                <option value="petrol">Petrol</option>
                                <option value="diesel">Diesel</option>
                                <option value="electric">Electric</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                        </div>


                        <div class="col-md-6 mb-3 dynamic-field" data-types="car,van,motorcycle,scooter,tuktuk">
                            <label class="form-label">Seats</label>
                            <input type="number" name="seats" class="form-control" placeholder="Ex: 4">
                        </div>

                        <div class="col-md-6 mb-3 dynamic-field" data-types="car,van">
                            <label class="form-label">Air Conditioned</label>
                            <select name="air_conditioned" class="form-select">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3 dynamic-field" data-types="motorcycle,scooter">
                            <label class="form-label">Helmet</label>
                            <select name="helmet" class="form-select">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3 dynamic-field" data-types="car,van,tuktuk">
                            <label class="form-label">First Aid Kit</label>
                            <select name="first_aid_kit" class="form-select">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>


                        <div class="col-md-6 mb-3 dynamic-field" data-types="tuktuk,car,van" style="display:none;">
                            <label class="form-label">Luggage Space</label>
                            <input type="text" name="luggage_space" class="form-control" placeholder="Ex: 200L">
                        </div>

                        {{-- Vehicle Image --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vehicle Image</label>
                            <input type="file" name="vehicle_image" class="form-control">
                        </div>

                        {{-- Sub Images --}}
                        <div class="col-md-6 mb-3 sub-image-field dynamic-field" data-types="car,van"
                            style="display: none;">
                            <label class="form-label">Sub Images (Multiple, Max 4)</label>
                            <input type="file" name="sub_image[]" class="form-control" multiple>
                            <small class="text-muted">You can upload up to 4 images.</small>
                        </div>

                        {{-- Agent --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Agent</label>
                            <select name="agent_id" class="form-select" required>
                                <option value="">Select Agent</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        {{-- Insurance Type --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Insurance Type</label>
                            <input type="text" name="insurance_type" class="form-control"
                                placeholder="Enter Insurance Type">
                        </div>



                        {{-- Status --}}
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
            <div class="card-header">
                <h5 class="card-title">Vehicle List</h5>
                <p class="card-subtitle">All vehicles in your system with details.</p>
            </div>
            <div class="card-body">
                <div class="row mb-3 justify-content-end">
                    <div class="col-md-3">
                        <input type="text" id="vehicleSearch" class="form-control"
                            placeholder="Search by Name, Make, or Model">
                    </div>
                    <div class="col-md-3">
                        <select id="vehicleStatusFilter" class="form-select">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive" id="vehicleTableWrapper">
                    <table class="table table-hover table-centered" id="vehicleTable">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Manufacturer</th>
                                <th>Model</th>
                                <th>Condition</th>

                                <th>Price </th>
                                <th>Type</th>
                                <th>Status</th>

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

                                    <td>{{ number_format($vehicle->price, 2) }}</td>
                                    <td>{{ ucfirst($vehicle->type ?? '-') }}</td>
                                    <td>
                                        @if ($vehicle->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <td class="text-center">

                                        <a href="{{ route('admin.vehicles.show', $vehicle->id) }}"
                                            class="icon-btn text-info" title="View Vehicle Details">
                                            <i class="bi bi-eye fs-5"></i>
                                        </a>

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
                                            data-status="{{ $vehicle->status }}"
                                            data-fuel_type="{{ $vehicle->fuel_type }}"
                                            data-luggage_space="{{ $vehicle->luggage_space }}"
                                            data-insurance_type="{{ $vehicle->insurance_type }}"
                                            data-agent_id="{{ $vehicle->agent_id }}"
                                            data-vehicle_image="{{ $vehicle->vehicle_image }}"
                                            data-subimages='@json($vehicle->sub_image)' data-bs-toggle="modal"
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

                                {{-- Type --}}
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

                                {{-- Manufacturer --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Manufacturer</label>
                                    <input type="text" name="make" id="edit_make" class="form-control" required>
                                </div>

                                {{-- Vehicle Name --}}
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Vehicle Name</label>
                                    <input type="text" name="name" id="edit_name" class="form-control" required>
                                </div>

                                {{-- Model --}}
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Model</label>
                                    <input type="text" name="model" id="edit_model" class="form-control">
                                </div>

                                {{-- Price --}}
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="number" step="0.01" name="price" id="edit_price"
                                        class="form-control">
                                </div>

                                {{-- Dynamic Fields --}}
                                <div class="col-md-6 mb-3 dynamic-field"
                                    data-types="cycle,electricbike,scooter,motorcycle,tuktuk,car,van">
                                    <label class="form-label">Condition</label>
                                    <select name="condition" id="edit_condition" class="form-select">
                                        <option value="">Select Condition</option>
                                        <option value="new">New</option>
                                        <option value="used">Used</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3 dynamic-field" data-types="car,van,motorcycle,scooter,tuktuk">
                                    <label class="form-label">Transmission</label>
                                    <select name="transmission" id="edit_transmission" class="form-select">
                                        <option value="">Select Transmission</option>
                                        <option value="auto">Automatic</option>
                                        <option value="manual">Manual</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3 dynamic-field" data-types="car,van,motorcycle,scooter,tuktuk">
                                    <label class="form-label">Mileage (km)</label>
                                    <input type="text" name="milage" id="edit_milage" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3 dynamic-field" data-types="car,van">
                                    <label class="form-label">Fuel Type</label>
                                    <select name="fuel_type" id="edit_fuel_type" class="form-select">
                                        <option value="">Select Fuel Type</option>
                                        <option value="petrol">Petrol</option>
                                        <option value="diesel">Diesel</option>
                                        <option value="electric">Electric</option>
                                        <option value="hybrid">Hybrid</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3 dynamic-field" data-types="car,van,motorcycle,scooter,tuktuk">
                                    <label class="form-label">Seats</label>
                                    <input type="number" name="seats" id="edit_seats" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3 dynamic-field" data-types="car,van">
                                    <label class="form-label">Air Conditioned</label>
                                    <select name="air_conditioned" id="edit_air_conditioned" class="form-select">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3 dynamic-field" data-types="motorcycle,scooter">
                                    <label class="form-label">Helmet</label>
                                    <select name="helmet" id="edit_helmet" class="form-select">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3 dynamic-field" data-types="car,van,tuktuk">
                                    <label class="form-label">First Aid Kit</label>
                                    <select name="first_aid_kit" id="edit_first_aid_kit" class="form-select">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3 dynamic-field" data-types="tuktuk,car,van">
                                    <label class="form-label">Luggage Space</label>
                                    <input type="text" name="luggage_space" id="edit_luggage_space"
                                        class="form-control" placeholder="Ex: 200L">
                                </div>

                                {{-- Vehicle Image --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Vehicle Image</label>
                                    <input type="file" name="vehicle_image" class="form-control">
                                    <div class="mt-2">
                                        <img id="existingVehicleImage" src="" alt="Vehicle Image"
                                            style="max-width: 150px; max-height: 150px; display: none;">
                                    </div>
                                </div>
                                {{-- Sub Images --}}
                                <div class="col-md-6 mb-3 sub-image-field" style="display:none;">
                                    <label class="form-label">Sub Images (Multiple, Max 4)</label>
                                    <input type="file" name="sub_image[]" class="form-control" multiple>
                                    <small class="text-muted">You can upload up to 4 images.</small>
                                    <div id="existingSubImages" class="mt-2 d-flex flex-wrap gap-2"></div>
                                </div>

                                {{-- Agent --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Agent</label>
                                    <select name="agent_id" id="edit_agent_id" class="form-select" required>
                                        <option value="">Select Agent</option>
                                        @foreach ($agents as $agent)
                                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Insurance Type --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Insurance Type</label>
                                    <input type="text" name="insurance_type" id="edit_insurance_type"
                                        class="form-control" placeholder="Enter Insurance Type">
                                </div>



                                {{-- Status --}}
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
            const searchInput = document.getElementById('vehicleSearch');
            const statusSelect = document.getElementById('vehicleStatusFilter');
            const vehicleTableBody = document.querySelector('#vehicleTable tbody');

            // Fetch vehicles based on filters
            function fetchFilteredVehicles() {
                const query = searchInput.value.trim();
                const status = statusSelect.value;

                fetch(`{{ route('admin.vehicles.index') }}?search=${query}&status=${status}`, {
                        headers: {
                            "Accept": "application/json"
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        vehicleTableBody.innerHTML = '';

                        if (data.vehicles.length === 0) {
                            vehicleTableBody.innerHTML = `
                    <tr>
                        <td colspan="16" class="text-center text-muted">No vehicles found.</td>
                    </tr>`;
                            return;
                        }

                        data.vehicles.forEach(vehicle => {
                            vehicleTableBody.innerHTML += `
                    <tr id="vehicle-${vehicle.id}">
                        <td>${vehicle.vehicle_image ? `<img src="/storage/${vehicle.vehicle_image}" class="vehicle-img">` : '<span class="text-muted">No Image</span>'}</td>
                        <td>${vehicle.name}</td>
                        <td>${vehicle.make}</td>
                        <td>${vehicle.model}</td>
                        <td>${vehicle.condition ? vehicle.condition.charAt(0).toUpperCase() + vehicle.condition.slice(1) : '-'}</td>
                    
                        <td>${Number(vehicle.price).toLocaleString(undefined, {minimumFractionDigits:2})}</td>
                        <td>${vehicle.type ? vehicle.type.charAt(0).toUpperCase() + vehicle.type.slice(1) : '-'}</td>
                        <td><span class="badge ${vehicle.status == 1 ? 'bg-success' : 'bg-danger'}">${vehicle.status == 1 ? 'Active' : 'Inactive'}</span></td>

                        <td class="text-center">

                                <a href="/admin/vehicles/${vehicle.id}" 
       class="icon-btn text-info" 
       title="Show Vehicle">
       <i class="bi bi-eye fs-5"></i>
    </a>

                            <button class="icon-btn text-primary editVehicleBtn"
                                data-id="${vehicle.id}"
                                data-name="${vehicle.name}"
                                data-make="${vehicle.make}"
                                data-model="${vehicle.model}"
                                data-seats="${vehicle.seats}"
                                data-milage="${vehicle.milage}"
                                data-air_conditioned="${vehicle.air_conditioned}"
                                data-helmet="${vehicle.helmet}"
                                data-first_aid_kit="${vehicle.first_aid_kit}"
                                data-condition="${vehicle.condition}"
                                data-transmission="${vehicle.transmission}"
                                data-price="${vehicle.price}"
                                data-type="${vehicle.type}"
                                data-status="${vehicle.status}"
                                data-fuel_type="${vehicle.fuel_type}"
                                data-luggage_space="${vehicle.luggage_space}"
                                data-insurance_type="${vehicle.insurance_type}"
                                data-agent_id="${vehicle.agent_id}"
                                data-vehicle_image="${vehicle.vehicle_image}"
                                data-subimages='${JSON.stringify(vehicle.sub_image || [])}'
                                data-bs-toggle="modal"
                                data-bs-target="#editVehicleModal"
                                title="Edit Vehicle">
                                <i class="bi bi-pencil-square fs-5"></i>
                            </button>

                            <button type="button"
                                class="icon-btn toggle-status-btn ${vehicle.status ? 'text-success' : 'text-warning'}"
                                data-id="${vehicle.id}" data-status="${vehicle.status}"
                                title="${vehicle.status ? 'Set as Inactive' : 'Set as Active'}">
                                <i class="${vehicle.status ? 'bi bi-check-circle-fill' : 'bi bi-slash-circle'} fs-5"></i>
                            </button>

                            <button type="button" class="icon-btn text-danger delete-vehicle"
                                data-id="${vehicle.id}" title="Delete Vehicle">
                                <i class="bi bi-trash-fill fs-5"></i>
                            </button>
                        </td>
                    </tr>`;
                        });
                    })
                    .catch(err => console.error(err));
            }

            // Event listeners for search & status filters
            searchInput.addEventListener('input', fetchFilteredVehicles);
            statusSelect.addEventListener('change', fetchFilteredVehicles);

            // --- Event Delegation for Actions (Edit / Delete / Toggle Status) ---
            document.querySelector('#vehicleTable').addEventListener('click', function(e) {
                const editBtn = e.target.closest('.editVehicleBtn');
                const deleteBtn = e.target.closest('.delete-vehicle');
                const toggleBtn = e.target.closest('.toggle-status-btn');

                // EDIT
                if (editBtn) {
                    const form = document.getElementById('editVehicleForm');
                    form.action = `/admin/vehicles/${editBtn.dataset.id}`;

                    // Populate fields (same as your existing logic)
                    document.getElementById('edit_name').value = editBtn.dataset.name || '';
                    document.getElementById('edit_make').value = editBtn.dataset.make || '';
                    document.getElementById('edit_model').value = editBtn.dataset.model || '';
                    document.getElementById('edit_milage').value = editBtn.dataset.milage || '';
                    document.getElementById('edit_price').value = editBtn.dataset.price || '';
                    document.getElementById('edit_air_conditioned').value = editBtn.dataset
                        .air_conditioned || '0';
                    document.getElementById('edit_helmet').value = editBtn.dataset.helmet || '0';
                    document.getElementById('edit_first_aid_kit').value = editBtn.dataset.first_aid_kit ||
                        '0';
                    document.getElementById('edit_condition').value = (editBtn.dataset.condition || '')
                        .toLowerCase();
                    document.getElementById('edit_transmission').value = (editBtn.dataset.transmission ||
                        '').toLowerCase();
                    document.getElementById('edit_status').value = editBtn.dataset.status || '1';
                    document.getElementById('edit_agent_id').value = editBtn.dataset.agent_id || '';
                    document.getElementById('edit_fuel_type').value = editBtn.dataset.fuel_type || '';
                    document.getElementById('edit_insurance_type').value = editBtn.dataset.insurance_type ||
                        '';
                    document.getElementById('edit_luggage_space').value = editBtn.dataset.luggage_space ||
                        '';
                    document.getElementById('edit_type').value = editBtn.dataset.type || '';

                    // Vehicle Image
                    const existingVehicleImage = document.getElementById('existingVehicleImage');
                    if (editBtn.dataset.vehicle_image) {
                        existingVehicleImage.src = `/storage/${editBtn.dataset.vehicle_image}`;
                        existingVehicleImage.style.display = 'block';
                    } else {
                        existingVehicleImage.style.display = 'none';
                    }

                    // Sub images
                    const subImagesContainer = document.getElementById('existingSubImages');
                    subImagesContainer.innerHTML = '';
                    const subImages = JSON.parse(editBtn.dataset.subimages || '[]');
                    if (subImages.length > 0) {
                        subImages.forEach(img => {
                            subImagesContainer.innerHTML += `
                        <div class="position-relative me-2 mb-2">
                            <img src="/storage/${img}" 
                                class="img-thumbnail" 
                                style="width: 100px; height: 100px; object-fit: cover;">
                        </div>`;
                        });
                    } else {
                        subImagesContainer.innerHTML =
                            `<p class="text-muted">No sub images uploaded yet.</p>`;
                    }

                    // Show/hide dynamic fields for Edit Modal
                    updateEditDynamicFields();
                }

                // DELETE
                if (deleteBtn) {
                    const id = deleteBtn.dataset.id;
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
                                            icon: 'success',
                                            title: 'Deleted!',
                                            text: data.message,
                                            timer: 1500,
                                            showConfirmButton: false
                                        });
                                    } else {
                                        Swal.fire("Error!", data.message ||
                                            "Failed to delete vehicle.", "error");
                                    }
                                });
                        }
                    });
                }

                // TOGGLE STATUS
                if (toggleBtn) {
                    const id = toggleBtn.dataset.id;
                    const currentStatus = toggleBtn.dataset.status;
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
                                        // Update dataset & UI
                                        toggleBtn.dataset.status = data.new_status;
                                        const icon = toggleBtn.querySelector("i");
                                        if (data.new_status == 1) {
                                            icon.className = "bi bi-check-circle-fill fs-5";
                                            toggleBtn.classList.remove("text-warning");
                                            toggleBtn.classList.add("text-success");
                                            toggleBtn.title = "Set as Inactive";
                                        } else {
                                            icon.className = "bi bi-slash-circle fs-5";
                                            toggleBtn.classList.remove("text-success");
                                            toggleBtn.classList.add("text-warning");
                                            toggleBtn.title = "Set as Active";
                                        }

                                        const statusBadge = document.querySelector(
                                            `#vehicle-${id} td:nth-child(14) span`);
                                        if (statusBadge) {
                                            statusBadge.textContent = data.new_status == 1 ?
                                                'Active' : 'Inactive';
                                            statusBadge.className = data.new_status == 1 ?
                                                'badge bg-success' : 'badge bg-danger';
                                        }

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
                                });
                        }
                    });
                }
            });

            // --- Dynamic fields for Edit Modal ---
            const editTypeSelect = document.getElementById('edit_type');
            const editDynamicFields = document.querySelectorAll('#editVehicleForm .dynamic-field');

            function updateEditDynamicFields() {
                const selectedType = editTypeSelect.value;
                editDynamicFields.forEach(field => {
                    const types = field.dataset.types.split(",");
                    field.style.display = types.includes(selectedType) ? "block" : "none";
                });
            }

            if (editTypeSelect) {
                editTypeSelect.addEventListener('change', updateEditDynamicFields);
            }
        });



        document.addEventListener("DOMContentLoaded", function() {
            const typeSelect = document.getElementById("vehicle_type");
            const dynamicFields = document.querySelectorAll(".dynamic-field");
            const subImageField = document.querySelector(".sub-image-field");

            function updateDynamicFields() {
                const selectedType = typeSelect.value;

                dynamicFields.forEach(field => {
                    const types = field.dataset.types.split(",");
                    if (types.includes(selectedType)) {
                        field.style.display = "block";
                    } else {
                        field.style.display = "none";
                    }
                });

                // Show sub images only for car/van
                if (selectedType === "car" || selectedType === "van") {
                    subImageField.style.display = "block";
                } else {
                    subImageField.style.display = "none";
                }
            }

            typeSelect.addEventListener("change", updateDynamicFields);
            updateDynamicFields(); // Initial load

            function showExistingSubImages(subImages) {
                let container = document.getElementById('existingSubImages');
                container.innerHTML = '';

                if (subImages && subImages.length > 0) {
                    subImages.forEach(img => {
                        container.innerHTML += `
                <div class="position-relative me-2 mb-2">
                    <img src="/storage/${img}" 
                         class="img-thumbnail" 
                         style="width: 100px; height: 100px; object-fit: cover;">
                </div>`;
                    });
                } else {
                    container.innerHTML = '<p class="text-muted">No sub images uploaded yet.</p>';
                }
            }



            if (typeSelect) {
                typeSelect.addEventListener('change', function() {
                    if (this.value === 'car' || this.value === 'van') {
                        subImageField.style.display = 'block';
                    } else {
                        subImageField.style.display = 'none';
                    }
                });
            }


            document.querySelectorAll('input[name="sub_image[]"]').forEach(input => {
                input.addEventListener('change', function() {
                    const maxFiles = 4;
                    if (this.files.length > maxFiles) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Too Many Images!',
                            text: `You can upload a maximum of ${maxFiles} images.`,
                            timer: 2500,
                            showConfirmButton: false
                        });
                        this.value = ""; // Clear selected files
                    }
                });
            });

            // --- Show/Hide Sub Image field in Edit Modal ---
            const editTypeSelect = document.getElementById('edit_type');
            const editSubImageField = document.querySelector('#editVehicleForm .sub-image-field');

            if (editTypeSelect) {
                editTypeSelect.addEventListener('change', function() {
                    if (this.value === 'car' || this.value === 'van') {
                        editSubImageField.style.display = 'block';
                    } else {
                        editSubImageField.style.display = 'none';
                    }
                });
            }

            // Ensure correct visibility when modal opens
            document.querySelectorAll('.editVehicleBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    setTimeout(() => {
                        const currentType = document.getElementById('edit_type').value;
                        if (currentType === 'car' || currentType === 'van') {
                            editSubImageField.style.display = 'block';
                        } else {
                            editSubImageField.style.display = 'none';
                        }
                    }, 300);
                });
            });


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

                    const subImagesContainer = document.getElementById('existingSubImages');
                    subImagesContainer.innerHTML = ''; // clear old previews
                    const subImages = JSON.parse(btn.dataset.subimages || '[]');

                    if (subImages.length > 0) {
                        subImages.forEach(img => {
                            subImagesContainer.innerHTML += `
                    <div class="position-relative me-2 mb-2">
                        <img src="/storage/${img}" 
                            class="img-thumbnail" 
                            style="width: 100px; height: 100px; object-fit: cover;">
                    </div>`;
                        });
                    } else {
                        subImagesContainer.innerHTML =
                            `<p class="text-muted">No sub images uploaded yet.</p>`;
                    }

                    // --- Show/hide sub image field for car/van ---
                    const editSubImageField = document.querySelector(
                        '#editVehicleForm .sub-image-field');
                    if (btn.dataset.type === 'car' || btn.dataset.type === 'van') {
                        editSubImageField.style.display = 'block';
                    } else {
                        editSubImageField.style.display = 'none';
                    }

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

        // --- Dynamic fields for Edit Modal ---
        const editTypeSelect = document.getElementById('edit_type');
        const editDynamicFields = document.querySelectorAll('#editVehicleForm .dynamic-field');

        function updateEditDynamicFields() {
            const selectedType = editTypeSelect.value;

            editDynamicFields.forEach(field => {
                const types = field.dataset.types.split(",");
                if (types.includes(selectedType)) {
                    field.style.display = "block";
                } else {
                    field.style.display = "none";
                }
            });
        }

        // On change
        if (editTypeSelect) {
            editTypeSelect.addEventListener('change', updateEditDynamicFields);
        }

        // When edit modal opens, update fields visibility
        document.querySelectorAll('.editVehicleBtn').forEach(btn => {
            btn.addEventListener('click', () => {
                const form = document.getElementById('editVehicleForm');
                form.action = `/admin/vehicles/${btn.dataset.id}`;

                // Populate values
                document.getElementById('edit_name').value = btn.dataset.name || '';
                document.getElementById('edit_make').value = btn.dataset.make || '';
                document.getElementById('edit_model').value = btn.dataset.model || '';
                document.getElementById('edit_milage').value = btn.dataset.milage || '';
                document.getElementById('edit_price').value = btn.dataset.price || '';
                document.getElementById('edit_air_conditioned').value = btn.dataset.air_conditioned || '0';
                document.getElementById('edit_helmet').value = btn.dataset.helmet || '0';
                document.getElementById('edit_first_aid_kit').value = btn.dataset.first_aid_kit || '0';
                document.getElementById('edit_condition').value = (btn.dataset.condition || '')
                    .toLowerCase();
                document.getElementById('edit_transmission').value = (btn.dataset.transmission || '')
                    .toLowerCase();
                document.getElementById('edit_status').value = btn.dataset.status || '1';
                document.getElementById('edit_agent_id').value = btn.dataset.agent_id || '';
                document.getElementById('edit_fuel_type').value = btn.dataset.fuel_type || '';
                document.getElementById('edit_insurance_type').value = btn.dataset.insurance_type || '';
                document.getElementById('edit_luggage_space').value = btn.dataset.luggage_space || '';
                document.getElementById('edit_type').value = btn.dataset.type || '';

                const existingVehicleImage = document.getElementById('existingVehicleImage');
                if (btn.dataset.vehicle_image) {
                    existingVehicleImage.src = `/storage/${btn.dataset.vehicle_image}`;
                    existingVehicleImage.style.display = 'block';
                } else {
                    existingVehicleImage.src = '';
                    existingVehicleImage.style.display = 'none';
                }

                // Sub images
                const subImagesContainer = document.getElementById('existingSubImages');
                subImagesContainer.innerHTML = '';
                const subImages = JSON.parse(btn.dataset.subimages || '[]');
                if (subImages.length > 0) {
                    subImages.forEach(img => {
                        subImagesContainer.innerHTML += `
                        <div class="position-relative me-2 mb-2">
                            <img src="/storage/${img}" 
                                class="img-thumbnail" 
                                style="width: 100px; height: 100px; object-fit: cover;">
                        </div>`;
                    });
                } else {
                    subImagesContainer.innerHTML = `<p class="text-muted">No sub images uploaded yet.</p>`;
                }

                // Update visibility dynamically
                updateEditDynamicFields();
            });
        });
    </script>
@endsection
