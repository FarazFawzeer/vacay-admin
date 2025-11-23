@extends('layouts.vertical', ['subtitle' => 'Customer Edit'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Customer', 'subtitle' => 'Edit'])

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Edit Customer</h5>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Full Name + Email --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control"
                        value="{{ old('name', $customer->name) }}" required>
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
                <div class="col-md-6 mb-3">
                    <label class="form-label">Service</label>
                    <select name="service" class="form-select">
                        <option value="">Select</option>
                        @foreach(['Tour Package','Rent Vehicle','Transportation','Airline Ticketing','Insurance Service','Visa Assistance'] as $s)
                            <option value="{{ $s }}" {{ old('service', $customer->service) == $s ? 'selected' : '' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Heard Us</label>
                    <select name="heard_us" class="form-select">
                        <option value="">Select</option>
                        @foreach(['Working Customer','Trip Advisor','Google','Facebook','Instagram','TikTok','Contacts List','Friends','Family','Koko','Reference'] as $h)
                            <option value="{{ $h }}" {{ old('heard_us', $customer->heard_us) == $h ? 'selected' : '' }}>
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
                        <option value="Individual" {{ $customer->type == 'Individual' ? 'selected' : '' }}>Individual</option>
                        <option value="Corporate" {{ $customer->type == 'Corporate' ? 'selected' : '' }}>Corporate</option>
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
document.getElementById('type').addEventListener('change', function() {
    const companyDiv = document.getElementById('companyDiv');
    companyDiv.style.display = this.value === 'Corporate' ? 'block' : 'none';
});

// Load initial state
document.getElementById('type').dispatchEvent(new Event('change'));
</script>

@endsection
