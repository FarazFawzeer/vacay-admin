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

                {{-- Name + Email --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="Ex: John Doe">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Ex: john@gmail.com">
                    </div>
                </div>

                {{-- Company Name + Company City --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" placeholder="Ex: ABC Travels">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Company City</label>
                        <input type="text" name="company_city" class="form-control" placeholder="Ex: Colombo">
                    </div>
                </div>

                {{-- Country + Phone --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Company Country</label>
                        <input type="text" name="company_country" class="form-control" placeholder="Ex: Sri Lanka">
                    </div>

                    <div class="col-md-6 mb-3">
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

                {{-- Service (Select + Custom Input) --}}
                <div class="mb-3">
                    <label class="form-label">Service</label>
                    <select class="form-control" id="serviceSelect">
                        <option value="">-- Select Service --</option>
                        <option value="tour">Tour</option>
                        <option value="rent vehicle">Rent Vehicle</option>
                        <option value="transportation">Transportation</option>
                        <option value="visa">Visa</option>
                        <option value="air ticketing">Air Ticketing</option>
                        <option value="passport">Passport</option>
                        <option value="custom">+ Add Custom Service</option>
                    </select>

                    <input type="text" name="service" id="customService" class="form-control mt-2 d-none"
                        placeholder="Type custom service ">
                </div>

                {{-- Note --}}
                <div class="mb-3">
                    <label class="form-label">Note</label>
                    <textarea name="note" class="form-control" placeholder="Add any additional information"></textarea>
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
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
        const select = document.getElementById('serviceSelect');
        const customInput = document.getElementById('customService');

        select.addEventListener('change', function() {
            if (this.value === 'custom') {
                customInput.classList.remove('d-none');
                customInput.focus();
            } else {
                customInput.classList.add('d-none');
                customInput.value = this.value; // set selected value
            }
        });
    </script>
@endsection
