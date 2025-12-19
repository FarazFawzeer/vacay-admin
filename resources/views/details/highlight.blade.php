@extends('layouts.vertical', ['subtitle' => 'Destination Highlights'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Add Highlight',
        'subtitle' => 'Destination Highlights',
    ])

    <style>
        .btn-equal {
            width: 80px;
            /* or any fixed width you want */
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
                <button type="button" id="toggleCreateForm" class="btn btn-primary">+ Add Highlight</button>
            </div>
        </div>

        {{-- Create Highlight Form (Hidden by Default) --}}
        <div class="card mb-4" id="createHighlightCard" style="display: none;">
            <div class="card-body">
                <div id="message"></div> {{-- Success / Error messages --}}

                <form id="createHighlightForm" action="{{ route('admin.destination-highlights.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- Select Destination --}}
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="destination_id" class="form-label">Destination</label>
                            <select name="destination_id" id="destination_id" class="form-select" required>
                                <option value="">Select Destination</option>
                                @foreach ($destinations as $destination)
                                    <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Place Name --}}
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="place_name" class="form-label">Place Name</label>
                            <input type="text" name="place_name" id="place_name" class="form-control"
                                placeholder="Ex: Temple of Tooth" required>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Short description"></textarea>
                        </div>
                    </div>

                    {{-- Image --}}
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Create Highlight</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Highlight List --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" id="highlightTable">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Destination</th>
                                <th>Place Name</th>
                                <th>Description</th>
                                <th>Image</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($highlights as $highlight)
                                <tr id="highlight-{{ $highlight->id }}">
                                    <td>{{ $highlight->destination->name ?? '-' }}</td>
                                    <td>{{ $highlight->place_name }}</td>
                                    <td>{{ $highlight->description ?? '-' }}</td>
                                    <td>
                                        @if ($highlight->image)
                                            <img src="{{ asset('admin/storage/' . $highlight->image) }}"
                                                alt="{{ $highlight->place_name }}" width="80">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $highlight->updated_at->format('d M Y, h:i A') }}</td>
                                   <td class="text-center">

    {{-- Edit Highlight --}}
    <button type="button"
        class="icon-btn text-primary edit-highlight"
        data-id="{{ $highlight->id }}"
        data-destination="{{ $highlight->destination_id }}"
        data-place="{{ $highlight->place_name }}"
        data-description="{{ $highlight->description }}"
        title="Edit Highlight">
        <i class="bi bi-pencil-square fs-5"></i>
    </button>

    {{-- Delete Highlight --}}
    <button type="button"
        class="icon-btn text-danger delete-highlight"
        data-id="{{ $highlight->id }}"
        title="Delete Highlight">
        <i class="bi bi-trash-fill fs-5"></i>
    </button>

</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No highlights found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-end mt-3">
                        {{ $highlights->links() }}
                    </div>
                </div>
            </div>

            <!-- Edit Highlight Modal -->
            <div class="modal fade" id="editHighlightModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="editHighlightForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Highlight</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div id="editMessage"></div>

                                {{-- Select Destination --}}
                                <div class="mb-3">
                                    <label for="edit_destination_id" class="form-label">Destination</label>
                                    <select name="destination_id" id="edit_destination_id" class="form-select" required>
                                        <option value="">Select Destination</option>
                                        @foreach ($destinations as $destination)
                                            <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Place Name --}}
                                <div class="mb-3">
                                    <label for="edit_place_name" class="form-label">Place Name</label>
                                    <input type="text" name="place_name" id="edit_place_name" class="form-control"
                                        required>
                                </div>

                                {{-- Description --}}
                                <div class="mb-3">
                                    <label for="edit_description" class="form-label">Description</label>
                                    <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                                </div>

                                {{-- Image --}}
                                <div class="mb-3">
                                    <label for="edit_image" class="form-label">Image</label>
                                    <input type="file" name="image" id="edit_image" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update Highlight</button>
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
            const formCard = document.getElementById("createHighlightCard");

            toggleBtn.addEventListener("click", function() {
                if (formCard.style.display === "none") {
                    formCard.style.display = "block";
                    toggleBtn.textContent = "Close Form";
                } else {
                    formCard.style.display = "none";
                    toggleBtn.textContent = "+ Add Highlight";
                }
            });

            // AJAX Submit
            document.getElementById('createHighlightForm').addEventListener('submit', function(e) {
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
                            setTimeout(() => {
                                messageBox.innerHTML = "";
                            }, 3000);

                            // Optional: reload table
                            location.reload();
                        } else {
                            let errors = data.errors ? Object.values(data.errors).flat().join('<br>') :
                                data.message;
                            messageBox.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                        }
                    })
                    .catch(err => console.error(err));
            });
        });

        document.addEventListener("DOMContentLoaded", function() {

            // Edit Highlight button click
            document.querySelector("#highlightTable").addEventListener("click", function(e) {
                const btn = e.target.closest(".edit-highlight");
                if (!btn) return;

                const id = btn.dataset.id;
                const destinationId = btn.dataset.destination;
                const placeName = btn.dataset.place;
                const description = btn.dataset.description;

                // Fill form
                document.getElementById("editHighlightForm").action = `/admin/destination-highlights/${id}`;
                document.getElementById("edit_destination_id").value = destinationId;
                document.getElementById("edit_place_name").value = placeName;
                document.getElementById("edit_description").value = description;

                // Show modal
                const editModalEl = document.getElementById("editHighlightModal");
                const editModal = new bootstrap.Modal(editModalEl);
                editModal.show();
            });

            // AJAX submit for edit
            document.getElementById("editHighlightForm").addEventListener("submit", function(e) {
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
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            const errors = data.errors ? Object.values(data.errors).flat().join(
                                '<br>') : data.message;
                            messageBox.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                        }
                    })
                    .catch(err => console.error(err));
            });
        });
    </script>
@endsection
