@extends('layouts.vertical', ['subtitle' => 'Edit Tour Package'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Tour Packages', 'subtitle' => 'Edit'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Tour Package</h5>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Main Info --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="heading" class="form-label">Heading</label>
                        <input type="text" name="heading" id="heading" class="form-control"
                            value="{{ old('heading', $package->heading) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tour_ref_no" class="form-label">Reference No</label>
                        <input type="text" name="tour_ref_no" id="tour_ref_no" class="form-control"
                            value="{{ old('tour_ref_no', $package->tour_ref_no) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $package->description) }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="summary_description" class="form-label">Summary Description</label>
                        <textarea name="summary_description" id="summary_description" class="form-control">{{ old('summary_description', $package->summary_description) }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" name="country" id="country" class="form-control"
                            value="{{ old('country', $package->country_name) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="place" class="form-label">Place</label>
                        <input type="text" name="place" id="place" class="form-control"
                            value="{{ old('place', $package->place) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="inbound" {{ $package->type == 'inbound' ? 'selected' : '' }}>Inbound</option>
                            <option value="outbound" {{ $package->type == 'outbound' ? 'selected' : '' }}>Outbound</option>
                        </select>
                    </div>
                </div>

                {{-- Category, Days, Nights --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="special" {{ $package->tour_category == 'special' ? 'selected' : '' }}>Special
                            </option>
                            <option value="city" {{ $package->tour_category == 'city' ? 'selected' : '' }}>City</option>
                            <option value="tailor" {{ $package->tour_category == 'tailor' ? 'selected' : '' }}>Tailor Made
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="days" class="form-label">Days</label>
                        <input type="number" name="days" id="days" class="form-control"
                            value="{{ old('days', $package->days) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="nights" class="form-label">Nights</label>
                        <input type="number" name="nights" id="nights" class="form-control"
                            value="{{ old('nights', $package->nights) }}">
                    </div>
                </div>

                {{-- Ratings, Status, Price --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="ratings" class="form-label">Ratings</label>
                        <input type="number" step="0.1" min="0" max="5" name="ratings"
                            class="form-control" value="{{ old('ratings', $package->ratings) }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ $package->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $package->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Price (USD)</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control"
                            value="{{ old('price', $package->price) }}">
                    </div>
                </div>

                {{-- Main Picture --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="main_picture" class="form-label">Main Picture</label>
                        <input type="file" name="main_picture" id="main_picture" class="form-control">
                        @if ($package->picture)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $package->picture) }}" width="120"
                                    class="rounded shadow">
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="map_image" class="form-label">Map Image</label>
                        <input type="file" name="map_image" id="map_image" class="form-control">
                        @if ($package->map_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $package->map_image) }}" width="120"
                                    class="rounded shadow">
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tour Summaries --}}
                <div class="card my-4">
                    <div class="card-header d-flex justify-content-between">
                        <span>Tour Summaries</span>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addSummary()">+ Add
                            Summary</button>
                    </div>
                    <div class="card-body" id="summaryWrapper">
                        @foreach ($package->summaries as $i => $summary)
                            <div class="row mb-2" id="summary-{{ $i }}">
                                <div class="col-md-3">
                                    <input name="tour_summaries[{{ $i }}][city]" class="form-control"
                                        value="{{ $summary->city }}">
                                </div>
                                <div class="col-md-3">
                                    <input name="tour_summaries[{{ $i }}][theme]" class="form-control"
                                        value="{{ $summary->theme }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="removeElement('summary-{{ $i }}')">Remove</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Itineraries --}}
                <div class="card my-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Itineraries</span>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addItinerary()">+ Add
                            Itinerary</button>
                    </div>
                    <div class="card-body" id="itineraryWrapper">
                        @foreach ($package->itineraries as $i => $itinerary)
                            <div class="border p-3 mb-3 rounded" id="itinerary-{{ $i }}">
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label>Destination</label>
                                        <select name="itineraries[{{ $i }}][place_id]" class="form-select"
                                            onchange="fetchProgramPoints(this, {{ $i }})">
                                            <option value="">-- Select Destination --</option>
                                            @foreach ($destinations as $d)
                                                <option value="{{ $d->id }}"
                                                    {{ trim(strtolower($itinerary->place_name)) == trim(strtolower($d->name)) ? 'selected' : '' }}>
                                                    {{ $d->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <input type="hidden" name="itineraries[{{ $i }}][existing_image]"
                                        value="{{ $itinerary->pictures }}">


                                    <div class="col-md-2">
                                        <label>Day</label>
                                        <input type="number" name="itineraries[{{ $i }}][day]"
                                            class="form-control" value="{{ $itinerary->day }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Picture</label>
                                        <input type="file" name="itineraries[{{ $i }}][pictures]"
                                            class="form-control">
                                        @if ($itinerary->pictures)
                                            <img src="{{ asset('storage/' . $itinerary->pictures) }}" width="100"
                                                class="mt-2 rounded">
                                        @endif
                                    </div>

                                    <div class="col-md-3">
                                        <label>Description</label>
                                        <input name="itineraries[{{ $i }}][description]" class="form-control"
                                            value="{{ $itinerary->description }}">
                                    </div>

                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="removeElement('itinerary-{{ $i }}')">Remove</button>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-4">

                                        <div id="programWrapper{{ $i }}">
                                            <label><strong>Program Points</strong></label>
                                            @php
                                                $programPoints = is_string($itinerary->program_points)
                                                    ? json_decode($itinerary->program_points, true) ?? []
                                                    : $itinerary->program_points ?? [];
                                            @endphp

                                            @if (!empty($programPoints))
                                                @foreach ($programPoints as $pIndex => $point)
                                                    <div class="mb-2 d-flex gap-2 align-items-center"
                                                        id="program-{{ $loop->index }}">
                                                        <input type="text"
                                                            name="itineraries[{{ $loop->parent->index }}][program_points][]"
                                                            class="form-control" value="{{ $point }}" readonly>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="removeElement('program-{{ $loop->index }}')">X</button>
                                                    </div>
                                                @endforeach
                                            @endif
                                            <button type="button" class="btn btn-sm btn-secondary mt-2"
                                                onclick="addProgramPoint({{ $i }})">+ Add Program
                                                Point</button>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Overnight Stay</label>
                                        <select name="itineraries[{{ $i }}][overnight_stay]"
                                            class="form-select">
                                            <option value="">-- Select Hotel --</option>
                                            @foreach ($hotels as $h)
                                                <option value="{{ $h->hotel_name }}"
                                                    {{ $itinerary->overnight_stay == $h->hotel_name ? 'selected' : '' }}>
                                                    {{ $h->hotel_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label>Meal Plan</label>
                                        <input name="itineraries[{{ $i }}][meal_plan]" class="form-control"
                                            value="{{ $itinerary->meal_plan }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label>Travel Time</label>
                                        <input name="itineraries[{{ $i }}][approximate_travel_time]"
                                            class="form-control" value="{{ $itinerary->approximate_travel_time }}">
                                    </div>
                                </div>

                                {{-- Highlights --}}
                                <div id="highlightWrapper{{ $i }}" class="mt-3">
                                    <label><strong>Highlights</strong></label>
                                    @foreach ($itinerary->highlights as $hIndex => $highlight)
                                        <div class="row mb-2 border p-2 rounded align-items-center"
                                            id="highlight-{{ $i }}-{{ $hIndex }}">
                                            <div class="col-md-4">
                                                <input
                                                    name="itineraries[{{ $i }}][highlights][{{ $hIndex }}][highlight_places]"
                                                    class="form-control" value="{{ $highlight->highlight_places }}">
                                            </div>
                                            <div class="col-md-4">
                                                <input
                                                    name="itineraries[{{ $i }}][highlights][{{ $hIndex }}][description]"
                                                    class="form-control" value="{{ $highlight->description }}">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="file"
                                                    name="itineraries[{{ $i }}][highlights][{{ $hIndex }}][images]"
                                                    class="form-control">
                                                @if ($highlight->images)
                                                    {{-- ADD THIS HIDDEN FIELD TO RETAIN EXISTING IMAGE PATH --}}
                                                    <input type="hidden"
                                                        name="itineraries[{{ $i }}][highlights][{{ $hIndex }}][existing_image]"
                                                        value="{{ $highlight->images }}">
                                                    <img src="{{ asset('storage/' . $highlight->images) }}"
                                                        width="80" class="mt-2 rounded">
                                                @endif

                                                <input type="hidden"
                                                    name="itineraries[{{ $i }}][highlights][{{ $hIndex }}][existing_image]"
                                                    value="{{ $highlight->images }}">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center">
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="removeElement('highlight-{{ $i }}-{{ $hIndex }}')">X</button>
                                            </div>
                                        </div>
                                    @endforeach

                                    <button type="button" class="btn btn-sm btn-secondary mt-2"
                                        onclick="addHighlight({{ $i }})">+ Add Highlight</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card my-4">
                    <div class="card-header">
                        <h5 class="mb-0">Vehicle Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="vehicleSelect" class="form-label">Select Vehicle</label>
                                <select id="vehicleSelect" name="vehicle_id" class="form-select"
                                    onchange="populateVehicleDetails()">
                                    <option value="">-- Select Vehicle --</option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}"
                                            @if (isset($packageVehicle) && $packageVehicle->make === $vehicle->make && $packageVehicle->model === $vehicle->model) selected @endif>
                                            {{ $vehicle->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- The vehicle details section will initially be hidden, but visible if a vehicle is pre-selected by JS --}}
                        <div id="vehicleDetails" style="display: none;">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Make</label>
                                    <input type="text" id="vehicleMake" name="vehicle_make" class="form-control"
                                        readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Model</label>
                                    <input type="text" id="vehicleModel" name="vehicle_model" class="form-control"
                                        readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Seats</label>
                                    <input type="text" id="vehicleSeats" name="vehicle_seats" class="form-control"
                                        readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Air Conditioned</label>
                                    <input type="text" id="vehicleAirConditioned" name="vehicle_air_conditioned"
                                        class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Condition</label>
                                    <input type="text" id="vehicleCondition" name="vehicle_condition"
                                        class="form-control" readonly>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Vehicle Image</label>
                                    <div>
                                        <img id="vehicleImage" src="" alt="Vehicle Image"
                                            class="img-fluid rounded border" style="max-height: 200px; display: none;">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                {{-- Submit --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Update Package</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        // 1. JSON-encode the list of all available vehicles
        const vehicles = @json($vehicles);

        function populateVehicleDetails() {
            const select = document.getElementById('vehicleSelect');
            const vehicleId = select.value;
            // Find the selected vehicle object in the JSON array
            const vehicle = vehicles.find(v => v.id == vehicleId);

            const detailsDiv = document.getElementById('vehicleDetails');
            const imageElement = document.getElementById('vehicleImage');

            if (vehicle) {
                // Populate all the read-only input fields
                document.getElementById('vehicleMake').value = vehicle.make ?? '';
                document.getElementById('vehicleModel').value = vehicle.model ?? '';
                document.getElementById('vehicleSeats').value = vehicle.seats ?? '';

                // Convert boolean/integer to 'Yes' or 'No'
                document.getElementById('vehicleAirConditioned').value = vehicle.air_conditioned ? 'Yes' : 'No';

                document.getElementById('vehicleCondition').value = vehicle.condition ?? '';

                // Handle image display
                if (vehicle.vehicle_image) {
                    // IMPORTANT: Ensure the path `/storage/` is correct for your file system setup
                    imageElement.src = `/storage/${vehicle.vehicle_image}`;
                    imageElement.style.display = 'block';
                } else {
                    imageElement.src = '';
                    imageElement.style.display = 'none';
                }

                // Show the details section
                detailsDiv.style.display = 'block';
            } else {
                // Hide the details section if no vehicle is selected
                detailsDiv.style.display = 'none';
            }
        }

        // 2. Call the function on page load to display details of the pre-selected vehicle
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('vehicleSelect');
            // If the select box has a value (meaning the Blade logic pre-selected one)
            if (select.value) {
                populateVehicleDetails();
            }
        });

        // ======= HOTEL LIST (from backend) =======
        const hotels = @json(
            $hotels->map(fn($h) => [
                    'id' => $h->id,
                    'hotel_name' => $h->hotel_name,
                ]));

        // ======= DESTINATIONS (used for dropdowns) =======
        const destinations = @json($destinations->map(fn($d) => ['id' => $d->id, 'name' => $d->name]));

        // ======= FETCH PROGRAM POINTS & HIGHLIGHTS BASED ON DESTINATION =======
        function fetchProgramPoints(select, index) {
            const destinationId = select.value;
            if (!destinationId) return;

            fetch(`/admin/destinations/${destinationId}/details`)
                .then(res => res.json())
                .then(data => {
                    // === PROGRAM POINTS ===
                    const programWrapper = document.getElementById(`programWrapper${index}`);
                    programWrapper.innerHTML = `<label><strong>Program Points</strong></label>`;

                    if (data.program_points?.length) {
                        data.program_points.forEach((p, i) => {
                            const pid = `program-${index}-${i}`;
                            programWrapper.insertAdjacentHTML("beforeend", `
                            <div class="mb-2 d-flex gap-2 align-items-center" id="${pid}">
                                <input type="text" 
                                    name="itineraries[${index}][program_points][]" 
                                    class="form-control" 
                                    value="${p.point}" readonly>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeElement('${pid}')">X</button>
                            </div>
                        `);
                        });
                    } else {
                        programWrapper.insertAdjacentHTML("beforeend",
                            `<p class="text-muted">No program points found</p>`);
                    }

                    programWrapper.insertAdjacentHTML("beforeend", `
                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addProgramPoint(${index})">
                        + Add Program Point
                    </button>
                `);

                    // === HIGHLIGHTS FIX ===
                    const highlightWrapper = document.getElementById(`highlightWrapper${index}`);
                    // Find and remove all previously fetched highlights (those without file inputs)
                    // We use a temporary wrapper to store the manually added highlights/button.
                    const tempWrapper = document.createElement('div');
                    tempWrapper.innerHTML = highlightWrapper.innerHTML;

                    // Remove the dynamically added highlights from the previous selection, if any.
                    // In the original code, the fetched highlights were read-only and had hidden image paths.
                    // However, the simplest fix is to store the manually added elements (which have the file input) 
                    // and the button, then re-insert them.

                    // Get the manual 'Add Highlight' button (it's always the last element)
                    const addButton = highlightWrapper.querySelector('button[onclick^="addHighlight"]');

                    // Find the index to start counting new highlights from.
                    let highlightCounter = highlightCounters[index] || 0;

                    let fetchedHighlightsHtml = '';

                    if (data.highlights?.length) {
                        // Remove the "No highlights found" message if it exists
                        const noHighlights = highlightWrapper.querySelector('.text-muted');
                        if (noHighlights) noHighlights.remove();

                        data.highlights.forEach((h) => {
                            const hid =
                                `highlight-${index}-${highlightCounter}`; // Use the counter for a unique ID and correct array index

                            fetchedHighlightsHtml += `
                    <div class="row mb-2 border p-2 rounded align-items-center bg-light" id="${hid}">
                        <div class="col-md-4">
                            <input name="itineraries[${index}][highlights][${highlightCounter}][highlight_places]" 
                                class="form-control" value="${h.place_name}" readonly>
                        </div>
                        <div class="col-md-4">
                            <input name="itineraries[${index}][highlights][${highlightCounter}][description]" 
                                class="form-control" value="${h.description}" readonly>
                        </div>
                        <div class="col-md-3">
                            ${h.image ? `
                                                                    <input type="hidden" name="itineraries[${index}][highlights][${highlightCounter}][existing_image]" value="${h.image}">
                                                                    <img src="/storage/${h.image}" class="img-fluid rounded" style="max-height:60px;">
                                                                ` : ''}
                            <input type="file" 
                                name="itineraries[${index}][highlights][${highlightCounter}][images]" 
                                class="form-control">
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeElement('${hid}')">X</button>
                        </div>
                    </div>
                    `;
                            highlightCounter++;
                        });
                        highlightCounters[index] = highlightCounter; // Update the counter after adding fetched items
                    } else {
                        fetchedHighlightsHtml =
                            `<p class="text-muted">No default highlights found for this destination</p>`;
                    }

                    // Temporarily clear the dynamically added highlights, but keep the header and manual additions

                    // 1. Get the current HTML
                    const currentHTML = highlightWrapper.innerHTML;
                    // 2. Clear the wrapper entirely
                    highlightWrapper.innerHTML = `<label><strong>Highlights</strong></label>`;
                    // 3. Re-insert the fetched content
                    highlightWrapper.insertAdjacentHTML("beforeend", fetchedHighlightsHtml);

                    // 4. Re-append the existing manual button (and any manually added highlight rows)
                    highlightWrapper.insertAdjacentHTML("beforeend", `
                <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addHighlight(${index})">
                    + Add Highlight
                </button>
            `);
                });
        }

        // ======= SUMMARY & ITINERARY INDEX TRACKERS =======
        let summaryIndex = document.querySelectorAll('#summaryWrapper > .row').length || 0;
        let itineraryIndex = document.querySelectorAll('#itineraryWrapper > .border').length || 0;
        let highlightCounters = {}; // track highlights per itinerary

        // Initialize highlight counters for existing itineraries
        document.querySelectorAll('#itineraryWrapper > .border').forEach((itineraryEl, i) => {
            const highlightCount = itineraryEl.querySelectorAll('[id^="highlight-"]').length;
            highlightCounters[i] = highlightCount;
        });
        // ======= ADD SUMMARY ROW =======
        function addSummary() {
            const wrapper = document.getElementById("summaryWrapper");
            const id = `summary-${summaryIndex}`;

            const options = destinations.map(d => `<option value="${d.name}">${d.name}</option>`).join('');

            wrapper.insertAdjacentHTML("beforeend", `
            <div class="row mb-2 align-items-center" id="${id}">
                <div class="col-md-3">
                    <select name="tour_summaries[${summaryIndex}][city]" class="form-select">
                        <option value="">-- Select Destination --</option>
                        ${options}
                    </select>
                </div>
                <div class="col-md-3">
                    <input name="tour_summaries[${summaryIndex}][theme]" class="form-control" placeholder="Theme">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeElement('${id}')">Remove</button>
                </div>
            </div>
        `);
            summaryIndex++;
        }

        // ======= ADD ITINERARY BLOCK =======
        function addItinerary() {
            const wrapper = document.getElementById("itineraryWrapper");
            const id = `itinerary-${itineraryIndex}`;
            const destinationOptions = destinations.map(d => `<option value="${d.id}">${d.name}</option>`).join('');
            const hotelOptions = hotels.map(h => `<option value="${h.hotel_name}">${h.hotel_name}</option>`).join('');

            wrapper.insertAdjacentHTML("beforeend", `
            <div class="border p-3 mb-3 rounded" id="${id}">
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label>Destination</label>
                        <select name="itineraries[${itineraryIndex}][place_id]" class="form-select" onchange="fetchProgramPoints(this, ${itineraryIndex})">
                            <option value="">-- Select Destination --</option>
                            ${destinationOptions}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Day</label>
                        <input type="number" name="itineraries[${itineraryIndex}][day]" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Picture</label>
                        <input type="file" name="itineraries[${itineraryIndex}][pictures]" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Description</label>
                        <input name="itineraries[${itineraryIndex}][description]" class="form-control">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeElement('${id}')">Remove</button>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <div id="programWrapper${itineraryIndex}">
                            <label><strong>Program Points</strong></label>
                            <button type="button" class="btn btn-sm btn-secondary mb-2" onclick="addProgramPoint(${itineraryIndex})">+ Add Program Point</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Overnight Stay</label>
                        <select name="itineraries[${itineraryIndex}][overnight_stay]" class="form-select">
                            <option value="">-- Select Hotel --</option>
                            ${hotelOptions}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Meal Plan</label>
                        <input name="itineraries[${itineraryIndex}][meal_plan]" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>Travel Time</label>
                        <input name="itineraries[${itineraryIndex}][approximate_travel_time]" class="form-control">
                    </div>
                </div>

                <div id="highlightWrapper${itineraryIndex}" class="mt-3">
                    <label><strong>Highlights</strong></label>
                    <button type="button" class="btn btn-sm btn-secondary mb-2" onclick="addHighlight(${itineraryIndex})">+ Add Highlight</button>
                </div>
            </div>
        `);

            highlightCounters[itineraryIndex] = 0; // initialize highlight counter for this itinerary
            itineraryIndex++;
        }

        // ======= ADD HIGHLIGHT ROW =======
        function addHighlight(itineraryIdx) {
            if (!highlightCounters[itineraryIdx]) highlightCounters[itineraryIdx] = 0;
            const highlightIdx = highlightCounters[itineraryIdx]++;
            const wrapper = document.getElementById(`highlightWrapper${itineraryIdx}`);
            const id = `highlight-${itineraryIdx}-${highlightIdx}`;

            // Find the 'Add Highlight' button and insert before it
            const addButton = wrapper.querySelector('button[onclick^="addHighlight"]');

            const newHighlightHtml = `
    <div class="row mb-2 border p-2 rounded align-items-center" id="${id}">
        <div class="col-md-4">
            <input name="itineraries[${itineraryIdx}][highlights][${highlightIdx}][highlight_places]" class="form-control" placeholder="Place Name">
        </div>
        <div class="col-md-4">
            <input name="itineraries[${itineraryIdx}][highlights][${highlightIdx}][description]" class="form-control" placeholder="Description">
        </div>
        <div class="col-md-3">
            {{-- ADDED: Hidden field to ensure persistence if no file is uploaded on save --}}
            <input type="hidden" name="itineraries[${itineraryIdx}][highlights][${highlightIdx}][existing_image]" value="">
            <input type="file" name="itineraries[${itineraryIdx}][highlights][${highlightIdx}][images]" class="form-control">
        </div>
        <div class="col-md-1 d-flex align-items-center">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeElement('${id}')">X</button>
        </div>
    </div>
    `;

            // Insert the new highlight before the button
            addButton.insertAdjacentHTML('beforebegin', newHighlightHtml);
        }

        // ======= ADD PROGRAM POINT =======
        function addProgramPoint(index) {
            const wrapper = document.getElementById(`programWrapper${index}`);
            const id = `program-${index}-${Date.now()}`;
            wrapper.insertAdjacentHTML("beforeend", `
            <div class="mb-2 d-flex gap-2 align-items-center" id="${id}">
                <input name="itineraries[${index}][program_points][]" class="form-control" placeholder="Enter program point">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeElement('${id}')">X</button>
            </div>
        `);
        }

        // ======= REMOVE ELEMENT HELPER =======
        function removeElement(id) {
            const el = document.getElementById(id);
            if (el) el.remove();
        }

        // ======= AUTO-HIDE ALERTS =======
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.classList.remove('show');
                alert.classList.add('hide');
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);
    </script>
@endsection
