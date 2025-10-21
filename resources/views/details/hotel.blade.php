@extends('layouts.vertical', ['subtitle' => 'Hotels'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Add Hotel',
        'subtitle' => 'Hotels',
    ])

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

                    {{-- Star --}}
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="star" class="form-label">Star Rating</label>
                            <select name="star" id="star" class="form-select">
                                <option value="">Select Rating</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} Star</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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
                            @forelse($hotels as $hotel)
                                <tr id="hotel-{{ $hotel->id }}">
                                    <td>{{ $hotel->hotel_name }}</td>
                                    <td>{{ $hotel->star ? $hotel->star . ' ★' : '-' }}</td>
                                    <td>
                                        @if ($hotel->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $hotel->updated_at->format('d M Y, h:i A') }}</td>
                                    <td >

                                        {{-- Edit Hotel --}}
                                        <button type="button" class="icon-btn text-primary edit-hotel"
                                            data-id="{{ $hotel->id }}" data-name="{{ $hotel->hotel_name }}"
                                            data-star="{{ $hotel->star }}" data-status="{{ $hotel->status }}"
                                            title="Edit Hotel">
                                            <i class="bi bi-pencil-square fs-5"></i>
                                        </button>

                                        {{-- Delete Hotel --}}
                                        <button type="button" class="icon-btn text-danger delete-hotel"
                                            data-id="{{ $hotel->id }}" title="Delete Hotel">
                                            <i class="bi bi-trash-fill fs-5"></i>
                                        </button>

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hotels found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-end mt-3">
                        {{ $hotels->links() }}
                    </div>
                </div>
            </div>

            <!-- Edit Hotel Modal -->
            <div class="modal fade" id="editHotelModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="editHotelForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Hotel</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div id="editMessage"></div>

                                {{-- Hotel Name --}}
                                <div class="mb-3">
                                    <label for="edit_hotel_name" class="form-label">Hotel Name</label>
                                    <input type="text" name="hotel_name" id="edit_hotel_name" class="form-control"
                                        required>
                                </div>

                                {{-- Star --}}
                                <div class="mb-3">
                                    <label for="edit_star" class="form-label">Star Rating</label>
                                    <select name="star" id="edit_star" class="form-select">
                                        <option value="">Select Rating</option>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">{{ $i }} Star</option>
                                        @endfor
                                    </select>
                                </div>

                                {{-- Status --}}
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

    {{-- Scripts --}}
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
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
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
@endsection
