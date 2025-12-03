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

                {{-- Name + Email --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required
                               value="{{ old('name', $agent->name) }}" placeholder="Ex: John Doe">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $agent->email) }}" placeholder="Ex: john@gmail.com">
                    </div>
                </div>

                {{-- Company Name + Company City --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control"
                               value="{{ old('company_name', $agent->company_name) }}" placeholder="Ex: ABC Travels">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Company City</label>
                        <input type="text" name="company_city" class="form-control"
                               value="{{ old('company_city', $agent->company_city) }}" placeholder="Ex: Colombo">
                    </div>
                </div>

                {{-- Country + Phone --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Company Country</label>
                        <input type="text" name="company_country" class="form-control"
                               value="{{ old('company_country', $agent->company_country) }}" placeholder="Ex: Sri Lanka">
                    </div>

                    <div class="col-md-6 mb-3">
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

                {{-- Service (Select + Custom Input) --}}
                <div class="mb-3">
                    <label class="form-label">Service</label>
                    <select class="form-control" id="serviceSelect">
                        <option value="">-- Select Service --</option>
                        <option value="tour" {{ $agent->service=='tour' ? 'selected' : '' }}>Tour</option>
                        <option value="rent vehicle" {{ $agent->service=='rent vehicle' ? 'selected' : '' }}>Rent Vehicle</option>
                        <option value="transportation" {{ $agent->service=='transportation' ? 'selected' : '' }}>Transportation</option>
                        <option value="visa" {{ $agent->service=='visa' ? 'selected' : '' }}>Visa</option>
                        <option value="air ticketing" {{ $agent->service=='air ticketing' ? 'selected' : '' }}>Air Ticketing</option>
                        <option value="passport" {{ $agent->service=='passport' ? 'selected' : '' }}>Passport</option>
                        <option value="custom" {{ !in_array($agent->service, ['tour','rent vehicle','transportation','visa','air ticketing','passport']) ? 'selected' : '' }}>+ Add Custom Service</option>
                    </select>

                    <input type="text" name="service" id="customService" class="form-control mt-2 {{ !in_array($agent->service, ['tour','rent vehicle','transportation','visa','air ticketing','passport']) ? '' : 'd-none' }}"
                        value="{{ !in_array($agent->service, ['tour','rent vehicle','transportation','visa','air ticketing','passport']) ? $agent->service : '' }}"
                        placeholder="Type custom service ">
                </div>

                {{-- Note --}}
                <div class="mb-3">
                    <label class="form-label">Note</label>
                    <textarea name="note" class="form-control" placeholder="Add any additional information">{{ old('note', $agent->note) }}</textarea>
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" {{ $agent->status ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$agent->status ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Submit --}}
<div class="d-flex justify-content-end mb-3 gap-2">
    <a href="{{ route('admin.agents.index') }}" class="btn btn-secondary" style="width: 150px">
        Back
    </a>
    <button type="submit" class="btn btn-primary " style="width: 150px">
        Update Agent
    </button>
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
