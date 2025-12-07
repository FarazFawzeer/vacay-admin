@extends('layouts.vertical', ['subtitle' => 'Visa'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Add Visa', 'subtitle' => 'Visa'])

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
                <button type="button" id="toggleCreateForm" class="btn btn-primary">+ Add Visa</button>
            </div>
        </div>

        {{-- Create Visa Form --}}
        {{-- Create Visa Form --}}
        <div class="card mb-4" id="createVisaCard" style="display: none;">
            <div class="card-body">
                <div id="message"></div>

                <form id="createVisaForm" action="{{ route('admin.visa.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Country</label>
                            <select name="country" class="form-select" required>
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country['en'] }}"
                                        {{ old('country') == $country['en'] ? 'selected' : '' }}>
                                        {{ $country['en'] }}
                                    </option>
                                @endforeach
                            </select>

                        </div>


                        <div class="col-md-6 mb-3">
                            <label class="form-label">Visa Type</label>
                            <select name="visa_type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="e-Visa (Online Applying)">e-Visa (Online Applying)</option>
                                <option value="On Arrival (No Visa Needed)">On Arrival (No Visa Needed)</option>
                                <option value="Apply Visa (To Embassy)">Apply Visa (To Embassy)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Visa Details</label>
                            <textarea name="visa_details" class="form-control" rows="3" placeholder="Enter visa details..."></textarea>
                        </div>


                        <div class="col-md-6 mb-3">
                            <label class="form-label">Documents (Image)</label>
                            <input type="file" name="documents" class="form-control" accept="image/*">
                        </div>

                    </div>

                    <div class="row">
                        {{-- NEW NOTE FIELD --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Note (Optional)</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Enter any additional notes..."></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Select Agents</label>
                            <div class="border rounded p-2">
                                @foreach ($agents as $agent)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="agents[]"
                                            value="{{ $agent->id }}" id="agent{{ $agent->id }}">
                                        <label class="form-check-label" for="agent{{ $agent->id }}">
                                            {{ $agent->company_name }} - {{ $agent->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Create Visa</button>
                    </div>

                </form>
            </div>
        </div>


        {{-- Filters --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Country</label>
                        <select id="filterCountry" class="form-select">
                            <option value="">All Countries</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country['en'] }}">{{ $country['en'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Agent</label>
                        <select id="filterAgent" class="form-select">
                            <option value="">All Agents</option>
                            @foreach ($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->company_name }} - {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" id="filterSearch" class="form-control" placeholder="Search...">
                    </div>
                </div>
            </div>
        </div>

        {{-- Visa Table --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" id="visaTable">
                    @include('details.visa_table', ['visas' => $visas])
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editVisaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content" style="max-height: 90vh; overflow-y: auto;">
                <form id="editVisaForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Visa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                        <div id="editMessage"></div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <select name="country" id="edit_country" class="form-select" required>
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['en'] }}">
                                            {{ $country['en'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-6 mb-3">
                                <label class="form-label">Visa Type</label>
                                <select name="visa_type" id="edit_type" class="form-select" required>
                                    <option value="e-Visa (Online Applying)">e-Visa (Online Applying)</option>
                                    <option value="On Arrival (No Visa Needed)">On Arrival (No Visa Needed)</option>
                                    <option value="Apply Visa (To Embassy)">Apply Visa (To Embassy)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Visa Details</label>
                                <textarea name="visa_details" id="edit_details" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Documents</label>
                                <div id="existingDoc" class="mb-2"></div>
                                <input type="file" name="documents" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Note</label>
                                <textarea name="note" id="edit_note" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select Agents</label>
                                <div class="border rounded p-2" id="edit_agent_list"
                                    style="max-height: 200px; overflow-y: auto;">
                                    @foreach ($agents as $agent)
                                        <div class="form-check">
                                            <input class="form-check-input edit-agent-checkbox" type="checkbox"
                                                name="agents[]" value="{{ $agent->id }}"
                                                id="editAgent{{ $agent->id }}">
                                            <label class="form-check-label" for="editAgent{{ $agent->id }}">
                                                {{ $agent->company_name }} - {{ $agent->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" style="width: 150px;"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" style="width: 150px;">Update Visa</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("toggleCreateForm");
            const formCard = document.getElementById("createVisaCard");

            // 🔹 Toggle Create Form
            toggleBtn.addEventListener("click", function() {
                formCard.style.display = formCard.style.display === "none" ? "block" : "none";
                toggleBtn.textContent = formCard.style.display === "block" ? "Close Form" : "+ Add Visa";
            });

            // 🔹 Create Visa
            document.getElementById("createVisaForm").addEventListener("submit", function(e) {
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
                                title: "Visa Created!",
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

            // 🔹 Edit Visa - Open Modal
            // 🔹 Edit Visa - Open Modal
            document.querySelector("#visaTable").addEventListener("click", e => {
                const btn = e.target.closest(".edit-visa");
                if (!btn) return;

                const id = btn.dataset.id;

                // Set form action
                document.getElementById("editVisaForm").action = `/admin/visa/${id}`;

                // Text fields
                document.getElementById("edit_country").value = btn.dataset.country;
                document.getElementById("edit_type").value = btn.dataset.type;
                document.getElementById("edit_details").value = btn.dataset.details || "";
                document.getElementById("edit_note").value = btn.dataset.note || "";

                // Existing image
                const docContainer = document.getElementById("existingDoc");
                docContainer.innerHTML = btn.dataset.documents ?
                    `<img src="/admin/storage/${btn.dataset.documents}" width="80" class="rounded">` :
                    `<span class="text-muted">No existing image</span>`;

                // Reset agent checkboxes
                document.querySelectorAll(".edit-agent-checkbox").forEach(cb => cb.checked = false);

                // Mark assigned agents
                let agentIds = btn.dataset.agents ? btn.dataset.agents.split(",") : [];

                agentIds.forEach(id => {
                    let checkbox = document.getElementById("editAgent" + id.trim());
                    if (checkbox) checkbox.checked = true;
                });

                new bootstrap.Modal(document.getElementById("editVisaModal")).show();
            });


            // 🔹 Update Visa
            document.getElementById("editVisaForm").addEventListener("submit", function(e) {
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

            // 🔹 Delete Visa
            document.querySelector("#visaTable").addEventListener("click", e => {
                const btn = e.target.closest(".delete-visa");
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
                        fetch(`/admin/visa/${id}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Accept": "application/json"
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('visa-' + id).remove();
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

            function fetchVisas() {
                let country = document.getElementById('filterCountry').value;
                let agent = document.getElementById('filterAgent').value;
                let search = document.getElementById('filterSearch').value;
                fetch(`{{ route('admin.visa.index') }}?country=${country}&agent=${agent}&search=${search}`, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(res => res.text())
                    .then(html => document.getElementById('visaTable').innerHTML = html);
            }
            document.getElementById('filterCountry').addEventListener('change', fetchVisas);
            document.getElementById('filterAgent').addEventListener('change', fetchVisas);
            let typingTimer;
            document.getElementById('filterSearch').addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(fetchVisas, 500);
            });
        });
    </script>
@endsection
