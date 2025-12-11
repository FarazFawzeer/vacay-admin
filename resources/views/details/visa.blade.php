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

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        {{-- Create Visa Form --}}
        {{-- Create Visa Form --}}
        <div class="card mb-4" id="createVisaCard" style="display: none;">
            <div class="card-body">

                <form id="createVisaForm" action="{{ route('admin.visa.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- ===================== BASIC DETAILS ======================= --}}
                    <div class="row">

                        {{-- From Country --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">From Country</label>
                            <select name="from_country" class="form-select" required>
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country['en'] }}">{{ $country['en'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- To Country --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">To Country</label>
                            <select name="to_country" class="form-select" required>
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country['en'] }}">{{ $country['en'] }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row">

                        {{-- Visa Type with Custom Add Option --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Visa Type</label>
                            <select name="visa_type" id="visaTypeSelect" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="e-Visa (Online Applying)">e-Visa (Online Applying)</option>
                                <option value="On Arrival (No Visa Needed)">On Arrival (No Visa Needed)</option>
                                <option value="Apply Visa (To Embassy)">Apply Visa (To Embassy)</option>
                                <option value="custom">Other (Add Manually)</option>
                            </select>

                            <input type="text" name="custom_visa_type" id="customVisaTypeInput"
                                class="form-control mt-2 d-none" placeholder="Enter custom visa type">
                        </div>

                    </div>

                    {{-- ===================== VISA CATEGORY SECTION ======================= --}}

                    <h5 class="mt-4">Visa Categories</h5>
                    <div id="visaCategoryWrapper"></div>

                    <button type="button" class="btn btn-primary btn-sm mt-2" id="addVisaCategoryBtn">
                        + Add Visa Category
                    </button>


                    {{-- ===================== CHECKLIST SECTION ======================= --}}
                    <h5 class="mt-4">Checklist</h5>

                    @php
                        $checklistItems = [
                            'Passport: Must have at least 6 months validity.',
                            'UAE Resident Visa: Copy required.',
                            'Passport Photo: White background.',
                            'Proof of Funds: Bank statement (last 3 months).',
                            'Return Flight Ticket: Confirmed ticket.',
                            'Accommodation Proof: Hotel booking or invitation letter.',
                            'Letter of No Objection from employer.',
                            'Marriage Certificate – English translation & original.',
                            'Birth Certificate – English translation & original.',
                            'National ID copies.',
                            'Deposit amount of 3000D (100% refundable).',
                        ];
                    @endphp

                    <div id="checklistWrapper" class="border rounded p-3">
                        @foreach ($checklistItems as $item)
                            <div class="d-flex align-items-center checklist-item mb-2">
                                <input type="text" name="checklist[]" class="form-control" value="{{ $item }}">
                                <button type="button" class="btn btn-danger btn-sm ms-2 removeChecklist">X</button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="addChecklistBtn">
                        + Add Checklist Item
                    </button>
                    <div class="row">

                        {{-- ===================== DOCUMENTS ======================= --}}
                        <div class="col-md-6 mt-4">
                            <label class="form-label">Documents (Image / PDF / Word)</label>
                            <input type="file" name="documents[]" class="form-control" multiple>
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

                    {{-- ===================== NOTE ======================= --}}

                    <div class="col-md-12 mt-3">
                        <label class="form-label">Note (Optional)</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Aditionl notes.."></textarea>
                    </div>

                    {{-- SUBMIT --}}
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success">Create Visa</button>
                    </div>
            </div>




            </form>
        </div>
    </div>


    {{-- Visa Table --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Visa List</h5>
            <p class="card-subtitle">All Visa in your system with details.</p>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end justify-content-end">
                <div class="col-md-3">

                    <select id="filterCountry" class="form-select">
                        <option value="">All Countries</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country['en'] }}">{{ $country['en'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">

                    <select id="filterAgent" class="form-select">
                        <option value="">All Agents</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->company_name }} - {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">

                    <input type="text" id="filterSearch" class="form-control" placeholder="Search...">
                </div>
            </div>

            <div class="table-responsive mt-4" id="visaTable">
                @include('details.visa_table', ['visas' => $visas])
            </div>
        </div>
    </div>
    </div>

    <!-- Edit Visa Modal -->
    <div class="modal fade" id="editVisaModal" tabindex="-1" aria-labelledby="editVisaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="editVisaForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editVisaModalLabel">Edit Visa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="editVisaModalBody">
                        <!-- ===================== BASIC DETAILS ======================= -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">From Country</label>
                                <select name="from_country" class="form-select" required>
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['en'] }}" class="edit-from-country"></option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">To Country</label>
                                <select name="to_country" class="form-select" required>
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['en'] }}" class="edit-to-country"></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Visa Type</label>
                                <select name="visa_type" class="form-select edit-visa-type" required>
                                    <option value="">Select Type</option>
                                    <option value="e-Visa (Online Applying)">e-Visa (Online Applying)</option>
                                    <option value="On Arrival (No Visa Needed)">On Arrival (No Visa Needed)</option>
                                    <option value="Apply Visa (To Embassy)">Apply Visa (To Embassy)</option>
                                    <option value="custom">Other (Add Manually)</option>
                                </select>
                                <input type="text" name="custom_visa_type"
                                    class="form-control mt-2 edit-custom-visa-type d-none"
                                    placeholder="Enter custom visa type">
                            </div>
                        </div>

                        <!-- ===================== VISA CATEGORY SECTION ======================= -->
                        <h5>Visa Categories</h5>
                        <div id="editVisaCategoryWrapper"></div>
                        <button type="button" class="btn btn-primary btn-sm mt-2" id="editAddVisaCategoryBtn">+ Add Visa
                            Category</button>

                        <!-- ===================== CHECKLIST SECTION ======================= -->
                        <h5 class="mt-4">Checklist</h5>
                        <div id="editChecklistWrapper" class="border rounded p-3"></div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" id="editAddChecklistBtn">+ Add
                            Checklist Item</button>

                        <div class="row">
                            <!-- ===================== DOCUMENTS ======================= -->
                            <div class="col-md-6 mt-4">
                                <label class="form-label">Documents (Image / PDF / Word)</label>
                                <input type="file" name="documents[]" class="form-control" multiple>

                                <div id="editDocumentsWrapper" class="mb-3 mt-3">
                                    <!-- Uploaded documents will be displayed here -->
                                </div>

                            </div>

                            <!-- ===================== AGENTS ======================= -->
                            <div class="col-md-6 mb-3 mt-4">
                                <label class="form-label">Select Agents</label>
                                <div class="border rounded p-2" id="editAgentsWrapper">
                                    @foreach ($agents as $agent)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="agents[]"
                                                value="{{ $agent->id }}" id="editAgent{{ $agent->id }}">
                                            <label class="form-check-label" for="editAgent{{ $agent->id }}">
                                                {{ $agent->company_name }} - {{ $agent->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- ===================== NOTE ======================= -->
                        <div class="col-md-12 mt-3">
                            <label class="form-label">Note (Optional)</label>
                            <textarea name="note" class="form-control edit-note" rows="3" placeholder="Additional notes.."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Visa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    

    <script>
        // Show custom visa type input
        document.getElementById('visaTypeSelect').addEventListener('change', function() {
            let customField = document.getElementById('customVisaTypeInput');
            customField.classList.toggle('d-none', this.value !== 'custom');
        });

        // ======== Add Visa Category Section ========


        let index = 0;

        document.getElementById('addVisaCategoryBtn').addEventListener('click', function() {

            let categoryNumber = index + 1; // Counter for display

            let html = `
    <div class="visa-category border rounded p-3 mb-3">
        <div class="d-flex justify-content-between">
            <h6>Visa Category ${categoryNumber}</h6>
            <button type="button" class="btn btn-danger btn-sm removeVisaCategory">Remove</button>
        </div>

        <div class="row mt-2">
            <div class="col-md-4 mb-3">
                <label>Visa Type</label>
                <input type="text" name="categories[${index}][visa_type]" class="form-control" placeholder="Example: Tourist Visa">
            </div>
            <div class="col-md-4 mb-3">
                <label>State</label>
                <input type="text" name="categories[${index}][state]" class="form-control" placeholder="Example: Dubai, Kuala Lumpur">
            </div>
            <div class="col-md-4 mb-3">
                <label>No. of Days</label>
                <input type="number" name="categories[${index}][days]" class="form-control" placeholder="Example: 30">
            </div>
            <div class="col-md-4 mb-3">
                <label>Visa Validity</label>
                <input type="text" name="categories[${index}][visa_validity]" class="form-control" placeholder="Example: 3 Months">
            </div>
            <div class="col-md-4 mb-3">
                <label>How Many Days</label>
                <input type="number" name="categories[${index}][how_many_days]" class="form-control" placeholder="Example: 60">
            </div>
            <div class="col-md-4 mb-3">
                <label>Price</label>
                <input type="number" step="0.01" name="categories[${index}][price]" class="form-control" placeholder="Example: 150">
            </div>
            <div class="col-md-4 mb-3">
                <label>Currency</label>
                <select name="categories[${index}][currency]" class="form-select">
                    <option value="">Select Currency</option>
                    <option value="LKR">LKR - Sri Lankan Rupee</option>
                    <option value="USD">USD - US Dollar</option>
                    <option value="AED">AED - UAE Dirham</option>
                    <option value="EUR">EUR - Euro</option>
                    <option value="GBP">GBP - British Pound</option>
                    <option value="SAR">SAR - Saudi Riyal</option>
                    <option value="QAR">QAR - Qatari Riyal</option>
                    <option value="OMR">OMR - Omani Rial</option>
                    <option value="KWD">KWD - Kuwaiti Dinar</option>
                    <option value="BHD">BHD - Bahraini Dinar</option>
                    <option value="INR">INR - Indian Rupee</option>
                    <option value="PKR">PKR - Pakistani Rupee</option>
                    <option value="AUD">AUD - Australian Dollar</option>
                    <option value="CAD">CAD - Canadian Dollar</option>
                    <option value="SGD">SGD - Singapore Dollar</option>
                    <option value="MYR">MYR - Malaysian Ringgit</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Processing Time</label>
                <input type="text" name="categories[${index}][processing_time]" class="form-control" placeholder="Example: 3 to 5 Working Days">
            </div>
        </div>
    </div>
    `;

            document.getElementById('visaCategoryWrapper').insertAdjacentHTML('beforeend', html);
            index++;
        });

        // Remove Visa Category
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeVisaCategory')) {
                e.target.closest('.visa-category').remove();
                // Optional: Re-number remaining categories
                let categories = document.querySelectorAll('.visa-category');
                categories.forEach((cat, idx) => {
                    cat.querySelector('h6').innerText = `Visa Category ${idx + 1}`;
                });
            }
        });



        // ======== CHECKLIST ========
        document.getElementById('addChecklistBtn').addEventListener('click', function() {
            let html = `
        <div class="d-flex align-items-center checklist-item mb-2">
            <input type="text" name="checklist[]" class="form-control" placeholder="Enter checklist item">
            <button type="button" class="btn btn-danger btn-sm ms-2 removeChecklist">X</button>
        </div>`;
            document.getElementById('checklistWrapper').insertAdjacentHTML('beforeend', html);
        });

        // Remove checklist item
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeChecklist')) {
                e.target.closest('.checklist-item').remove();
            }
        });
    </script>


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


        });

        document.addEventListener("DOMContentLoaded", function() {

            function fetchFilteredVisas() {
                let country = document.getElementById("filterCountry").value;
                let agent = document.getElementById("filterAgent").value;
                let search = document.getElementById("filterSearch").value;

                $.ajax({
                    url: "{{ route('admin.visa.index') }}",
                    method: "GET",
                    data: {
                        country: country,
                        agent: agent,
                        search: search
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        $("#visaTable").html(response);
                    },
                    error: function() {
                        alert("Failed to load filtered results");
                    }
                });

            }

            // Trigger filtering on change
            $("#filterCountry, #filterAgent").on("change", fetchFilteredVisas);

            // Trigger live search (typing)
            $("#filterSearch").on("keyup", function() {
                clearTimeout(window.searchDelay);
                window.searchDelay = setTimeout(fetchFilteredVisas, 300);
            });

        });
    </script>



    <!-- ===================== JS for Edit Modal ======================= -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Open Edit Modal
            $(document).on('click', '.editVisaBtn', function() {
                let visaId = $(this).data('id');
                let modal = new bootstrap.Modal(document.getElementById('editVisaModal'));

                // Set form action dynamically
                $('#editVisaForm').attr('action', '/admin/visa/' + visaId);

                $.ajax({
                    url: `/admin/visa/${visaId}/edit`,
                    method: 'GET',
                    success: function(response) {
                        let visa = response.visa; // extract visa from response
                        let countries = response.countries; //
                        let documents = response.documents; //
                        // Populate countries dropdown dynamically
                        let fromSelect = $('select[name="from_country"]');
                        let toSelect = $('select[name="to_country"]');
                        fromSelect.empty().append('<option value="">Select Country</option>');
                        toSelect.empty().append('<option value="">Select Country</option>');
                        countries.forEach(country => {
                            fromSelect.append(
                                `<option value="${country.en}" ${visa.from_country === country.en ? 'selected' : ''}>${country.en}</option>`
                            );
                            toSelect.append(
                                `<option value="${country.en}" ${visa.to_country === country.en ? 'selected' : ''}>${country.en}</option>`
                            );
                        });

                        $('select[name="visa_type"]').val(visa.visa_type);
                        $('input[name="custom_visa_type"]').val(visa.custom_visa_type || '');
                        if (visa.visa_type === 'custom') {
                            $('input[name="custom_visa_type"]').removeClass('d-none');
                        } else {
                            $('input[name="custom_visa_type"]').addClass('d-none');
                        }
                        $('textarea[name="note"]').val(visa.note);

                        // Populate agents
                        $('#editAgentsWrapper input[type="checkbox"]').prop('checked', false);
                        visa.agents.forEach(agent => {
                            $('#editAgent' + agent.id).prop('checked', true);
                        });

                        // Populate categories
                        $('#editVisaCategoryWrapper').html('');
                        visa.categories.forEach((cat, index) => {
                            let catHtml = `
                        <div class="visa-category border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <h6>Visa Category ${index+1}</h6>
                                <button type="button" class="btn btn-danger btn-sm removeVisaCategory">Remove</button>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4 mb-3">
                                    <label>Visa Type</label>
                                    <input type="text" name="categories[${index}][visa_type]" class="form-control" value="${cat.visa_type}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>State</label>
                                    <input type="text" name="categories[${index}][state]" class="form-control" value="${cat.state}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>No. of Days</label>
                                    <input type="number" name="categories[${index}][days]" class="form-control" value="${cat.days}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Visa Validity</label>
                                    <input type="text" name="categories[${index}][visa_validity]" class="form-control" value="${cat.visa_validity}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>How Many Days</label>
                                    <input type="number" name="categories[${index}][how_many_days]" class="form-control" value="${cat.how_many_days}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Price</label>
                                    <input type="number" step="0.01" name="categories[${index}][price]" class="form-control" value="${cat.price}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Currency</label>
                                    <select name="categories[${index}][currency]" class="form-select">
                                        <option value="">Select Currency</option>
                                        <option value="LKR" ${cat.currency=='LKR'?'selected':''}>LKR</option>
                                        <option value="USD" ${cat.currency=='USD'?'selected':''}>USD</option>
                                        <option value="AED" ${cat.currency=='AED'?'selected':''}>AED</option>
                                        <option value="EUR" ${cat.currency=='EUR'?'selected':''}>EUR</option>
                                        <option value="GBP" ${cat.currency=='GBP'?'selected':''}>GBP</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Processing Time</label>
                                    <input type="text" name="categories[${index}][processing_time]" class="form-control" value="${cat.processing_time}">
                                </div>
                            </div>
                        </div>`;
                            $('#editVisaCategoryWrapper').append(catHtml);
                        });

                        $('#editDocumentsWrapper').html('');
                        let existingDocs = response.documents || [];

                        if (existingDocs.length > 0) {
                            let html = existingDocs.map(doc => {
                                let relPath = doc.url.replace(window.location.origin +
                                    '/admin/storage/', '');
                                let ext = doc.name.split('.').pop().toLowerCase();
                                if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
                                    return `
            <div class="existing-file-wrapper d-inline-block position-relative me-2 mb-2">
                <img src="${doc.url}" width="80" height="80" class="rounded border">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 remove-existing-file" data-file="${relPath}" style="z-index:1"></button>
                <input type="hidden" name="existing_documents[]" value="${relPath}">
            </div>`;
                                } else if (ext === 'pdf') {
                                    return `
            <div class="existing-file-wrapper d-inline-block position-relative me-2 mb-2">
                <a href="${doc.url}" target="_blank" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf-fill"></i> 
                </a>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 remove-existing-file" data-file="${relPath}" style="z-index:1"></button>
                <input type="hidden" name="existing_documents[]" value="${relPath}">
            </div>`;
                                } else {
                                    return `
            <div class="existing-file-wrapper d-inline-block position-relative me-2 mb-2">
                <a href="${doc.url}" target="_blank" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-file-earmark-text"></i> 
                </a>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 remove-existing-file" data-file="${relPath}" style="z-index:1"></button>
                <input type="hidden" name="existing_documents[]" value="${relPath}">
            </div>`;
                                }
                            }).join('');

                            $('#editDocumentsWrapper').html(html);
                        } else {
                            $('#editDocumentsWrapper').html(
                                `<span class="text-muted">No existing files</span>`);
                        }

                        // Remove existing file on click
                        $(document).on('click', '.remove-existing-file', function() {
                            $(this).closest('.existing-file-wrapper').remove();
                        });

                        // Reset checklist if you use it
                        // Reset checklist if you use it
                        $('#editChecklistWrapper').html('');

                        // Use empty array if visa.checklist is undefined
                        (visa.checklist || []).forEach(item => {
                            $('#editChecklistWrapper').append(`
        <div class="d-flex align-items-center checklist-item mb-2">
            <input type="text" name="checklist[]" class="form-control" value="${item}">
            <button type="button" class="btn btn-danger btn-sm ms-2 removeChecklist">X</button>
        </div>
    `);
                        });


                        modal.show();
                    },
                    error: function() {
                        $('#editVisaModalBody').html(
                            '<p class="text-danger">Failed to load visa details.</p>');
                        modal.show();
                    }
                });
            });

            // Toggle custom visa type input
            $(document).on('change', 'select[name="visa_type"]', function() {
                let input = $(this).closest('.row, .col-md-6').find('input[name="custom_visa_type"]');
                if ($(this).val() == 'custom') {
                    input.removeClass('d-none');
                } else {
                    input.addClass('d-none');
                }
            });

            // Remove Visa Category dynamically
            $(document).on('click', '.removeVisaCategory', function() {
                $(this).closest('.visa-category').remove();
                $('#editVisaCategoryWrapper .visa-category').each(function(i) {
                    $(this).find('h6').text('Visa Category ' + (i + 1));
                    $(this).find('input, select').each(function() {
                        let name = $(this).attr('name');
                        if (name) {
                            let newName = name.replace(/\d+/, i);
                            $(this).attr('name', newName);
                        }
                    });
                });
            });

            // Add new Visa Category in modal
            let editIndex = 0;

            $('#editAddVisaCategoryBtn').click(function() {
                let categoryNumber = editIndex + 1; // For display

                let catHtml = `
    <div class="visa-category border rounded p-3 mb-3">
        <div class="d-flex justify-content-between">
            <h6>Visa Category ${categoryNumber}</h6>
            <button type="button" class="btn btn-danger btn-sm removeVisaCategory">Remove</button>
        </div>

        <div class="row mt-2">
            <div class="col-md-4 mb-3">
                <label>Visa Type</label>
                <input type="text" name="categories[${editIndex}][visa_type]" class="form-control" placeholder="Example: Tourist Visa">
            </div>
            <div class="col-md-4 mb-3">
                <label>State</label>
                <input type="text" name="categories[${editIndex}][state]" class="form-control" placeholder="Example: Dubai, Kuala Lumpur">
            </div>
            <div class="col-md-4 mb-3">
                <label>No. of Days</label>
                <input type="number" name="categories[${editIndex}][days]" class="form-control" placeholder="Example: 30">
            </div>
            <div class="col-md-4 mb-3">
                <label>Visa Validity</label>
                <input type="text" name="categories[${editIndex}][visa_validity]" class="form-control" placeholder="Example: 3 Months">
            </div>
            <div class="col-md-4 mb-3">
                <label>How Many Days</label>
                <input type="number" name="categories[${editIndex}][how_many_days]" class="form-control" placeholder="Example: 60">
            </div>
            <div class="col-md-4 mb-3">
                <label>Price</label>
                <input type="number" step="0.01" name="categories[${editIndex}][price]" class="form-control" placeholder="Example: 150">
            </div>
            <div class="col-md-4 mb-3">
                <label>Currency</label>
                <select name="categories[${editIndex}][currency]" class="form-select">
                    <option value="">Select Currency</option>
                    <option value="LKR">LKR - Sri Lankan Rupee</option>
                    <option value="USD">USD - US Dollar</option>
                    <option value="AED">AED - UAE Dirham</option>
                    <option value="EUR">EUR - Euro</option>
                    <option value="GBP">GBP - British Pound</option>
                    <option value="SAR">SAR - Saudi Riyal</option>
                    <option value="QAR">QAR - Qatari Riyal</option>
                    <option value="OMR">OMR - Omani Rial</option>
                    <option value="KWD">KWD - Kuwaiti Dinar</option>
                    <option value="BHD">BHD - Bahraini Dinar</option>
                    <option value="INR">INR - Indian Rupee</option>
                    <option value="PKR">PKR - Pakistani Rupee</option>
                    <option value="AUD">AUD - Australian Dollar</option>
                    <option value="CAD">CAD - Canadian Dollar</option>
                    <option value="SGD">SGD - Singapore Dollar</option>
                    <option value="MYR">MYR - Malaysian Ringgit</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Processing Time</label>
                <input type="text" name="categories[${editIndex}][processing_time]" class="form-control" placeholder="Example: 3 to 5 Working Days">
            </div>
        </div>
    </div>
    `;

                $('#editVisaCategoryWrapper').append(catHtml);
                editIndex++;
            });

            // Remove Visa Category dynamically
            $(document).on('click', '.removeVisaCategory', function() {
                $(this).closest('.visa-category').remove();

                // Re-number remaining categories and fix name indexes
                $('#editVisaCategoryWrapper .visa-category').each(function(i) {
                    $(this).find('h6').text('Visa Category ' + (i + 1));
                    $(this).find('input, select').each(function() {
                        let name = $(this).attr('name');
                        if (name) {
                            let newName = name.replace(/\d+/, i);
                            $(this).attr('name', newName);
                        }
                    });
                });

                // Update editIndex
                editIndex = $('#editVisaCategoryWrapper .visa-category').length;
            });



            // Add checklist dynamically
            $('#editAddChecklistBtn').click(function() {
                $('#editChecklistWrapper').append(`
                <div class="d-flex align-items-center checklist-item mb-2">
                    <input type="text" name="checklist[]" class="form-control">
                    <button type="button" class="btn btn-danger btn-sm ms-2 removeChecklist">X</button>
                </div>
            `);
            });

            // Remove checklist item
            $(document).on('click', '.removeChecklist', function() {
                $(this).closest('.checklist-item').remove();
            });

        });

      $(document).on('submit', '.deleteVisaForm', function(e){
    e.preventDefault();

    let form = $(this);
    let url = form.attr('action');

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
            $.ajax({
                url: url,
                type: 'POST',
                data: form.serialize(),
                success: function(response){
                    if(response.success){
                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        ).then(() => {
                            location.reload(); // Or remove row dynamically
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function(xhr){
                    Swal.fire(
                        'Error!',
                        'Failed to delete visa.',
                        'error'
                    );
                }
            });
        }
    });
});

    </script>
@endsection
