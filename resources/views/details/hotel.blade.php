@extends('layouts.vertical', ['subtitle' => 'Hotels'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Add Hotel',
        'subtitle' => 'Hotels',
    ])

    <style>
        /* Scrollable table wrapper */
        #hotelTableWrapper {
            max-height: calc(100vh - 250px);
            /* Adjust 250px according to header, filters, pagination */
            overflow-y: auto;
        }

        /* Make table header sticky */
        #hotelTable thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            /* Match table header background */
            z-index: 10;
        }

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

        {{-- Toggle Form --}}
        <div class="mb-4">
            <div class="card-body d-flex justify-content-end align-items-center">
                <button type="button" id="toggleCreateForm" class="btn btn-primary">+ Add Hotel</button>
            </div>
        </div>

        {{-- Create Hotel Form --}}
        <div class="card mb-4" id="createHotelCard" style="display: none;">
            <div class="card-body">
                <div id="message"></div>

                <form id="createHotelForm" action="{{ route('admin.hotels.store') }}" method="POST">
                    @csrf

                    {{-- Hotel Name --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hotel_name" class="form-label">Hotel Name</label>
                            <input type="text" name="hotel_name" id="hotel_name" class="form-control"
                                placeholder="Ex: Hilton Colombo" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" name="country" id="country" class="form-control"
                                placeholder="Ex: Sri Lanka" required>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" name="city" id="city" class="form-control"
                                placeholder="Ex: Colombo">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address" id="address" class="form-control"
                                placeholder="Ex: 123 Main Street">
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="contact_person" class="form-label">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person" class="form-control"
                                placeholder="Ex: Mr. John Doe">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="landline_number" class="form-label">Landline Number</label>
                            <input type="text" name="landline_number" id="landline_number" class="form-control"
                                placeholder="Ex: 011-2345678">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="mobile_number" class="form-label">Mobile Number</label>
                            <input type="text" name="mobile_number" id="mobile_number" class="form-control"
                                placeholder="Ex: 077-1234567">
                        </div>
                    </div>
                    {{-- Star --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="star" class="form-label">Star Rating</label>
                            <select name="star" id="star" class="form-select">
                                <option value="">Select Rating</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} Star</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="hotel_category" class="form-label">Hotel Category</label>
                            <select name="hotel_category" id="hotel_category" class="form-select" required>
                                <option value="">Select Category</option>
                                <option value="luxury">Luxury</option>
                                <option value="standard">Standard</option>
                                <option value="budget">Budget</option>
                                <option value="villa">Villa</option>
                                <option value="apartment">Apartment</option>
                                <option value="roomtype">Room Type</option>
                                <option value="cabana">Cabana</option>
                                <option value="other">Other</option>
                            </select>
                        </div>


                    </div>






                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Room Types</label>

                            <div id="roomTypeWrapper">

                                <!-- Default Item -->
                                <div class="row room-type-item mb-2">

                                    <div class="col-md-3">
                                        <input type="text" name="room_type[]" class="form-control"
                                            placeholder="Room Type (Ex: Deluxe)">
                                    </div>

                                    <div class="col-md-3">
                                        <select name="meal_plan[]" class="form-control">
                                            <option value="">Select Meal Plan</option>
                                            <option value="half board">Half Board</option>
                                            <option value="full board">Full Board</option>
                                            <option value="all include">All Include</option>
                                            <option value="room only">Room Only</option>
                                            <option value="bed and breakfast">Bed and Breakfast</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <input type="number" step="0.01" name="room_price[]" class="form-control"
                                            placeholder="Price">
                                    </div>

                                    <div class="col-md-2">
                                        <select name="room_currency[]" class="form-control">
                                            <option value="USD">USD</option>
                                            <option value="LKR">LKR</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 mt-2">
                                        <input type="file" name="room_image[]" class="form-control" accept="image/*">
                                    </div>

                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-item w-100">Remove</button>
                                    </div>

                                </div>

                            </div>

                            <button type="button" id="addRoomType" class="btn btn-sm btn-primary mt-2">Add Room
                                Type</button>
                        </div>


                        <div class="col-md-6 mb-3">
                            <label class="form-label">Facilities</label>
                            <div id="facilitiesWrapper">
                                <div class="input-group mb-2 facilities-item">
                                    <input type="text" name="facilities[]" class="form-control"
                                        placeholder="Ex: Free WiFi">
                                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                                </div>
                            </div>
                            <button type="button" id="addFacility" class="btn btn-sm btn-primary">Add Facility</button>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Entertainment</label>
                            <div id="entertainmentWrapper">
                                <div class="input-group mb-2 entertainment-item">
                                    <input type="text" name="entertainment[]" class="form-control"
                                        placeholder="Ex: Live Music">
                                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                                </div>
                            </div>
                            <button type="button" id="addEntertainment" class="btn btn-sm btn-primary">Add
                                Entertainment</button>

                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class="form-label"><strong>Meal & Aditional Costs</strong></label>

                            <div id="mealCostWrapper">

                                <!-- DEFAULT BREAKFAST -->
                                <div class="row mb-2 meal-item">
                                    <div class="col-md-3">
                                        <input type="text" name="meal_name[]" value="Breakfast" class="form-control"
                                            readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" step="0.01" name="meal_price[]" class="form-control"
                                            placeholder="Price">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="meal_currency[]" class="form-control">
                                            <option value="USD">USD</option>
                                            <option value="LKR">LKR</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-danger remove-meal">Remove</button>
                                    </div>
                                </div>

                                <!-- DEFAULT LUNCH -->
                                <div class="row mb-2 meal-item">
                                    <div class="col-md-3">
                                        <input type="text" name="meal_name[]" value="Lunch" class="form-control"
                                            readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" step="0.01" name="meal_price[]" class="form-control"
                                            placeholder="Price">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="meal_currency[]" class="form-control">
                                            <option value="USD">USD</option>
                                            <option value="LKR">LKR</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-danger remove-meal">Remove</button>
                                    </div>
                                </div>

                                <!-- DEFAULT DINNER -->
                                <div class="row mb-2 meal-item">
                                    <div class="col-md-3">
                                        <input type="text" name="meal_name[]" value="Dinner" class="form-control"
                                            readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" step="0.01" name="meal_price[]" class="form-control"
                                            placeholder="Price">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="meal_currency[]" class="form-control">
                                            <option value="USD">USD</option>
                                            <option value="LKR">LKR</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-danger remove-meal">Remove</button>
                                    </div>
                                </div>

                            </div>

                            <button type="button" id="addMealCost" class="btn btn-sm btn-primary mt-2">Add Meal
                                Cost</button>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pictures" class="form-label">Hotel Pictures</label>
                            <input type="file" name="pictures[]" id="pictures" class="form-control" multiple
                                accept="image/*">
                            <small class="text-muted">You can upload multiple images.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                                <option value="2">Expire</option>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control"
                                placeholder="Short description about the hotel"></textarea>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Create Hotel</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Hotel List --}}
        <div class="card">
            <div class="card-body">
                <div class="row mb-3 justify-content-end">
                    <div class="col-md-3">
                        <input type="text" id="hotelSearch" class="form-control"
                            placeholder="Search by hotel name, city, country, category...">
                    </div>
                    <div class="col-md-3">
                        <select id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            <option value="luxury">Luxury</option>
                            <option value="standard">Standard</option>
                            <option value="budget">Budget</option>
                            <option value="villa">Villa</option>
                            <option value="apartment">Apartment</option>
                            <option value="roomtype">Room Type</option>
                            <option value="cabana">Cabana</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>


                <div class="table-responsive table-wrapper" style=" max-height: calc(100vh - 250px);    overflow-y: auto;"
                    id="hotelTable">

                </div>
            </div>

            <!-- Edit Hotel Modal -->
            <!-- EDIT HOTEL MODAL -->
            <!-- EDIT HOTEL MODAL -->
            <div class="modal fade" id="editHotelModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Hotel</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form id="editHotelForm" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                <div id="editMessage"></div>

                                <!-- Hotel Name -->
                                <div class="mb-3">
                                    <label class="form-label">Hotel Name</label>
                                    <input type="text" name="hotel_name" id="edit_hotel_name" class="form-control"
                                        required>
                                </div>

                                <!-- Country / City / Address -->
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Country</label>
                                        <input type="text" name="country" id="edit_country" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city" id="edit_city" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="address" id="edit_address" class="form-control">
                                    </div>
                                </div>

                                <!-- Contact Details -->
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Contact Person</label>
                                        <input type="text" name="contact_person" id="edit_contact_person"
                                            class="form-control" placeholder="Ex: Mr. John Doe">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Landline Number</label>
                                        <input type="text" name="landline_number" id="edit_landline_number"
                                            class="form-control" placeholder="Ex: 011-2345678">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Mobile Number</label>
                                        <input type="text" name="mobile_number" id="edit_mobile_number"
                                            class="form-control" placeholder="Ex: 077-1234567">
                                    </div>
                                </div>


                                <!-- Star Rating -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Star Rating</label>
                                        <select name="star" id="edit_star" class="form-select">
                                            <option value="">Select Rating</option>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}">{{ $i }} Star</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <!-- Hotel Category -->

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Hotel Category</label>
                                        <select name="hotel_category" id="edit_hotel_category" class="form-select">
                                            <option value="">Select Category</option>
                                            <option value="luxury">Luxury</option>
                                            <option value="standard">Standard</option>
                                            <option value="budget">Budget</option>
                                            <option value="villa">Villa</option>
                                            <option value="apartment">Apartment</option>
                                            <option value="roomtype">Room Type</option>
                                            <option value="cabana">Cabana</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>


                                </div>

                                <!-- Room Types (with Price & Currency) -->
                                <div class="mb-3">
                                    <label class="form-label">Room Types</label>
                                    <div id="editRoomTypeWrapper"></div>
                                    <button type="button" id="editAddRoomType" class="btn btn-sm btn-primary mt-2">Add
                                        Room Type</button>
                                </div>


                                <!-- Facilities -->
                                <div class="mb-3">
                                    <label class="form-label">Facilities</label>
                                    <div id="editFacilitiesWrapper"></div>
                                    <button type="button" id="editAddFacility" class="btn btn-sm btn-primary mt-2">Add
                                        Facility</button>
                                </div>

                                <!-- Entertainment -->
                                <div class="mb-3">
                                    <label class="form-label">Entertainment</label>
                                    <div id="editEntertainmentWrapper"></div>
                                    <button type="button" id="editAddEntertainment"
                                        class="btn btn-sm btn-primary mt-2">Add Entertainment</button>
                                </div>

                                <!-- Meal & Additional Costs -->
                                <div class="mb-3">
                                    <label class="form-label"><strong>Meal & Additional Costs</strong></label>
                                    <div id="editMealCostWrapper"></div>
                                    <button type="button" id="editAddMealCost" class="btn btn-sm btn-primary mt-2">Add
                                        Aditional Cost</button>
                                </div>

                                <!-- Hotel Pictures -->
                                <div class="mb-3">
                                    <label class="form-label">Hotel Pictures</label>
                                    <div id="existingPictures" class="d-flex flex-wrap mb-2 gap-2"></div>
                                    <input type="file" name="pictures[]" id="edit_pictures" class="form-control"
                                        multiple>
                                </div>

                                <!-- Status -->
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="edit_status" class="form-select">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                        <option value="2">Expire</option>
                                    </select>
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                                </div>


                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update Hotel</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

            <!-- IMAGE VIEWER MODAL -->
            <div class="modal fade" id="imageViewerModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body p-2 text-center">
                            <img id="imageViewerImg" src="" alt="Preview"
                                style="max-width:100%; max-height:70vh; object-fit:contain;">
                        </div>
                        <div class="modal-footer justify-content-between">
                            <small id="imageIndexInfo" class="text-muted"></small>
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // ---------------------------
            // DYNAMIC FIELDS FUNCTION
            // ---------------------------
            function addInput(wrapperId, placeholder, inputName) {
                const wrapper = document.getElementById(wrapperId);
                const div = document.createElement('div');
                div.classList.add('input-group', 'mb-2');
                div.innerHTML = `
            <input type="text" name="${inputName}[]" class="form-control" placeholder="${placeholder}">
            <button type="button" class="btn btn-danger remove-item">Remove</button>
        `;
                wrapper.appendChild(div);
            }

            // Event delegation for removing dynamic inputs
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    const parent = e.target.closest('.input-group') || e.target.closest(
                        '.room-type-item') || e.target.closest('.meal-item');
                    if (parent) parent.remove();
                }
                if (e.target.classList.contains('remove-meal')) {
                    e.target.closest('.meal-item').remove();
                }
            });

            // ---------------------------
            // CREATE FORM: ADD DYNAMIC FIELDS
            // ---------------------------
            document.getElementById('addRoomType').addEventListener('click', function() {
                const wrapper = document.getElementById('roomTypeWrapper');
                const newItem = document.createElement('div');
                newItem.classList.add('row', 'room-type-item', 'mb-2');
                newItem.innerHTML = `
        <div class="col-md-3">
            <input type="text" name="room_type[]" class="form-control" placeholder="Room Type">
        </div>

        <div class="col-md-3">
            <select name="meal_plan[]" class="form-control">
                <option value="">Select Meal Plan</option>
                <option value="half board">Half Board</option>
                <option value="full board">Full Board</option>
                <option value="all include">All Include</option>
                <option value="room only">Room Only</option>
                <option value="bed and breakfast">Bed and Breakfast</option>
            </select>
        </div>

        <div class="col-md-2">
            <input type="number" step="0.01" name="room_price[]" class="form-control" placeholder="Price">
        </div>

        <div class="col-md-2">
            <select name="room_currency[]" class="form-control">
                <option value="USD">USD</option>
                <option value="LKR">LKR</option>
            </select>
        </div>

           <div class="col-md-2 mt-2">
                <input type="file" name="room_image[]" class="form-control" accept="image/*">
            </div>


        <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-item w-100">Remove</button>
        </div>
    `;
                wrapper.appendChild(newItem);
            });
            document.getElementById('addMealCost').addEventListener('click', function() {
                const wrapper = document.getElementById('mealCostWrapper');
                const div = document.createElement('div');
                div.classList.add('row', 'mb-2', 'meal-item');
                div.innerHTML = `
            <div class="col-md-3">
                <input type="text" name="meal_name[]" class="form-control" placeholder="Meal Name">
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" name="meal_price[]" class="form-control" placeholder="Price">
            </div>
            <div class="col-md-3">
                <select name="meal_currency[]" class="form-control">
                    <option value="USD">USD</option>
                    <option value="LKR">LKR</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-danger remove-meal">Remove</button>
            </div>
        `;
                wrapper.appendChild(div);
            });

            document.getElementById('addFacility').addEventListener('click', () => addInput('facilitiesWrapper',
                'Ex: Free WiFi', 'facilities'));
            document.getElementById('addEntertainment').addEventListener('click', () => addInput(
                'entertainmentWrapper', 'Ex: Live Music', 'entertainment'));

            // EDIT FORM ADD BUTTONS
            document.getElementById('editAddRoomType').addEventListener('click', function() {
                const wrapper = document.getElementById('editRoomTypeWrapper');
                const newItem = document.createElement('div');
                newItem.classList.add('row', 'room-type-item', 'mb-2');
                newItem.innerHTML = `
        <div class="col-md-3">
            <input type="text" name="room_type[]" class="form-control" placeholder="Room Type (Ex: Deluxe)">
        </div>

        <div class="col-md-3">
            <select name="meal_plan[]" class="form-control">
                <option value="">Select Meal Plan</option>
                <option value="half board">Half Board</option>
                <option value="full board">Full Board</option>
                <option value="all include">All Include</option>
                <option value="room only">Room Only</option>
                <option value="bed and breakfast">Bed and Breakfast</option>
            </select>
        </div>

        <div class="col-md-2">
            <input type="number" step="0.01" name="room_price[]" class="form-control" placeholder="Price">
        </div>

        <div class="col-md-2">
            <select name="room_currency[]" class="form-control">
                <option value="USD">USD</option>
                <option value="LKR">LKR</option>
            </select>
        </div>

        <div class="col-md-2 mt-2">
            <input type="file" name="room_image[]" class="form-control" accept="image/*">
        </div>

        <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-item w-100">Remove</button>
        </div>
    `;
                wrapper.appendChild(newItem);
            });

            document.getElementById('editAddFacility').addEventListener('click', () => addInput(
                'editFacilitiesWrapper', 'Ex: Free WiFi', 'facilities'));
            document.getElementById('editAddEntertainment').addEventListener('click', () => addInput(
                'editEntertainmentWrapper', 'Ex: Live Music', 'entertainment'));



            document.getElementById('editAddMealCost').addEventListener('click', function() {
                const wrapper = document.getElementById('editMealCostWrapper');
                const div = document.createElement('div');
                div.classList.add('row', 'mb-2', 'meal-item');
                div.innerHTML = `
        <div class="col-md-3">
            <input type="text" name="meal_name[]" class="form-control" placeholder="Meal Name">
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" name="meal_price[]" class="form-control" placeholder="Price">
        </div>
        <div class="col-md-3">
            <select name="meal_currency[]" class="form-control">
                <option value="USD">USD</option>
                <option value="LKR">LKR</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-danger remove-meal w-100">Remove</button>
        </div>
    `;
                wrapper.appendChild(div);
            });
            // ---------------------------
            // TOGGLE CREATE FORM
            // ---------------------------
            const toggleBtn = document.getElementById("toggleCreateForm");
            const formCard = document.getElementById("createHotelCard");
            toggleBtn.addEventListener("click", () => {
                formCard.style.display = formCard.style.display === "none" ? "block" : "none";
                toggleBtn.textContent = formCard.style.display === "block" ? "Close Form" : "+ Add Hotel";
            });

            // ---------------------------
            // AJAX: CREATE HOTEL
            // ---------------------------
            document.getElementById('createHotelForm').addEventListener('submit', function(e) {
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
                        const messageBox = document.getElementById('message');
                        if (data.success) {
                            messageBox.innerHTML =
                                `<div class="alert alert-success">${data.message}</div>`;
                            form.reset();
                            loadHotels();
                        } else {
                            const errors = data.errors ? Object.values(data.errors).flat().join(
                                '<br>') : data.message;
                            messageBox.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                        }
                    }).catch(err => console.error(err));
            });

            // ---------------------------
            // AJAX: LOAD HOTELS WITH FILTERS
            // ---------------------------
            const hotelTableContainer = document.getElementById('hotelTable');
            const searchInput = document.getElementById('hotelSearch');
            const categorySelect = document.getElementById('categoryFilter');

            function loadHotels(page = 1) {
                const search = searchInput.value;
                const category = categorySelect.value;
                const url = `{{ route('admin.hotels.index') }}?search=${search}&category=${category}&page=${page}`;
                fetch(url, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(res => res.text())
                    .then(html => hotelTableContainer.innerHTML = html)
                    .catch(err => console.error(err));
            }

            searchInput.addEventListener('keyup', () => loadHotels());
            categorySelect.addEventListener('change', () => loadHotels());

            // ---------------------------
            // EDIT HOTEL MODAL
            // ---------------------------
            hotelTableContainer.addEventListener('click', function(e) {
                const btn = e.target.closest(".edit-hotel");
                if (!btn) return;

                const form = document.getElementById("editHotelForm");
                form.action = `/admin/hotels/${btn.dataset.id}`;

                // Basic fields
                // Basic fields
                form.querySelector("#edit_hotel_name").value = btn.dataset.name && btn.dataset.name !==
                    'null' ? btn.dataset.name : '';
                form.querySelector("#edit_contact_person").value = btn.dataset.contact_person && btn.dataset
                    .contact_person !== 'null' ? btn.dataset.contact_person : '';
                form.querySelector("#edit_landline_number").value = btn.dataset.landline_number && btn
                    .dataset.landline_number !== 'null' ? btn.dataset.landline_number : '';
                form.querySelector("#edit_mobile_number").value = btn.dataset.mobile_number && btn.dataset
                    .mobile_number !== 'null' ? btn.dataset.mobile_number : '';
                form.querySelector("#edit_city").value = btn.dataset.city && btn.dataset.city !== 'null' ?
                    btn.dataset.city : '';
                form.querySelector("#edit_address").value = btn.dataset.address && btn.dataset.address !==
                    'null' ? btn.dataset.address : '';
                form.querySelector("#edit_country").value = btn.dataset.country && btn.dataset.country !==
                    'null' ? btn.dataset.country : '';
                form.querySelector("#edit_star").value = btn.dataset.star && btn.dataset.star !== 'null' ?
                    btn.dataset.star : '';
                form.querySelector("#edit_hotel_category").value = btn.dataset.category && btn.dataset
                    .category !== 'null' ? btn.dataset.category : '';
                form.querySelector("#edit_description").value = btn.dataset.description && btn.dataset
                    .description !== 'null' ? btn.dataset.description : '';
                form.querySelector("#edit_status").value = btn.dataset.status && btn.dataset.status !==
                    'null' ? btn.dataset.status : '1';

                // Dynamic fields
                function loadDynamic(wrapperId, dataAttr, placeholder, inputName) {
                    const wrapper = document.getElementById(wrapperId);
                    wrapper.innerHTML = '';
                    let items = [];
                    try {
                        items = JSON.parse(btn.dataset[dataAttr]) || [];
                    } catch (e) {
                        items = btn.dataset[dataAttr] ? [btn.dataset[dataAttr]] : [];
                    }
                    if (items.length) {
                        items.forEach(() => addInput(wrapperId, placeholder, inputName));
                        wrapper.querySelectorAll('input').forEach((input, i) => input.value = items[i]);
                    } else {
                        addInput(wrapperId, placeholder, inputName);
                    }
                }

                function loadRoomTypes(wrapperId, dataAttr) {
                    const wrapper = document.getElementById(wrapperId);
                    wrapper.innerHTML = '';
                    let items = [];

                    try {
                        items = JSON.parse(btn.dataset[dataAttr]) || [];
                    } catch (e) {
                        items = btn.dataset[dataAttr] ? [btn.dataset[dataAttr]] : [];
                    }

                    if (items.length) {
                        items.forEach(item => {
                            if (!item || typeof item !== 'object') item = {};

                            const div = document.createElement('div');
                            div.classList.add('row', 'room-type-item', 'mb-2');

                            // Existing image preview
                            let existingImageHtml = '';
                            if (item.image) {
                                existingImageHtml = `
                    <div class="mb-1">
                        <img src="/${item.image}" width="80" height="80" class="rounded" style="object-fit:cover;">
                    </div>
                `;
                            }

                            div.innerHTML = `
                <div class="col-md-3">
                    <input type="text" name="room_type[]" class="form-control" 
                        placeholder="Room Type" value="${item.type ?? ''}">
                </div>

                <div class="col-md-3">
                    <select name="meal_plan[]" class="form-control">
                        <option value="">Select Meal Plan</option>
                        <option value="half board" ${item.meal_plan=='half board'?'selected':''}>Half Board</option>
                        <option value="full board" ${item.meal_plan=='full board'?'selected':''}>Full Board</option>
                        <option value="all include" ${item.meal_plan=='all include'?'selected':''}>All Include</option>
                        <option value="room only" ${item.meal_plan=='room only'?'selected':''}>Room Only</option>
                        <option value="bed and breakfast" ${item.meal_plan=='bed and breakfast'?'selected':''}>Bed and Breakfast</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="number" step="0.01" name="room_price[]" class="form-control" 
                        value="${item.price ?? ''}" placeholder="Price">
                </div>

                <div class="col-md-2">
                    <select name="room_currency[]" class="form-control">
                        <option value="USD" ${item.currency=='USD'?'selected':''}>USD</option>
                        <option value="LKR" ${item.currency=='LKR'?'selected':''}>LKR</option>
                    </select>
                </div>

                <div class="col-md-2 mt-2">
                    ${existingImageHtml}
                    <input type="file" name="room_image[]" class="form-control" accept="image/*">
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-item w-100">Remove</button>
                </div>
            `;
                            wrapper.appendChild(div);
                        });
                    } else {
                        // fallback to add empty field
                        document.getElementById('editAddRoomType').click();
                    }
                }


                function loadMealCosts(wrapperId, dataAttr) {
                    const wrapper = document.getElementById(wrapperId);
                    wrapper.innerHTML = '';
                    let items = [];

                    try {
                        items = JSON.parse(btn.dataset[dataAttr]) || [];
                    } catch (e) {
                        items = btn.dataset[dataAttr] ? [btn.dataset[dataAttr]] : [];
                    }

                    if (items.length) {
                        items.forEach(item => {
                            const div = document.createElement('div');
                            div.classList.add('row', 'mb-2', 'meal-item');
                            div.innerHTML = `
                <div class="col-md-3">
                    <input type="text" name="meal_name[]" class="form-control" placeholder="Meal Name" value="${item.name ?? ''}">
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.01" name="meal_price[]" class="form-control" placeholder="Price" value="${item.price ?? ''}">
                </div>
                <div class="col-md-3">
                    <select name="meal_currency[]" class="form-control">
                        <option value="USD" ${item.currency=='USD'?'selected':''}>USD</option>
                        <option value="LKR" ${item.currency=='LKR'?'selected':''}>LKR</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-danger remove-meal w-100">Remove</button>
                </div>
            `;
                            wrapper.appendChild(div);
                        });
                    } else {
                        document.getElementById('editAddMealCost').click();
                    }
                }




                loadRoomTypes('editRoomTypeWrapper', 'room_type');
                loadMealCosts('editMealCostWrapper',
                    'meal_costs'); // assuming meal_costs is the data attribute
                loadDynamic('editFacilitiesWrapper', 'facilities', 'Ex: Free WiFi', 'facilities');
                loadDynamic('editEntertainmentWrapper', 'entertainment', 'Ex: Live Music', 'entertainment');

                // Pictures
                const pictureContainer = document.getElementById("existingPictures");
                pictureContainer.innerHTML = '';

                let pictures = [];
                if (btn.dataset.pictures) {
                    try {
                        pictures = JSON.parse(btn.dataset.pictures);
                        if (!Array.isArray(pictures)) pictures = [];
                    } catch (e) {
                        pictures = [];
                    }
                }

                if (pictures.length > 0) {
                    pictures.forEach(pic => {
                        pictureContainer.innerHTML += `
            <div class="position-relative d-inline-block m-1">
                <img src="/${pic}" class="rounded" width="80" height="80"
                    style="object-fit:cover; cursor:pointer;"
                    onclick="document.getElementById('imageViewerImg').src=this.src; new bootstrap.Modal(document.getElementById('imageViewerModal')).show();">
            </div>`;
                    });
                } else {
                    pictureContainer.innerHTML = `<span class="text-muted">No existing images</span>`;
                }

                new bootstrap.Modal(document.getElementById("editHotelModal")).show();
            });



            // ---------------------------
            // AJAX: UPDATE HOTEL
            // ---------------------------
            document.getElementById("editHotelForm").addEventListener("submit", function(e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);
                formData.append('_method', 'PUT');

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
                            loadHotels();
                        } else {
                            const errors = data.errors ? Object.values(data.errors).flat().join(
                                '<br>') : data.message;
                            messageBox.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                        }
                    }).catch(err => console.error(err));
            });

            // ---------------------------
            // DELETE HOTEL
            // ---------------------------
            hotelTableContainer.addEventListener("click", function(e) {
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
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch(`/admin/hotels/${id}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Accept": "application/json"
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    loadHotels();
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Hotel deleted.",
                                        icon: "success",
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                } else Swal.fire("Error!", data.message ||
                                    "Failed to delete hotel.", "error");
                            }).catch(err => Swal.fire("Error!", "Something went wrong.", "error"));
                    }
                });
            });

            // ---------------------------
            // IMAGE VIEWER
            // ---------------------------
            hotelTableContainer.addEventListener("click", function(e) {
                const imgBtn = e.target.closest(".view-image");
                if (!imgBtn) return;
                const src = imgBtn.dataset.src;
                const all = imgBtn.dataset.all ? JSON.parse(imgBtn.dataset.all) : [];
                document.getElementById("imageViewerImg").src = src;
                const info = document.getElementById("imageIndexInfo");
                if (all.length > 0) {
                    const filename = src.split('/').pop();
                    const idx = all.indexOf(filename);
                    info.textContent = idx >= 0 ? `${idx+1} of ${all.length}` : `1 of ${all.length}`;
                } else info.textContent = '';
                new bootstrap.Modal(document.getElementById('imageViewerModal')).show();
            });

            // ---------------------------
            // PAGINATION LINKS
            // ---------------------------
            hotelTableContainer.addEventListener('click', function(e) {
                const link = e.target.closest('.pagination a');
                if (!link) return;
                e.preventDefault();
                const url = new URL(link.href);
                const page = url.searchParams.get('page') || 1;
                loadHotels(page);
            });

            // ---------------------------
            // INITIAL LOAD
            // ---------------------------
            loadHotels();
        });
    </script>
@endsection
