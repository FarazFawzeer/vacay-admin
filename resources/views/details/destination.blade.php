@extends('layouts.vertical', ['subtitle' => 'Destination'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Add Details', 'subtitle' => 'Destination'])

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


        <div class=" mb-4">
            <div class="card-body d-flex justify-content-end  align-items-center">

                <button type="button" id="toggleCreateForm" class="btn btn-primary">
                    + Add Destination
                </button>
            </div>
        </div>

        {{-- Create Destination Form (Hidden by Default) --}}
        <div class="card mb-4" id="createDestinationCard" style="display: none;">
            <div class="card-body">
                <div id="message"></div> {{-- Success / Error messages --}}

                <form id="createDestinationForm" action="{{ route('admin.destinations.store') }}" method="POST">
                    @csrf

                    {{-- Name --}}
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="name" class="form-label">Destination Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name') }}" placeholder="Ex: Kandy" required>
                        </div>
                    </div>

                    {{-- Program Points (Dynamic) --}}
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

                    {{-- Submit --}}
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Create Destination</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Destination List --}}
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
                            @forelse($destinations as $destination)
                                <tr id="destination-{{ $destination->id }}">
                                    <td>{{ $destination->name }}</td>
                                    <td>
                                        @if (is_array($destination->program_points))
                                            <ul class="mb-0">
                                                @foreach ($destination->program_points as $point)
                                                    <li>{{ $point['point'] ?? '-' }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $destination->updated_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button type="button"
                                                class="btn btn-sm p-0 text-info border-0 bg-transparent edit-destination"
                                                data-id="{{ $destination->id }}" data-name="{{ $destination->name }}"
                                                data-points='@json($destination->program_points)'>
                                                <i class="fas fa-edit fa-lg"></i>
                                            </button>

                                            <button type="button"
                                                class="btn btn-sm p-0 text-danger border-0 bg-transparent delete-destination"
                                                data-id="{{ $destination->id }}">
                                                <i class="fas fa-trash-alt fa-lg"></i>
                                            </button>
                                        </div>
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No destinations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="modal fade" id="editDestinationModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form id="editDestinationForm" method="POST">
                                    @csrf
                                    @method('PUT')
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
                        {{ $destinations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
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
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                                "Accept": "application/json"
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

                        fetch("{{ route('admin.destinations.index') }}")
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
                        fetch("{{ url('admin/destinations') }}/" + id, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
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
                        fetch("{{ route('admin.destinations.index') }}")
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
@endsection
