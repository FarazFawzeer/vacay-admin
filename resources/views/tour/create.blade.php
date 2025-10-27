@extends('layouts.vertical', ['subtitle' => 'Create Tour Package'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Tour Packages', 'subtitle' => 'Create'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">New Tour Package</h5>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif


            <form id="packageForm" action="{{ route('admin.packages.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                {{-- Main Info --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="heading" class="form-label">Heading</label>
                        <input type="text" name="heading" id="heading" class="form-control"
                            placeholder="e.g., Explore Sri Lanka in 7 Days" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tour_ref_no" class="form-label">Reference No</label>
                        <input type="text" name="tour_ref_no" id="tour_ref_no" class="form-control"
                            placeholder="e.g., SLT-001" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control"
                            placeholder="Detailed package description with itinerary overview"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="summary_description" class="form-label">Summary Description</label>
                        <textarea name="summary_description" id="summary_description" class="form-control"
                            placeholder="Short 2–3 line summary for quick view"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" name="country" id="country" class="form-control"
                            placeholder="e.g., Sri Lanka">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="place" class="form-label">Place</label>
                        <input type="text" name="place" id="place" class="form-control"
                            placeholder="e.g., Kandy, Colombo">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">-- Select Tour Type --</option>
                            <option value="inbound">Inbound</option>
                            <option value="outbound">Outbound</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">-- Select Category --</option>
                            <option value="special">Special</option>
                            <option value="city">City</option>
                            <option value="tailor">Tailor Made</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="days" class="form-label">Days</label>
                        <input type="number" name="days" id="days" class="form-control" placeholder="e.g., 7">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="nights" class="form-label">Nights</label>
                        <input type="number" name="nights" id="nights" class="form-control" placeholder="e.g., 6">
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="hilight_show_hide"
                                id="hilight_show_hide" value="1">
                            <label class="form-check-label" for="hilight_show_hide">
                                Highlight Show
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="ratings" class="form-label">Ratings</label>
                        <input type="number" step="0.1" min="0" max="5" name="ratings"
                            id="ratings" class="form-control" placeholder="e.g., 4.5">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Price (USD)</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control"
                            placeholder="e.g., 1200.00">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="main_picture" class="form-label">Main Picture</label>
                        <input type="file" name="main_picture" id="main_picture" class="form-control"
                            placeholder="Upload main image">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="map_image" class="form-label">Map Image</label>
                        <input type="file" name="map_image" id="map_image" class="form-control"
                            placeholder="Upload route map">
                    </div>
                </div>

                {{-- Tour Summaries --}}
                <div class="card my-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Tour Summaries</span>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addSummary()">+ Add
                            Summary</button>
                    </div>
                    <div class="card-body" id="summaryWrapper"></div>
                </div>

                {{-- Itineraries --}}
                <div class="card my-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Itineraries</span>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addItinerary()">+ Add
                            Itinerary</button>
                    </div>
                    <div class="card-body" id="itineraryWrapper"></div>
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
                                        <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

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

                            <div class="row mt-3" id="subImagesSection" style="display: none;">
                                <div class="col-md-12">
                                    <label class="form-label">Sub Images</label>
                                    <div id="vehicleSubImages" class="d-flex flex-wrap gap-2"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Create Package</button>
                </div>
            </form>

        </div>
    </div>

    {{-- Scripts --}}
    <script>
        const hotels = @json(
            $hotels->map(fn($h) => [
                    'id' => $h->id,
                    'hotel_name' => $h->hotel_name,
                ]));
    </script>


    <script>
        const vehicles = @json($vehicles);

        function populateVehicleDetails() {
            const select = document.getElementById('vehicleSelect');
            const vehicleId = select.value;
            const vehicle = vehicles.find(v => v.id == vehicleId);

            const detailsDiv = document.getElementById('vehicleDetails');
            const imageElement = document.getElementById('vehicleImage');
            const subImagesSection = document.getElementById('subImagesSection');
            const subImagesContainer = document.getElementById('vehicleSubImages');

            if (vehicle) {
                document.getElementById('vehicleMake').value = vehicle.make ?? '';
                document.getElementById('vehicleModel').value = vehicle.model ?? '';
                document.getElementById('vehicleSeats').value = vehicle.seats ?? '';
                document.getElementById('vehicleAirConditioned').value = vehicle.air_conditioned ? 'Yes' : 'No';
                document.getElementById('vehicleCondition').value = vehicle.condition ?? '';

                // Show main image
                if (vehicle.vehicle_image) {
                    imageElement.src = `/storage/${vehicle.vehicle_image}`;
                    imageElement.style.display = 'block';
                } else {
                    imageElement.style.display = 'none';
                }

                // ✅ Show sub images only if type = car or van
                if (vehicle.type === 'car' || vehicle.type === 'van') {
                    if (vehicle.sub_image && Array.isArray(vehicle.sub_image) && vehicle.sub_image.length > 0) {
                        subImagesContainer.innerHTML = '';
                        vehicle.sub_image.forEach(img => {
                            subImagesContainer.insertAdjacentHTML('beforeend', `
                        <img src="/storage/${img}" 
                             class="rounded border" 
                             style="width:100px;height:100px;object-fit:cover;">
                    `);
                        });
                        subImagesSection.style.display = 'block';
                    } else {
                        subImagesContainer.innerHTML = '<p class="text-muted">No sub images available</p>';
                        subImagesSection.style.display = 'block';
                    }
                } else {
                    subImagesSection.style.display = 'none';
                }

                detailsDiv.style.display = 'block';
            } else {
                detailsDiv.style.display = 'none';
                subImagesSection.style.display = 'none';
            }
        }


        function fetchProgramPoints(select, index) {
            const destinationId = select.value;
            if (!destinationId) return;

            fetch(`/admin/destinations/${destinationId}/details`)
                .then(res => res.json())
                .then(data => {
                    // === PROGRAM POINTS ===
                    const programWrapper = document.getElementById(`programWrapper${index}`);
                    programWrapper.innerHTML = `<label><strong>Program Points</strong></label>`;

                    if (data.program_points && data.program_points.length > 0) {
                        data.program_points.forEach((p, i) => {
                            programWrapper.insertAdjacentHTML("beforeend", `
                        <div class="mb-2 d-flex gap-2 align-items-center" id="program-${index}-${i}">
                            <input type="text" 
                                name="itineraries[${index}][program_points][]" 
                                class="form-control" 
                                value="${p.point}" readonly>
                            <button type="button" class="btn btn-sm btn-danger" 
                                onclick="removeElement('program-${index}-${i}')">X</button>
                        </div>
                    `);
                        });
                    } else {
                        programWrapper.insertAdjacentHTML("beforeend",
                            `<p class="text-muted">No program points found</p>`);
                    }

                    // Add manual add button
                    programWrapper.insertAdjacentHTML("beforeend", `
                <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addProgramPoint(${index})">
                    + Add Program Point
                </button>
            `);

                    // === HIGHLIGHTS ===
                    const highlightWrapper = document.getElementById("highlightWrapper" + index);
                    highlightWrapper.innerHTML = `<label><strong>Highlights</strong></label>`;

                    const fetchedHighlightsCount = data.highlights ? data.highlights.length : 0;
                    highlightCounters[index] = fetchedHighlightsCount;

                    if (data.highlights && data.highlights.length > 0) {
                        data.highlights.forEach((h, i) => {
                            const hid = `highlight-${index}-${i}`;
                            highlightWrapper.insertAdjacentHTML("beforeend", `
        <div class="row mb-2 border p-2 rounded align-items-center" id="${hid}">
            <div class="col-md-4">
                <input name="itineraries[${index}][highlights][${i}][highlight_places]" 
                       class="form-control" value="${h.place_name}" readonly>
            </div>
            <div class="col-md-4">
                <input name="itineraries[${index}][highlights][${i}][description]" 
                       class="form-control" value="${h.description}" readonly>
            </div>
            <div class="col-md-3">
                ${h.image ? `<input type="hidden" name="itineraries[${index}][highlights][${i}][images]" value="${h.image}">
                                                                 <img src="/storage/${h.image}" class="img-fluid rounded" style="max-height:60px;">` : ''}
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeElement('${hid}')">X</button>
            </div>
        </div>
                    `);
                        });
                    } else {
                        highlightWrapper.insertAdjacentHTML("beforeend",
                            `<p class="text-muted">No highlights found</p>`);
                    }

                    // Add button to manually add more highlights
                    highlightWrapper.insertAdjacentHTML("beforeend", `
                <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addHighlight(${index})">
                    + Add Highlight
                </button>
            `);
                });
        }



        let summaryIndex = 0;
        let itineraryIndex = 0;

        function addSummary() {
            const wrapper = document.getElementById("summaryWrapper");
            const id = `summary-${summaryIndex}`;
            wrapper.insertAdjacentHTML("beforeend", `
                <div class="row mb-2 align-items-center" id="${id}">
                    <div class="col-md-3">
                        <select name="tour_summaries[${summaryIndex}][city]" class="form-select">
                               <option value="" disabled selected>Select Place</option>
                            @foreach ($destinations as $d)
                                <option value="{{ $d->name }}">{{ $d->name }}</option>
                            @endforeach
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

        function addItinerary() {
            const wrapper = document.getElementById("itineraryWrapper");
            const id = `itinerary-${itineraryIndex}`;

            // Build options dynamically from JS array
            const destinationOptions = @json($destinations->map(fn($d) => ['id' => $d->id, 'name' => $d->name]));

            const optionsHtml = destinationOptions.map(d => `<option value="${d.id}">${d.name}</option>`).join('');

            wrapper.insertAdjacentHTML("beforeend", `
        <div class="border p-3 mb-3 rounded" id="${id}">
            <div class="row mb-2">
                <div class="col-md-3">
                    <label>Destination</label>
                    <select name="itineraries[${itineraryIndex}][place_id]" class="form-select"
                        onchange="fetchProgramPoints(this, ${itineraryIndex})">
                        <option value="">-- Select Destination --</option>
                        ${optionsHtml}
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
                        ${hotels.map(h => `<option value="${h.hotel_name}">${h.hotel_name}</option>`).join('')}
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

            itineraryIndex++;
        }


        let highlightCounters = {}; // track per itinerary

        function addHighlight(itineraryIdx) {
            // initialize counter for this itinerary
            if (!highlightCounters[itineraryIdx]) {
                highlightCounters[itineraryIdx] = 0;
            }
            const highlightIdx = highlightCounters[itineraryIdx]++;

            const wrapper = document.getElementById("highlightWrapper" + itineraryIdx);
            const id = `highlight-${itineraryIdx}-${highlightIdx}`;

            wrapper.insertAdjacentHTML("beforeend", `
        <div class="row mb-2 border p-2 rounded align-items-center" id="${id}">
            <div class="col-md-4">
                <input name="itineraries[${itineraryIdx}][highlights][${highlightIdx}][highlight_places]" 
                       class="form-control" placeholder="Place Name">
            </div>
            <div class="col-md-4">
                <input name="itineraries[${itineraryIdx}][highlights][${highlightIdx}][description]" 
                       class="form-control" placeholder="Description">
            </div>
            <div class="col-md-3">
                <input type="file" name="itineraries[${itineraryIdx}][highlights][${highlightIdx}][images]" 
                       class="form-control">
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeElement('${id}')">X</button>
            </div>
        </div>
    `);
        }


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

        function removeElement(id) {
            const el = document.getElementById(id);
            if (el) el.remove();
        }

        // Wait 3 seconds (3000ms) then fade out alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                // Use Bootstrap's built-in fade out
                alert.classList.remove('show');
                alert.classList.add('hide');
                setTimeout(() => alert.remove(), 500); // remove from DOM after fade
            });
        }, 3000);
    </script>
@endsection
