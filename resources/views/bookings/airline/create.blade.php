@extends('layouts.vertical', ['subtitle' => 'Create Airline Booking'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Airline Booking',
        'subtitle' => 'Create',
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Airline Booking</h5>
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
            <form id="airlineBookingForm" action="{{ route('admin.airline-bookings.store') }}" method="POST">
                @csrf
                <div class="row">
                    <input type="hidden" name="final_passport_id" id="final_passport_id">
                    <input type="hidden" name="final_passport_no" id="final_passport_no">
                    {{-- Business Type --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Business Type</label>
                        <select name="business_type" id="business_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="corporate">Corporate</option>
                            <option value="individual">Individual</option>
                        </select>
                    </div>

                    {{-- Company Name for Corporate --}}
                    <div class="col-md-3 mb-3" id="company_name_section" style="display:none;">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" placeholder="Enter Company Name">
                    </div>

                    {{-- Ticket Type --}}
                    <div class="col-md-3 mb-3" id="ticket_type_section">
                        <label class="form-label">Ticket Type</label>
                        <select name="ticket_type" id="ticket_type" class="form-select">
                            <option value="">Select Ticket Type</option>
                            <option value="one_way">One Way Ticket</option>
                            <option value="return">Return Ticket</option>
                        </select>
                    </div>

                    {{-- One Way Section --}}
                    <div id="one_way_section" style="display:none;">
                        <h5>One Way Ticket Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer</label>
                                <select name="going_customer_id" class="form-select customer_select">
                                    <option value="">Select Customer</option>
                                    @foreach ($passports as $passport)
                                        <option value="{{ $passport->id }}"
                                            data-passport="{{ $passport->passport_number }}">
                                            {{ $passport->first_name }} {{ $passport->second_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Passport Number</label>
                                <input type="text" name="going_passport_no" class="form-control passport_input" readonly>

                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Agent</label>
                                <select name="oneway_agent_id" class="form-select">
                                    <option value="">Select Agent</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }} -
                                            {{ $agent->company_name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Airline / Flight</label>
                                <input type="text" name="oneway_airline" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Airline No</label>
                                <input type="text" name="oneway_airline_no" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">From Airport</label>
                                <select name="oneway_from_airport" class="form-select ">
                                    <option value="">Select Airport</option>
                                    @foreach ($airports as $airport)
                                        <option value="{{ $airport['code'] }}">
                                            {{ $airport['code'] }} - {{ $airport['name'] }} - {{ $airport['country'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">To Airport</label>
                                <select name="oneway_to_airport" class="form-select">
                                    <option value="">Select Airport</option>
                                    @foreach ($airports as $airport)
                                        <option value="{{ $airport['code'] }}">
                                            {{ $airport['code'] }} - {{ $airport['name'] }} - {{ $airport['country'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">PNR No</label>
                                <input type="text" name="oneway_pnr" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Departure Date & Time</label>
                                <input type="datetime-local" name="oneway_departure_datetime" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Arrival Date & Time</label>
                                <input type="datetime-local" name="oneway_arrival_datetime" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Baggage Qty</label>
                                <input type="number" name="oneway_baggage_qty" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Hand Luggage Qty</label>
                                <input type="number" name="oneway_handluggage_qty" class="form-control">
                            </div>

                        </div>
                    </div>

                    {{-- Return Type Section --}}
                    <div id="return_type_section" style="display:none;">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Return Type</label>
                            <select name="return_type" id="return_type" class="form-select">
                                <option value="">Select Return Type</option>
                                <option value="dummy">Dummy</option>
                                <option value="return_ticket">Return Ticket</option>
                                <option value="round_trip">Round Trip</option>
                            </select>
                        </div>
                    </div>

                    {{-- Return Ticket Sections --}}
                    <div id="return_ticket_section" style="display:none;">
                        <h5>Going Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer</label>
                                <select name="return_going_customer_id" class="form-select customer_select">
                                    <option value="">Select Customer</option>
                                    @foreach ($passports as $passport)
                                        <option value="{{ $passport->id }}"
                                            data-passport="{{ $passport->passport_number }}">
                                            {{ $passport->first_name }} {{ $passport->second_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Passport Number</label>
                                <input type="text" name="return_going_passport_no" class="form-control passport_input"
                                    readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Agent</label>
                                <select name="going_agent_id" class="form-select">
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }} -
                                            {{ $agent->company_name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Airline / Flight</label>
                                <input type="text" name="going_airline" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Airline No</label>
                                <input type="text" name="going_airline_no" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">From Airport</label>
                                <select name="going_from_airport" class="form-select">
                                    <option value="">Select Airport</option>
                                    @foreach ($airports as $airport)
                                        <option value="{{ $airport['code'] }}">
                                            {{ $airport['code'] }} - {{ $airport['name'] }} - {{ $airport['country'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">To Airport</label>
                                <select name="going_to_airport" class="form-select">
                                    <option value="">Select Airport</option>
                                    @foreach ($airports as $airport)
                                        <option value="{{ $airport['code'] }}">
                                            {{ $airport['code'] }} - {{ $airport['name'] }} - {{ $airport['country'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">PNR No</label>
                                <input type="text" name="going_pnr" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Departure Date & Time</label>
                                <input type="datetime-local" name="going_departure_datetime" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Arrival Date & Time</label>
                                <input type="datetime-local" name="going_arrival_datetime" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Baggage Qty</label>
                                <input type="number" name="going_baggage_qty" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Hand Luggage Qty</label>
                                <input type="number" name="going_handluggage_qty" class="form-control">
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
                                            data-passport="{{ $passport->passport_number }}">
                                            {{ $passport->first_name }} {{ $passport->second_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Passport Number</label>
                                <input type="text" name="going_passport_no" id="going_passport_no"
                                    class="form-control" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Agent</label>
                                <select name="coming_agent_id" class="form-select">
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }} -
                                            {{ $agent->company_name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Airline / Flight</label>
                                <input type="text" name="coming_airline" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Airline No</label>
                                <input type="text" name="coming_airline_no" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">From Airport</label>
                                <select name="coming_from_airport" class="form-select">
                                    <option value="">Select Airport</option>
                                    @foreach ($airports as $airport)
                                        <option value="{{ $airport['code'] }}">
                                            {{ $airport['code'] }} - {{ $airport['name'] }} - {{ $airport['country'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">To Airport</label>
                                <select name="coming_to_airport" class="form-select">
                                    <option value="">Select Airport</option>
                                    @foreach ($airports as $airport)
                                        <option value="{{ $airport['code'] }}">
                                            {{ $airport['code'] }} - {{ $airport['name'] }} - {{ $airport['country'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">PNR No</label>
                                <input type="text" name="coming_pnr" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Departure Date & Time</label>
                                <input type="datetime-local" name="coming_departure_datetime" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Arrival Date & Time</label>
                                <input type="datetime-local" name="coming_arrival_datetime" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Baggage Qty</label>
                                <input type="number" name="coming_baggage_qty" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Hand Luggage Qty</label>
                                <input type="number" name="coming_handluggage_qty" class="form-control">
                            </div>

                        </div>
                    </div>

                    {{-- Round Trip Section --}}
                    <div id="round_trip_section" style="display:none;">
                        <h5>Round Trip Details</h5>
                        <div id="round_trip_container"></div>
                        <button type="button" class="btn btn-sm btn-info mt-2 mb-3" id="add_round_trip_section">Add
                            Trip</button>
                    </div>

                    {{-- Booking & Payment Section --}}

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Booking Status</label>
                            <select name="status" class="form-select">
                                <option value="Quotation" selected>Quotation</option>
                                <option value="Accepted">Accepted</option>
                                <option value="Invoiced">Invoiced</option>
                                <option value="Partially Paid">Partially Paid</option>
                                <option value="Paid">Paid</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="unpaid">Unpaid</option>
                                <option value="partial">Partial</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="published_at" class="form-label">Published Date</label>
                            <input type="date" name="published_at" id="published_at" class="form-control"
                                value="{{ old('published_at', now()->toDateString()) }}">
                        </div>


                        <div class="col-md-6 mb-3">
                            <label class="form-label">Note</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Enter any additional note"></textarea>
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
                                                <option value="LKR" selected>LKR</option>
                                                <option value="USD">USD</option>
                                                <option value="EUR">EUR</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-sm-4 col-form-label">Base Price</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="base_price" value="0" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-sm-4 col-form-label">Additional Price</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="additional_price" value="0"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-sm-4 col-form-label">Discount</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="discount" value="0" class="form-control">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="mb-2 row">
                                        <label class="col-sm-4 col-form-label">Total</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="total_amount" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-sm-4 col-form-label">Advanced Paid</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="advanced_paid" value="0"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-2 row">
                                        <label class="col-sm-4 col-form-label">Balance</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="balance" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="text-end mt-3">
                    <button type="button" class="btn btn-warning"
                        onclick="window.location='{{ route('admin.airline-bookings.index') }}'">Back</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
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
        document.querySelectorAll(
            '[name="base_price"], [name="additional_price"], [name="discount"], [name="advanced_paid"]'
        ).forEach(input => {
            input.addEventListener('input', calculatePrice);
        });

        // Initial calculation on page load
        calculatePrice();

        const goingCustomer = document.getElementById('going_customer_id');
        const goingPassport = document.getElementById('going_passport_no');

        goingCustomer.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const passportNumber = selectedOption.getAttribute('data-passport') || '';
            goingPassport.value = passportNumber;
        });

        const businessType = document.getElementById('business_type');
        businessType.addEventListener('change', function() {
            document.getElementById('company_name_section').style.display = this.value === 'corporate' ? 'block' :
                'none';
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

        // For dynamically added selects (round trip)
        function bindNewRoundTripPassport(container) {
            container.querySelectorAll('.customer_select').forEach(function(select) {
                // Remove previous listeners to prevent duplicates
                select.replaceWith(select.cloneNode(true));
                bindPassportAutofill(container.querySelector('.customer_select:last-child'));
            });
        }

        // Bind for all existing selects on page load
        document.querySelectorAll('.customer_select').forEach(bindPassportAutofill);


        // Round Trip dynamic addition
        let tripIndex = 0;
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
                    <option value="{{ $agent->id }}">{{ $agent->name }} - {{ $agent->company_name ?? '' }}</option>
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
    <label class="form-label">From Airport</label>
    <select name="round_trip_${tripIndex}_from_airport" class="form-select">
        @foreach ($airports as $airport)
        <option value="{{ $airport['code'] }}">{{ $airport['code'] }}- {{ $airport['name'] }} - {{ $airport['country'] }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-3 mb-3">
    <label class="form-label">To Airport</label>
    <select name="round_trip_${tripIndex}_to_airport" class="form-select">
        @foreach ($airports as $airport)
        <option value="{{ $airport['code'] }}">{{ $airport['code'] }}- {{ $airport['name'] }} - {{ $airport['country'] }}</option>
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
