@extends('layouts.vertical', ['subtitle' => 'Agent Create'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Agent', 'subtitle' => 'Create'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">New Agent</h5>
        </div>

        <div class="card-body">
            <div id="message"></div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            <form id="createAgentForm" action="{{ route('admin.agents.store') }}" method="POST">
                @csrf


                {{-- Company Name + Company City --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" placeholder="Ex: ABC Travels">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Company City</label>
                        <input type="text" name="company_city" class="form-control" placeholder="Ex: Colombo">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Company Country</label>
                        <input type="text" name="company_country" class="form-control" placeholder="Ex: Sri Lanka">
                    </div>
                </div>


                {{-- Name + Email --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="Ex: John Doe">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Ex: john@gmail.com">
                    </div>


                    <div class="col-md-4 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="Ex: +94771234567">
                    </div>
                </div>



                {{-- Landline + WhatsApp --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Land Line</label>
                        <input type="text" name="land_line" class="form-control" placeholder="Ex: 0112345678">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control" placeholder="Ex: +94771234567">
                    </div>
                </div>

                <div class="row">
                    {{-- Service (Select + Custom Input) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Services</label>

                        <div class="d-flex gap-2 mb-2">
                            <select class="form-control" id="serviceSelect">
                                <option value="">-- Select Service --</option>
                                <option value="Tour">Tour</option>
                                <option value="Rent Vehicle">Rent Vehicle</option>
                                <option value="Transportation">Transportation</option>
                                <option value="Visa">Visa</option>
                                <option value="Air Ticketing">Air Ticketing</option>
                                <option value="Passport">Passport</option>
                                <option value="custom">+ Add Custom Service</option>
                            </select>

                            <button type="button" id="addServiceBtn" class="btn btn-success">
                                Add
                            </button>
                        </div>

                        <!-- Custom input -->
                        <input type="text" id="customServiceInput" class="form-control mb-2 d-none"
                            placeholder="Enter custom service">

                        <!-- Display added services -->
                        <ul id="serviceList" class="list-group"></ul>
                    </div>

                    <!-- Hidden input to submit array -->
                    <input type="hidden" name="service" id="serviceJson">

                    {{-- Note --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Note</label>
                        <textarea name="note" class="form-control" placeholder="Add any additional information"></textarea>
                    </div>
                    {{-- Status --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Create Agent</button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS to Enable Custom Service --}}
    <script>
        const serviceSelect = document.getElementById('serviceSelect');
        const customInput = document.getElementById('customServiceInput');
        const addBtn = document.getElementById('addServiceBtn');
        const serviceList = document.getElementById('serviceList');
        const serviceJson = document.getElementById('serviceJson');

        let services = [];

        // Show custom input if "custom" selected
        serviceSelect.addEventListener('change', () => {
            if (serviceSelect.value === 'custom') {
                customInput.classList.remove('d-none');
                customInput.value = '';
                customInput.focus();
            } else {
                customInput.classList.add('d-none');
            }
        });

        // Add service
        addBtn.addEventListener('click', () => {
            let value = serviceSelect.value;

            if (value === 'custom') {
                value = customInput.value.trim();
            }

            if (!value) {
                alert("Please select or enter a service");
                return;
            }

            // Add to list
            services.push(value);

            // Update UI
            updateServiceList();
        });

        function updateServiceList() {
            serviceList.innerHTML = '';

            services.forEach((srv, index) => {
                const li = document.createElement('li');
                li.className = "list-group-item d-flex justify-content-between align-items-center";
                li.innerHTML = `
                ${srv}
                <button type="button" class="btn btn-sm btn-danger" onclick="removeService(${index})">X</button>
            `;
                serviceList.appendChild(li);
            });

            serviceJson.value = JSON.stringify(services);
        }

        function removeService(index) {
            services.splice(index, 1);
            updateServiceList();
        }
    </script>
@endsection
