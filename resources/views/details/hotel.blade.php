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
                        <div class="col-md-12 mb-3">
                            <label for="hotel_name" class="form-label">Hotel Name</label>
                            <input type="text" name="hotel_name" id="hotel_name" class="form-control"
                                placeholder="Ex: Hilton Colombo" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" name="city" id="city" class="form-control"
                                placeholder="Ex: Colombo" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address" id="address" class="form-control"
                                placeholder="Ex: 123 Main Street" required>
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
                        <div class="col-md-6 mb-3">
                            <label for="room_type" class="form-label">Room Type</label>
                            <input type="text" name="room_type" id="room_type" class="form-control"
                                placeholder="Ex: Deluxe, Suite">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="meal_plan" class="form-label">Meal Plan</label>
                            <select name="meal_plan" id="meal_plan" class="form-select" required>
                                <option value="">Select Meal Plan</option>
                                <option value="half board">Half Board</option>
                                <option value="full board">Full Board</option>
                                <option value="all include">All Include</option>
                                <option value="room only">Room Only</option>
                            </select>
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
                            placeholder="Search by hotel name...">
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
            <div class="modal fade" id="editHotelModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Hotel</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form id="editHotelForm" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="modal-body"  style="max-height: 70vh; overflow-y: auto;">

                                <div id="editMessage"></div>

                                <!-- Hotel Name -->
                                <div class="mb-3">
                                    <label class="form-label">Hotel Name</label>
                                    <input type="text" name="hotel_name" id="edit_hotel_name" class="form-control"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit_city" class="form-label">City</label>
                                    <input type="text" name="city" id="edit_city" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="edit_address" class="form-label">Address</label>
                                    <input type="text" name="address" id="edit_address" class="form-control"
                                        >
                                </div>

                                <!-- Star -->
                                <div class="mb-3">
                                    <label class="form-label">Star Rating</label>
                                    <select name="star" id="edit_star" class="form-select">
                                        <option value="">Select Rating</option>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">{{ $i }} Star</option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Category -->
                                <div class="mb-3">
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

                                <!-- Room Type -->
                                <div class="mb-3">
                                    <label class="form-label">Room Type</label>
                                    <input type="text" name="room_type" id="edit_room_type" class="form-control">
                                </div>

                                <!-- Meal Plan -->
                                <div class="mb-3">
                                    <label class="form-label">Meal Plan</label>
                                    <input type="text" name="meal_plan" id="edit_meal_plan" class="form-control">
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                                </div>

                                <!-- Facilities -->
                                <div class="mb-3">
                                    <label class="form-label">Facilities</label>
                                    <textarea name="facilities" id="edit_facilities" class="form-control" rows="2"></textarea>
                                </div>

                                <!-- Entertainment -->
                                <div class="mb-3">
                                    <label class="form-label">Entertainment</label>
                                    <textarea name="entertainment" id="edit_entertainment" class="form-control" rows="2"></textarea>
                                </div>

                                <!-- Pictures -->
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
                                    </select>
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

    {{-- Scripts --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // ---------------------------
            // TOGGLE CREATE FORM
            // ---------------------------
            const toggleBtn = document.getElementById("toggleCreateForm");
            const formCard = document.getElementById("createHotelCard");

            toggleBtn.addEventListener("click", function() {
                formCard.style.display = formCard.style.display === "none" ? "block" : "none";
                toggleBtn.textContent =
                    formCard.style.display === "block" ? "Close Form" : "+ Add Hotel";
            });

            // ---------------------------
            // AJAX: CREATE HOTEL
            // ---------------------------
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
                            loadHotels();
                        } else {
                            let errors = data.errors ? Object.values(data.errors).flat().join('<br>') :
                                data.message;
                            messageBox.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                        }
                    }).catch(err => console.error(err));
            });

            // ---------------------------
            // AJAX: LOAD HOTELS (WITH FILTERS)
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
            // DELEGATE: EDIT HOTEL MODAL
            // ---------------------------
            hotelTableContainer.addEventListener("click", function(e) {
                const btn = e.target.closest(".edit-hotel");
                if (!btn) return;

                const id = btn.dataset.id;
                const form = document.getElementById("editHotelForm");
                form.action = `/admin/hotels/${id}`;

                form.querySelector("#edit_hotel_name").value = btn.dataset.name;
                form.querySelector("#edit_city").value = btn.dataset.city || '';
                form.querySelector("#edit_address").value = btn.dataset.address || '';
                form.querySelector("#edit_star").value = btn.dataset.star;
                form.querySelector("#edit_hotel_category").value = btn.dataset.category;
                form.querySelector("#edit_room_type").value = btn.dataset.room_type || '';
                form.querySelector("#edit_meal_plan").value = btn.dataset.meal_plan || '';
                form.querySelector("#edit_description").value = btn.dataset.description || '';
                form.querySelector("#edit_facilities").value = btn.dataset.facilities || '';
                form.querySelector("#edit_entertainment").value = btn.dataset.entertainment || '';
                form.querySelector("#edit_status").value = btn.dataset.status;

                // Load existing pictures
                const pictureContainer = document.getElementById("existingPictures");
                pictureContainer.innerHTML = '';
                let pictures = [];
                try {
                    pictures = JSON.parse(btn.dataset.pictures);
                } catch (e) {
                    pictures = [];
                }

                if (pictures.length > 0) {
                    pictures.forEach(pic => {
                        // Since images are in public/hotel, use asset path directly
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
                }).then((result) => {
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
    const viewerImg = document.getElementById("imageViewerImg");
    viewerImg.src = src;

    const info = document.getElementById("imageIndexInfo");
    if (all.length > 0) {
        const filename = src.split('/').pop();
        const idx = all.indexOf(filename);
        info.textContent = idx >= 0 ? `${idx+1} of ${all.length}` : `1 of ${all.length}`;
    } else info.textContent = '';

    new bootstrap.Modal(document.getElementById("imageViewerModal")).show();
});


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
