@extends('layouts.vertical', ['subtitle' => 'Passport'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Add Passport', 'subtitle' => 'Passport'])

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

        {{-- Toggle Form --}}
        <div class="mb-4">
            <div class="card-body d-flex justify-content-end align-items-center">
                <button type="button" id="toggleCreateForm" class="btn btn-primary">+ Add Passport</button>
            </div>
        </div>



        {{-- Create Passport Form --}}
        <div class="card mb-4" id="createPassportCard" style="display: none;">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card-body">
                <div id="message"></div>

                <form id="createPassportForm" action="{{ route('admin.passports.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->name ?? $customer->first_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Passport Number</label>
                            <input type="text" name="passport_number" class="form-control"
                                placeholder="Enter passport number" required>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Second Name</label>
                            <input type="text" name="second_name" class="form-control">
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nationality</label>
                            <input type="text" name="nationality" class="form-control" placeholder="Nationality"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sex</label>
                            <select name="sex" class="form-select">
                                <option value="">Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Issue Date</label>
                            <input type="date" name="issue_date" class="form-control">
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Passport Expiry</label>
                            <input type="date" name="passport_expire_date" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">ID Number</label>
                            <input type="text" name="id_number" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Photos (You can select multiple)</label>
                        <input type="file" name="id_photo[]" class="form-control" accept="image/*" multiple>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Create Passport</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Passport List --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" id="passportTable">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Customer</th>
                                <th>Passport No</th>
                                <th>Nationality</th>
                                <th>Expiry</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($passports as $p)
                                <tr id="passport-{{ $p->id }}">
                                    <td>{{ $p->customer->name ?? $p->customer->first_name }}</td>
                                    <td>{{ $p->passport_number }}</td>
                                    <td>{{ $p->nationality }}</td>
                                    <td>{{ $p->passport_expire_date }}</td>
                                    <td>
                                        @if ($p->id_photo)
                                            @foreach ($p->id_photo as $photo)
                                                <img src="{{ asset('admin/storage/' . $photo) }}" width="50"
                                                    height="50" class="rounded me-1 mb-1">
                                            @endforeach
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>

                                    <td>
                                        <!-- Show Passport -->
                                        <a href="{{ route('admin.passports.show', $p->id) }}" class="icon-btn text-info"
                                            title="View Passport">
                                            <i class="bi bi-eye fs-5"></i>
                                        </a>

                                        <!-- Edit Passport -->
                                        <button type="button" class="icon-btn text-primary edit-passport"
                                            data-id="{{ $p->id }}" data-customer="{{ $p->customer_id }}"
                                            data-fname="{{ $p->first_name }}" data-sname="{{ $p->second_name }}"
                                            data-nationality="{{ $p->nationality }}" data-sex="{{ $p->sex }}"
                                            data-passno="{{ $p->passport_number }}"
                                            data-exp="{{ $p->passport_expire_date }}" data-dob="{{ $p->dob }}"
                                            data-issue="{{ $p->issue_date }}" data-idnum="{{ $p->id_number }}"
                                            data-photo='@json($p->id_photo)'>
                                            <i class="bi bi-pencil-square fs-5"></i>
                                        </button>

                                        <!-- Delete Passport -->
                                        <button type="button" class="icon-btn text-danger delete-passport"
                                            data-id="{{ $p->id }}">
                                            <i class="bi bi-trash-fill fs-5"></i>
                                        </button>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No passports found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $passports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editPassportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editPassportForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Passport</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div id="editMessage"></div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer</label>
                                <select name="customer_id" id="edit_customer" class="form-select" required>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Passport Number</label>
                                <input type="text" name="passport_number" id="edit_passno" class="form-control"
                                    required>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" id="edit_fname" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Second Name</label>
                                <input type="text" name="second_name" id="edit_sname" class="form-control">
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nationality</label>
                                <input type="text" id="edit_nationality" name="nationality" class="form-control"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sex</label>
                                <select name="sex" id="edit_sex" class="form-select">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">DOB</label>
                                <input type="date" id="edit_dob" name="dob" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Issue Date</label>
                                <input type="date" id="edit_issue" name="issue_date" class="form-control">
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" id="edit_exp" name="passport_expire_date" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">ID Number</label>
                                <input type="text" id="edit_idnum" name="id_number" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Existing Photos</label>
                            <div id="existingPhoto" class="mb-2"></div>

                            <label class="form-label">Add More Photos</label>
                            <input type="file" name="id_photo[]" class="form-control" accept="image/*" multiple>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Passport</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("toggleCreateForm");
            const formCard = document.getElementById("createPassportCard");

            // Toggle Create Form
            toggleBtn.addEventListener("click", function() {
                formCard.style.display = formCard.style.display === "none" ? "block" : "none";
                toggleBtn.textContent = formCard.style.display === "block" ? "Close Form" :
                    "+ Add Passport";
            });

            // Create Passport
            document.getElementById("createPassportForm").addEventListener("submit", function(e) {
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
                                title: "Passport Created!",
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

            // Open Edit Modal
            document.querySelector("#passportTable").addEventListener("click", e => {
                const btn = e.target.closest(".edit-passport");
                if (!btn) return;

                const id = btn.dataset.id;

                document.getElementById("editPassportForm").action = `/admin/passports/${id}`;

                document.getElementById("edit_customer").value = btn.dataset.customer;
                document.getElementById("edit_fname").value = btn.dataset.fname;
                document.getElementById("edit_sname").value = btn.dataset.sname;
                document.getElementById("edit_passno").value = btn.dataset.passno;
                document.getElementById("edit_nationality").value = btn.dataset.nationality;
                document.getElementById("edit_sex").value = btn.dataset.sex;
                document.getElementById("edit_exp").value = btn.dataset.exp;
                document.getElementById("edit_dob").value = btn.dataset.dob;
                document.getElementById("edit_issue").value = btn.dataset.issue;
                document.getElementById("edit_idnum").value = btn.dataset.idnum;

                const photoContainer = document.getElementById("existingPhoto");
                if (btn.dataset.photo) {
                    let photos = [];
                    try {
                        photos = JSON.parse(btn.dataset.photo); // parse JSON array
                    } catch (e) {
                        photos = [btn.dataset.photo]; // fallback for single string
                    }

                    photoContainer.innerHTML = photos.map(photo =>
                        `<img src="/admin/storage/${photo}" width="80" height="80" class="rounded me-1 mb-1">`
                    ).join('');
                } else {
                    photoContainer.innerHTML = `<span class="text-muted">No existing image</span>`;
                }

                new bootstrap.Modal(document.getElementById("editPassportModal")).show();
            });

            // Update Passport
            document.getElementById("editPassportForm").addEventListener("submit", function(e) {
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

            // Delete Passport
            document.querySelector("#passportTable").addEventListener("click", e => {
                const btn = e.target.closest(".delete-passport");
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
                        fetch(`/admin/passports/${id}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Accept": "application/json"
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('passport-' + id).remove();
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
@endsection
