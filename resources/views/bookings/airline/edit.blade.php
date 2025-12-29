@extends('layouts.vertical', ['subtitle' => 'Edit Airline Booking'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Airline Booking',
        'subtitle' => 'Edit',
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Airline Booking - {{ $booking->invoice_id }}</h5>
        </div>

        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="airlineBookingForm" action="{{ route('admin.airline-bookings.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <input type="hidden" name="final_passport_id" id="final_passport_id" value="{{ $booking->trips->first()->passport_id ?? '' }}">
                    <input type="hidden" name="final_passport_no" id="final_passport_no" value="{{ $booking->trips->first()->passport_no ?? '' }}">
                    
                    {{-- Business Type --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Business Type</label>
                        <select name="business_type" id="business_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="corporate" {{ $booking->business_type == 'corporate' ? 'selected' : '' }}>Corporate</option>
                            <option value="individual" {{ $booking->business_type == 'individual' ? 'selected' : '' }}>Individual</option>
                        </select>
                    </div>

                    {{-- Company Name for Corporate --}}
                    <div class="col-md-3 mb-3" id="company_name_section" style="display:{{ $booking->business_type == 'corporate' ? 'block' : 'none' }};">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" placeholder="Enter Company Name" value="{{ $booking->company_name }}">
                    </div>

                    {{-- Ticket Type --}}
                    <div class="col-md-3 mb-3" id="ticket_type_section">
                        <label class="form-label">Ticket Type</label>
                        <select name="ticket_type" id="ticket_type" class="form-select">
                            <option value="">Select Ticket Type</option>
                            <option value="one_way" {{ $booking->ticket_type == 'one_way' ? 'selected' : '' }}>One Way Ticket</option>
                            <option value="return" {{ $booking->ticket_type == 'return' ? 'selected' : '' }}>Return Ticket</option>
                        </select>
                    </div>

                    @php
                        $firstTrip = $booking->trips->first();
                        $tripType = $firstTrip->trip_type ?? 'one_way';
                        
                        // Determine current state
                        $isOneWay = $booking->ticket_type == 'one_way';
                        $isDummy = $booking->ticket_type == 'return' && $tripType == 'dummy';
                        $isReturnTicket = $booking->ticket_type == 'return' && in_array($tripType, ['going', 'return']);
                        $isRoundTrip = $booking->ticket_type == 'return' && $tripType == 'round_trip';
                        
                        // Get trips by type
                        $goingTrip = $booking->trips->where('trip_type', 'going')->first();
                        $returnTrip = $booking->trips->where('trip_type', 'return')->first();
                        $roundTrips = $booking->trips->where('trip_type', 'round_trip');
                    @endphp

                    {{-- One Way Section --}}
                    <div id="one_way_section" style="display:{{ $isOneWay || $isDummy ? 'block' : 'none' }};">
                        <h5>One Way Ticket Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer</label>
                                <select name="going_customer_id" class="form-select customer_select">
                                    <option value="">Select Customer</option>
                                    @foreach ($passports as $passport)
                                        <option value="{{ $passport->id }}" 
                                            data-passport="{{ $passport->passport_number }}"
                                            {{ ($firstTrip->passport_id ?? '') == $passport->id ? 'selected' : '' }}>
                                            {{ $passport->first_name }} {{ $passport->second_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Passport Number</label>
                                <input type="text" name="going_passport_no" class="form-control passport_input" 
                                    value="{{ $firstTrip->passport_no ?? '' }}" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Agent</label>
                                <select name="oneway_agent_id" class="form-select">
                                    <option value="">Select Agent</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ ($firstTrip->agent_id ?? '') == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->name }} - {{ $agent->company_name ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Airline / Flight</label>
                                <input type="text" name="oneway_airline" class="form-control" value="{{ $firstTrip->airline ?? '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Airline No</label>
                                <input type="text" name="oneway_airline_no" class="form-control" value="{{ $firstTrip->airline_no ?? '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">From Country</label>
                                <select name="oneway_from_country" class="form-select">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['en'] }}" {{ ($firstTrip->from_country ?? '') == $country['en'] ? 'selected' : '' }}>
                                            {{ $country['en'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">To Country</label>
                                <select name="oneway_to_country" class="form-select">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['en'] }}" {{ ($firstTrip->to_country ?? '') == $country['en'] ? 'selected' : '' }}>
                                            {{ $country['en'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">PNR No</label>
                                <input type="text" name="oneway_pnr" class="form-control" value="{{ $firstTrip->pnr ?? '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Departure Date & Time</label>
                                <input type="datetime-local" name="oneway_departure_datetime" class="form-control" 
                                    value="{{ $firstTrip->departure_datetime ? date('Y-m-d\TH:i', strtotime($firstTrip->departure_datetime)) : '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Arrival Date & Time</label>
                                <input type="datetime-local" name="oneway_arrival_datetime" class="form-control"
                                    value="{{ $firstTrip->arrival_datetime ? date('Y-m-d\TH:i', strtotime($firstTrip->arrival_datetime)) : '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Baggage Qty</label>
                                <input type="number" name="oneway_baggage_qty" class="form-control" value="{{ $firstTrip->baggage_qty ?? 0 }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Hand Luggage Qty</label>
                                <input type="number" name="oneway_handluggage_qty" class="form-control" value="{{ $firstTrip->handluggage_qty ?? 0 }}">
                            </div>
                        </div>
                    </div>

                    {{-- Return Type Section --}}
                    <div id="return_type_section" style="display:{{ $booking->ticket_type == 'return' ? 'block' : 'none' }};">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Return Type</label>
                            <select name="return_type" id="return_type" class="form-select">
                                <option value="">Select Return Type</option>
                                <option value="dummy" {{ $isDummy ? 'selected' : '' }}>Dummy</option>
                                <option value="return_ticket" {{ $isReturnTicket ? 'selected' : '' }}>Return Ticket</option>
                                <option value="round_trip" {{ $isRoundTrip ? 'selected' : '' }}>Round Trip</option>
                            </select>
                        </div>
                    </div>

                    {{-- Return Ticket Sections --}}
                    <div id="return_ticket_section" style="display:{{ $isReturnTicket ? 'block' : 'none' }};">
                        <h5>Going Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer</label>
                                <select name="return_going_customer_id" class="form-select customer_select">
                                    <option value="">Select Customer</option>
                                    @foreach ($passports as $passport)
                                        <option value="{{ $passport->id }}" 
                                            data-passport="{{ $passport->passport_number }}"
                                            {{ ($goingTrip->passport_id ?? '') == $passport->id ? 'selected' : '' }}>
                                            {{ $passport->first_name }} {{ $passport->second_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Passport Number</label>
                                <input type="text" name="return_going_passport_no" class="form-control passport_input" 
                                    value="{{ $goingTrip->passport_no ?? '' }}" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Agent</label>
                                <select name="going_agent_id" class="form-select">
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ ($goingTrip->agent_id ?? '') == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Airline / Flight</label>
                                <input type="text" name="going_airline" class="form-control" value="{{ $goingTrip->airline ?? '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Airline No</label>
                                <input type="text" name="going_airline_no" class="form-control" value="{{ $goingTrip->airline_no ?? '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">From Country</label>
                                <select name="going_from_country" class="form-select">
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['en'] }}" {{ ($goingTrip->from_country ?? '') == $country['en'] ? 'selected' : '' }}>
                                            {{ $country['en'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">To Country</label>
                                <select name="going_to_country" class="form-select">
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['en'] }}" {{ ($goingTrip->to_country ?? '') == $country['en'] ? 'selected' : '' }}>
                                            {{ $country['en'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">PNR No</label>
                                <input type="text" name="going_pnr" class="form-control" value="{{ $goingTrip->pnr ?? '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Departure Date & Time</label>
                                <input type="datetime-local" name="going_departure_datetime" class="form-control"
                                    value="{{ $goingTrip && $goingTrip->departure_datetime ? date('Y-m-d\TH:i', strtotime($goingTrip->departure_datetime)) : '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Arrival Date & Time</label>
                                <input type="datetime-local" name="going_arrival_datetime" class="form-control"
                                    value="{{ $goingTrip && $goingTrip->arrival_datetime ? date('Y-m-d\TH:i', strtotime($goingTrip->arrival_datetime)) : '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Baggage Qty</label>
                                <input type="number" name="going_baggage_qty" class="form-control" value="{{ $goingTrip->baggage_qty ?? 0 }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Hand Luggage Qty</label>
                                <input type="number" name="going_handluggage_qty" class="form-control" value="{{ $goingTrip->handluggage_qty ?? 0 }}">
                            </div>
                        </div>

                        <h5 class="mt-3">Return Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer</label>
                                <select name="going_customer_id" id="going_customer_id" class="form-select">
                                    <option value="">Select Customer</option>
                                    @foreach ($passports as $passport)
                                        <option value="{{ $passport->id }}" 
                                            data-passport="{{ $passport->passport_number }}"
                                            {{ ($returnTrip->passport_id ?? '') == $passport->id ? 'selected' : '' }}>
                                            {{ $passport->first_name }} {{ $passport->second_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Passport Number</label>
                                <input type="text" name="going_passport_no" id="going_passport_no" 
                                    class="form-control" value="{{ $returnTrip->passport_no ?? '' }}" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Agent</label>
                                <select name="coming_agent_id" class="form-select">
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ ($returnTrip->agent_id ?? '') == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Airline / Flight</label>
                                <input type="text" name="coming_airline" class="form-control" value="{{ $returnTrip->airline ?? '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Airline No</label>
                                <input type="text" name="coming_airline_no" class="form-control" value="{{ $returnTrip->airline_no ?? '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">From Country</label>
                                <select name="coming_from_country" class="form-select">
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['en'] }}" {{ ($returnTrip->from_country ?? '') == $country['en'] ? 'selected' : '' }}>
                                            {{ $country['en'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">To Country</label>
                                <select name="coming_to_country" class="form-select">
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['en'] }}" {{ ($returnTrip->to_country ?? '') == $country['en'] ? 'selected' : '' }}>
                                            {{ $country['en'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">PNR No</label>
                                <input type="text" name="coming_pnr" class="form-control" value="{{ $returnTrip->pnr ?? '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Departure Date & Time</label>
                                <input type="datetime-local" name="coming_departure_datetime" class="form-control"
                                    value="{{ $returnTrip && $returnTrip->departure_datetime ? date('Y-m-d\TH:i', strtotime($returnTrip->departure_datetime)) : '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Arrival Date & Time</label>
                                <input type="datetime-local" name="coming_arrival_datetime" class="form-control"
                                    value="{{ $returnTrip && $returnTrip->arrival_datetime ? date('Y-m-d\TH:i', strtotime($returnTrip->arrival_datetime)) : '' }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Baggage Qty</label>
                                <input type="number" name="coming_baggage_qty" class="form-control" value="{{ $returnTrip->baggage_qty ?? 0 }}">
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Hand Luggage Qty</label>
                                <input type="number" name="coming_handluggage_qty" class="form-control" value="{{ $returnTrip->handluggage_qty ?? 0 }}">
                            </div>
                        </div>
                    </div>

                    {{-- Round Trip Section --}}
                    <div id="round_trip_section" style="display:{{ $isRoundTrip ? 'block' : 'none' }};">
                        <h5>Round Trip Details</h5>
                        <div id="round_trip_container">
                            @if($isRoundTrip)
                                @foreach($roundTrips as $index => $trip)
                                    <div class="border p-3 mb-3">
                                        <h6>Trip #{{ $index + 1 }}</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Customer</label>
                                                <select name="round_trip_{{ $index + 1 }}_customer_id" class="form-select customer_select">
                                                    <option value="">Select Customer</option>
                                                    @foreach ($passports as $passport)
                                                        <option value="{{ $passport->id }}" 
                                                            data-passport="{{ $passport->passport_number }}"
                                                            {{ $trip->passport_id == $passport->id ? 'selected' : '' }}>
                                                            {{ $passport->first_name }} {{ $passport->second_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Passport Number</label>
                                                <input type="text" name="round_trip_{{ $index + 1 }}_passport_no" 
                                                    class="form-control passport_input" value="{{ $trip->passport_no }}" readonly>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Agent</label>
                                                <select name="round_trip_{{ $index + 1 }}_agent_id" class="form-select">
                                                    @foreach ($agents as $agent)
                                                        <option value="{{ $agent->id }}" {{ $trip->agent_id == $agent->id ? 'selected' : '' }}>
                                                            {{ $agent->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Airline / Flight</label>
                                                <input type="text" name="round_trip_{{ $index + 1 }}_airline" 
                                                    class="form-control" value="{{ $trip->airline }}">
                                            </div>
                                            
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Airline No</label>
                                                <input type="text" name="round_trip_{{ $index + 1 }}_airline_no" 
                                                    class="form-control" value="{{ $trip->airline_no }}">
                                            </div>
                                            
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">From Country</label>
                                                <select name="round_trip_{{ $index + 1 }}_from_country" class="form-select">
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country['en'] }}" {{ $trip->from_country == $country['en'] ? 'selected' : '' }}>
                                                            {{ $country['en'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">To Country</label>
                                                <select name="round_trip_{{ $index + 1 }}_to_country" class="form-select">
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country['en'] }}" {{ $trip->to_country == $country['en'] ? 'selected' : '' }}>
                                                            {{ $country['en'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">PNR No</label>
                                                <input type="text" name="round_trip_{{ $index + 1 }}_pnr" 
                                                    class="form-control" value="{{ $trip->pnr }}">
                                            </div>
                                            
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Departure Date & Time</label>
                                                <input type="datetime-local" name="round_trip_{{ $index + 1 }}_departure_datetime" 
                                                    class="form-control" value="{{ $trip->departure_datetime ? date('Y-m-d\TH:i', strtotime($trip->departure_datetime)) : '' }}">
                                            </div>
                                            
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Arrival Date & Time</label>
                                                <input type="datetime-local" name="round_trip_{{ $index + 1 }}_arrival_datetime" 
                                                    class="form-control" value="{{ $trip->arrival_datetime ? date('Y-m-d\TH:i', strtotime($trip->arrival_datetime)) : '' }}">
                                            </div>
                                            
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Baggage Qty</label>
                                                <input type="number" name="round_trip_{{ $index + 1 }}_baggage_qty" 
                                                    class="form-control" value="{{ $trip->baggage_qty }}">
                                            </div>
                                            
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Hand Luggage Qty</label>
                                                <input type="number" name="round_trip_{{ $index + 1 }}_handluggage_qty" 
                                                    class="form-control" value="{{ $trip->handluggage_qty }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-info mt-2 mb-3" id="add_round_trip_section">Add Trip</button>
                    </div>

                    {{-- Booking & Payment Section --}}
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Booking Status</label>
                            <select name="status" class="form-select">
                                <option value="Quotation" {{ $booking->status == 'Quotation' ? 'selected' : '' }}>Quotation</option>
                                <option value="Accepted" {{ $booking->status == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="Invoiced" {{ $booking->status == 'Invoiced' ? 'selected' : '' }}>Invoiced</option>
                                <option value="Partially Paid" {{ $booking->status == 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                                <option value="Paid" {{ $booking->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                                <option value="Cancelled" {{ $booking->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="unpaid" {{ $booking->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="partial" {{ $booking->payment_status == 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card border-secondary">
                                <div class="card-header bg-light">
                                    <strong>Price & Payment Details</strong>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2 row">
                                        <label class="col-sm-4 col-form-label">Currency</label>
                                        <div class="col-sm-8">
                                            <select name="currency" class="form-select">
                                                <option value="LKR" {{ $booking->currency == 'LKR' ? 'selected' : '' }}>LKR</option>
                                                <option value="USD" {{ $booking->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                                <option value="EUR" {{ $booking->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-sm-4 col-form-label">Base Price</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="base_price" value="{{ $booking->base_price }}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-sm-4 col-form-label">Additional Price</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="additional_price" value="{{ $booking->additional_price }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-sm-4 col-form-label">Discount</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="discount" value="{{ $booking->discount }}" class="form-control">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="mb-2 row">
                                        <label class="col-sm-4 col-form-label">Total</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="total_amount" value="{{ $booking->total_amount }}" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-sm-4 col-form-label">Advanced Paid</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="advanced_paid" value="{{ $booking->advanced_paid }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-sm-4 col-form-label">Balance</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="balance" value="{{ $booking->balance }}" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-end mt-3">
                    <button type="button" class="btn btn-warning" onclick="window.location='{{ route('admin.airline-bookings.index') }}'">Back</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Initialize tripIndex from existing round trips
        let tripIndex = {{ $isRoundTrip ? $roundTrips->count() : 0 }};

        document.getElementById('airlineBookingForm').addEventListener('submit', function() {
            // ONE WAY fields
            const oneWayCustomer = document.querySelector('[name="going_customer_id"]');
            const oneWayPassport = document.querySelector('[name="going_passport_no"]');

            // Copy values if exist
            if (oneWayCustomer && oneWayCustomer.value) {
                document.getElementById('final_passport_id').value = oneWayCustomer.value;
            }

            if (oneWayPassport && oneWayPassport.value) {
                document.getElementById('final_passport_no').value = oneWayPassport.value;
            }
        });

        function calculatePrice() {
            const basePrice = parseFloat(document.querySelector('[name="base_price"]').value) || 0;
            const additionalPrice = parseFloat(document.querySelector('[name="additional_price"]').value) || 0;
            const discount = parseFloat(document.querySelector('[name="discount"]').value) || 0;
            const advancedPaid = parseFloat(document.querySelector('[name="advanced_paid"]').value) || 0;

            const total = basePrice + additionalPrice - discount;
            const balance = total - advancedPaid;

            document.querySelector('[name="total_amount"]').value = total >= 0 ? total.toFixed(2) : 0;
            document.querySelector('[name="balance"]').value = balance >= 0 ? balance.toFixed(2) : 0;
        }

        // Bind calculation to inputs
        document.querySelectorAll('[name="base_price"], [name="additional_price"], [name="discount"], [name="advanced_paid"]').forEach(input => {
            input.addEventListener('input', calculatePrice);
        });

        // Initial calculation on page load
        calculatePrice();

        const goingCustomer = document.getElementById('going_customer_id');
        const goingPassport = document.getElementById('going_passport_no');

        if (goingCustomer && goingPassport) {
            goingCustomer.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const passportNumber = selectedOption.getAttribute('data-passport') || '';
                goingPassport.value = passportNumber;
            });
        }

        const businessType = document.getElementById('business_type');
        businessType.addEventListener('change', function() {
            document.getElementById('company_name_section').style.display = this.value === 'corporate' ? 'block' : 'none';
            document.getElementById('ticket_type_section').style.display = this.value ? 'block' : 'none';
            document.getElementById('one_way_section').style.display = 'none';
            document.getElementById('return_type_section').style.display = 'none';
            document.getElementById('return_ticket_section').style.display = 'none';
            document.getElementById('round_trip_section').style.display = 'none';
        });

        const ticketType = document.getElementById('ticket_type');
        ticketType.addEventListener('change', function() {
            const val = this.value;
            document.getElementById('one_way_section').style.display = val === 'one_way' ? 'block' : 'none';
            document.getElementById('return_type_section').style.display = val === 'return' ? 'block' : 'none';
            document.getElementById('return_ticket_section').style.display = 'none';
            document.getElementById('round_trip_section').style.display = 'none';
        });

        const returnType = document.getElementById('return_type');
        returnType.addEventListener('change', function() {
            const val = this.value;

            // Hide all related sections first
            document.getElementById('one_way_section').style.display = 'none';
            document.getElementById('return_ticket_section').style.display = 'none';
            document.getElementById('round_trip_section').style.display = 'none';

            // Dummy behaves like One Way
            if (val === 'dummy') {
                document.getElementById('one_way_section').style.display = 'block';
            }

            if (val === 'return_ticket') {
                document.getElementById('return_ticket_section').style.display = 'block';
            }

            if (val === 'round_trip') {
                document.getElementById('round_trip_section').style.display = 'block';
            }
        });

        // Function to bind customer -> passport autofill
        function bindPassportAutofill(selectEl) {
            selectEl.addEventListener('change', function() {
                // Find closest .row parent and then .passport_input inside it
                const row = selectEl.closest('.row');
                if (!row) return;

                const passportInput = row.querySelector('.passport_input');
                const passportNumber = selectEl.options[selectEl.selectedIndex].getAttribute('data-passport') || '';
                if (passportInput) passportInput.value = passportNumber;
            });
        }

        // Bind existing selects on page load
        document.querySelectorAll('.customer_select').forEach(bindPassportAutofill);

        // Round Trip dynamic addition
        document.getElementById('add_round_trip_section').addEventListener('click', function() {
            tripIndex++;
            const container = document.getElementById('round_trip_container');
            const html = `
    <div class="border p-3 mb-3">
        <h6>Trip #${tripIndex}</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Customer</label>
                <select name="round_trip_${tripIndex}_customer_id" class="form-select customer_select">
                    <option value="">Select Customer</option>
                    @foreach ($passports as $passport)
                    <option value="{{ $passport->id }}" data-passport="{{ $passport->passport_number }}">
                        {{ $passport->first_name }} {{ $passport->second_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Passport Number</label>
                <input type="text" name="round_trip_${tripIndex}_passport_no" class="form-control passport_input" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Agent</label>
                <select name="round_trip_${tripIndex}_agent_id" class="form-select">
                    @foreach ($agents as $agent)
                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Airline / Flight</label>
                <input type="text" name="round_trip_${tripIndex}_airline" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Airline No</label>
                <input type="text" name="round_trip_${tripIndex}_airline_no" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">From Country</label>
                <select name="round_trip_${tripIndex}_from_country" class="form-select">
                    @foreach ($countries as $country)
                    <option value="{{ $country['en'] }}">{{ $country['en'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">To Country</label>
                <select name="round_trip_${tripIndex}_to_country" class="form-select">
                    @foreach ($countries as $country)
                    <option value="{{ $country['en'] }}">{{ $country['en'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">PNR No</label>
                <input type="text" name="round_trip_${tripIndex}_pnr" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Departure Date & Time</label>
                <input type="datetime-local" name="round_trip_${tripIndex}_departure_datetime" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Arrival Date & Time</label>
                <input type="datetime-local" name="round_trip_${tripIndex}_arrival_datetime" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Baggage Qty</label>
                <input type="number" name="round_trip_${tripIndex}_baggage_qty" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Hand Luggage Qty</label>
                <input type="number" name="round_trip_${tripIndex}_handluggage_qty" class="form-control">
            </div>
        </div>
    </div>`;
            container.insertAdjacentHTML('beforeend', html);
            document.querySelectorAll('.customer_select').forEach(function(select) {
                // Avoid binding multiple times
                if (!select.dataset.bound) {
                    bindPassportAutofill(select);
                    select.dataset.bound = true; // mark as bound
                }
            });
        });
    </script>
@endsection