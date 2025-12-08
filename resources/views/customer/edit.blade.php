@extends('layouts.vertical', ['subtitle' => 'Customer Edit'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Customer', 'subtitle' => 'Edit'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Customer</h5>
        </div>

        <div class="card-body">

            <div id="message"></div>

            <form id="editCustomerForm" action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Full Name + Email --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $customer->email) }}">
                    </div>
                </div>

                {{-- Contact --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="contact" class="form-control"
                            value="{{ old('contact', $customer->contact) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Other Phone</label>
                        <input type="text" name="other_phone" class="form-control"
                            value="{{ old('other_phone', $customer->other_phone) }}">
                    </div>
                </div>

                {{-- More fields --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" class="form-control"
                            value="{{ old('whatsapp_number', $customer->whatsapp_number) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control"
                            value="{{ old('date_of_birth', $customer->date_of_birth) }}">
                    </div>
                </div>

                {{-- Address --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control"
                            value="{{ old('address', $customer->address) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" class="form-control"
                            value="{{ old('country', $customer->country) }}">
                    </div>
                </div>

                {{-- Service + Heard Us --}}
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

                    <!-- Hidden JSON input to submit array -->
                    <input type="hidden" name="service" id="serviceJson" value='@json($customer->service ?? [])'>


                    <div class="col-md-6 mb-3">
                        <label class="form-label">Heard Us</label>
                        <select name="heard_us" class="form-select">
                            <option value="">Select</option>
                            @foreach (['Working Customer', 'Trip Advisor', 'Google', 'Facebook', 'Instagram', 'TikTok', 'Contacts List', 'Friends', 'Family', 'Koko', 'Reference'] as $h)
                                <option value="{{ $h }}"
                                    {{ old('heard_us', $customer->heard_us) == $h ? 'selected' : '' }}>
                                    {{ $h }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Type + Company --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="Individual" {{ $customer->type == 'Individual' ? 'selected' : '' }}>Individual
                            </option>
                            <option value="Corporate" {{ $customer->type == 'Corporate' ? 'selected' : '' }}>Corporate
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3" id="companyDiv">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control"
                            value="{{ old('company_name', $customer->company_name) }}">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update Customer</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const serviceSelect = document.getElementById('serviceSelect');
            const customInput = document.getElementById('customServiceInput');
            const addBtn = document.getElementById('addServiceBtn');
            const serviceList = document.getElementById('serviceList');
            const serviceJson = document.getElementById('serviceJson');

            // Load existing services from DB
            let services = @json($customer->service ?? []);

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

            window.removeService = function(index) {
                services.splice(index, 1);
                updateServiceList();
            }

            serviceSelect.addEventListener('change', () => {
                if (serviceSelect.value === 'custom') {
                    customInput.classList.remove('d-none');
                    customInput.value = '';
                    customInput.focus();
                } else {
                    customInput.classList.add('d-none');
                }
            });

            addBtn.addEventListener('click', () => {
                let value = serviceSelect.value;
                if (value === 'custom') {
                    value = customInput.value.trim();
                }
                if (!value) return alert("Please select or enter a service");
                if (services.includes(value)) return alert("Service already added");
                services.push(value);
                updateServiceList();
            });

            // Initialize list on page load
            updateServiceList();

            document.getElementById('type').addEventListener('change', function() {
                const companyDiv = document.getElementById('companyDiv');
                companyDiv.style.display = this.value === 'Corporate' ? 'block' : 'none';
            });

            // Load initial state
            document.getElementById('type').dispatchEvent(new Event('change'));


            // AJAX submit
            const form = document.getElementById('editCustomerForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
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

                        } else {
                            let errors = data.errors ? data.errors.join('<br>') : data.message;
                            messageBox.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                        }
                    })
                    .catch(err => {
                        document.getElementById('message').innerHTML =
                            `<div class="alert alert-danger">Something went wrong.</div>`;
                        console.error(err);
                    });
            });


        });
    </script>
@endsection
