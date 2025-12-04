@extends('layouts.vertical', ['subtitle' => 'Agent Edit'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Agent', 'subtitle' => 'Edit'])

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Edit Agent</h5>
    </div>

    <div class="card-body">
        <div id="message"></div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form id="editAgentForm" action="{{ route('admin.agents.update', $agent->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Company Name + City + Country --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company_name" class="form-control"
                        value="{{ old('company_name', $agent->company_name) }}" placeholder="Ex: ABC Travels">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Company City</label>
                    <input type="text" name="company_city" class="form-control"
                        value="{{ old('company_city', $agent->company_city) }}" placeholder="Ex: Colombo">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Company Country</label>
                    <input type="text" name="company_country" class="form-control"
                        value="{{ old('company_country', $agent->company_country) }}" placeholder="Ex: Sri Lanka">
                </div>
            </div>

            {{-- Name + Email + Phone --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control"
                        value="{{ old('name', $agent->name) }}" required placeholder="Ex: John Doe">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $agent->email) }}" placeholder="Ex: john@gmail.com">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control"
                        value="{{ old('phone', $agent->phone) }}" placeholder="Ex: +94771234567">
                </div>
            </div>

            {{-- Landline + WhatsApp --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Land Line</label>
                    <input type="text" name="land_line" class="form-control"
                        value="{{ old('land_line', $agent->land_line) }}" placeholder="Ex: 0112345678">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control"
                        value="{{ old('whatsapp', $agent->whatsapp) }}" placeholder="Ex: +94771234567">
                </div>
            </div>

            {{-- Service Section (Multi + Custom) --}}
            <div class="row">
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

                        <button type="button" id="addServiceBtn" class="btn btn-success">Add</button>
                    </div>

                    <!-- Custom input -->
                    <input type="text" id="customServiceInput" class="form-control mb-2 d-none"
                        placeholder="Enter custom service">

                    <!-- Display added services -->
                    <ul id="serviceList" class="list-group"></ul>

                    <!-- Hidden field -->
                    <input type="hidden" name="service" id="serviceJson">
                </div>

                {{-- Note --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label">Note</label>
                    <textarea name="note" class="form-control" placeholder="Add any additional information">{{ old('note', $agent->note) }}</textarea>
                </div>

                {{-- Status --}}
                <div class="col-md-2 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" {{ $agent->status ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$agent->status ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-end mb-3 gap-2">
                <a href="{{ route('admin.agents.index') }}" class="btn btn-secondary" style="width: 150px">Back</a>
                <button type="submit" class="btn btn-primary" style="width: 150px">Update Agent</button>
            </div>

        </form>
    </div>
</div>

{{-- JS – Exact Same Logic as Create Page --}}
<script>
    const serviceSelect = document.getElementById('serviceSelect');
    const customInput = document.getElementById('customServiceInput');
    const addBtn = document.getElementById('addServiceBtn');
    const serviceList = document.getElementById('serviceList');
    const serviceJson = document.getElementById('serviceJson');

    let services = @json($agent->service ?? []);

    updateServiceList();

    // Show custom box
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

        services.push(value);
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
