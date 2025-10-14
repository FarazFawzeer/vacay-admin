

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Tour Packages', 'subtitle' => 'Edit'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Tour Package</h5>
        </div>

        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.packages.update', $package->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="heading" class="form-label">Heading</label>
                        <input type="text" name="heading" id="heading" class="form-control"
                            value="<?php echo e(old('heading', $package->heading)); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tour_ref_no" class="form-label">Reference No</label>
                        <input type="text" name="tour_ref_no" id="tour_ref_no" class="form-control"
                            value="<?php echo e(old('tour_ref_no', $package->tour_ref_no)); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control"><?php echo e(old('description', $package->description)); ?></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="summary_description" class="form-label">Summary Description</label>
                        <textarea name="summary_description" id="summary_description" class="form-control"><?php echo e(old('summary_description', $package->summary_description)); ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" name="country" id="country" class="form-control"
                            value="<?php echo e(old('country', $package->country_name)); ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="place" class="form-label">Place</label>
                        <input type="text" name="place" id="place" class="form-control"
                            value="<?php echo e(old('place', $package->place)); ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="inbound" <?php echo e($package->type == 'inbound' ? 'selected' : ''); ?>>Inbound</option>
                            <option value="outbound" <?php echo e($package->type == 'outbound' ? 'selected' : ''); ?>>Outbound</option>
                        </select>
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="special" <?php echo e($package->tour_category == 'special' ? 'selected' : ''); ?>>Special
                            </option>
                            <option value="city" <?php echo e($package->tour_category == 'city' ? 'selected' : ''); ?>>City</option>
                            <option value="tailor" <?php echo e($package->tour_category == 'tailor' ? 'selected' : ''); ?>>Tailor Made
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="days" class="form-label">Days</label>
                        <input type="number" name="days" id="days" class="form-control"
                            value="<?php echo e(old('days', $package->days)); ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="nights" class="form-label">Nights</label>
                        <input type="number" name="nights" id="nights" class="form-control"
                            value="<?php echo e(old('nights', $package->nights)); ?>">
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="ratings" class="form-label">Ratings</label>
                        <input type="number" step="0.1" min="0" max="5" name="ratings"
                            class="form-control" value="<?php echo e(old('ratings', $package->ratings)); ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" <?php echo e($package->status == 1 ? 'selected' : ''); ?>>Active</option>
                            <option value="0" <?php echo e($package->status == 0 ? 'selected' : ''); ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Price (USD)</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control"
                            value="<?php echo e(old('price', $package->price)); ?>">
                    </div>
                </div>

                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="main_picture" class="form-label">Main Picture</label>
                        <input type="file" name="main_picture" id="main_picture" class="form-control">
                        <?php if($package->picture): ?>
                            <div class="mt-2">
                                <img src="<?php echo e(asset('storage/' . $package->picture)); ?>" width="120"
                                    class="rounded shadow">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="map_image" class="form-label">Map Image</label>
                        <input type="file" name="map_image" id="map_image" class="form-control">
                        <?php if($package->map_image): ?>
                            <div class="mt-2">
                                <img src="<?php echo e(asset('storage/' . $package->map_image)); ?>" width="120"
                                    class="rounded shadow">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="card my-4">
                    <div class="card-header d-flex justify-content-between">
                        <span>Tour Summaries</span>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addSummary()">+ Add
                            Summary</button>
                    </div>
                    <div class="card-body" id="summaryWrapper">
                        <?php $__currentLoopData = $package->summaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $summary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row mb-2" id="summary-<?php echo e($i); ?>">
                                <div class="col-md-3">
                                    <input name="tour_summaries[<?php echo e($i); ?>][city]" class="form-control"
                                        value="<?php echo e($summary->city); ?>">
                                </div>
                                <div class="col-md-3">
                                    <input name="tour_summaries[<?php echo e($i); ?>][theme]" class="form-control"
                                        value="<?php echo e($summary->theme); ?>">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="removeElement('summary-<?php echo e($i); ?>')">Remove</button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="card my-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Itineraries</span>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addItinerary()">+ Add
                            Itinerary</button>
                    </div>
                    <div class="card-body" id="itineraryWrapper">
                        <?php $__currentLoopData = $package->itineraries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $itinerary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border p-3 mb-3 rounded" id="itinerary-<?php echo e($i); ?>">
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label>Destination</label>
                                        <select name="itineraries[<?php echo e($i); ?>][place_id]" class="form-select"
                                            onchange="fetchProgramPoints(this, <?php echo e($i); ?>)">
                                            <option value="">-- Select Destination --</option>
                                            <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($d->id); ?>"
                                                    <?php echo e($itinerary->place_id == $d->id ? 'selected' : ''); ?>>
                                                    <?php echo e($d->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label>Day</label>
                                        <input type="number" name="itineraries[<?php echo e($i); ?>][day]"
                                            class="form-control" value="<?php echo e($itinerary->day); ?>">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Picture</label>
                                        <input type="file" name="itineraries[<?php echo e($i); ?>][pictures]"
                                            class="form-control">
                                        <?php if($itinerary->pictures): ?>
                                            <img src="<?php echo e(asset('storage/' . $itinerary->pictures)); ?>" width="100"
                                                class="mt-2 rounded">
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Description</label>
                                        <input name="itineraries[<?php echo e($i); ?>][description]" class="form-control"
                                            value="<?php echo e($itinerary->description); ?>">
                                    </div>

                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="removeElement('itinerary-<?php echo e($i); ?>')">Remove</button>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-4">

                                        <div id="programWrapper<?php echo e($i); ?>">
                                            <label><strong>Program Points</strong></label>
                                            <?php
                                                $programPoints = is_string($itinerary->program_points)
                                                    ? json_decode($itinerary->program_points, true) ?? []
                                                    : $itinerary->program_points ?? [];
                                            ?>

                                            <?php if(!empty($programPoints)): ?>
                                                <?php $__currentLoopData = $programPoints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pIndex => $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="mb-2 d-flex gap-2 align-items-center"
                                                        id="program-<?php echo e($loop->index); ?>">
                                                        <input type="text"
                                                            name="itineraries[<?php echo e($loop->parent->index); ?>][program_points][]"
                                                            class="form-control" value="<?php echo e($point); ?>" readonly>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="removeElement('program-<?php echo e($loop->index); ?>')">X</button>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-sm btn-secondary mt-2"
                                                onclick="addProgramPoint(<?php echo e($i); ?>)">+ Add Program
                                                Point</button>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Overnight Stay</label>
                                        <select name="itineraries[<?php echo e($i); ?>][overnight_stay]"
                                            class="form-select">
                                            <option value="">-- Select Hotel --</option>
                                            <?php $__currentLoopData = $hotels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($h->hotel_name); ?>"
                                                    <?php echo e($itinerary->overnight_stay == $h->hotel_name ? 'selected' : ''); ?>>
                                                    <?php echo e($h->hotel_name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label>Meal Plan</label>
                                        <input name="itineraries[<?php echo e($i); ?>][meal_plan]" class="form-control"
                                            value="<?php echo e($itinerary->meal_plan); ?>">
                                    </div>

                                    <div class="col-md-2">
                                        <label>Travel Time</label>
                                        <input name="itineraries[<?php echo e($i); ?>][approximate_travel_time]"
                                            class="form-control" value="<?php echo e($itinerary->approximate_travel_time); ?>">
                                    </div>
                                </div>

                                
                                <div id="highlightWrapper<?php echo e($i); ?>" class="mt-3">
                                    <label><strong>Highlights</strong></label>
                                    <?php $__currentLoopData = $itinerary->highlights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hIndex => $highlight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="row mb-2 border p-2 rounded align-items-center"
                                            id="highlight-<?php echo e($i); ?>-<?php echo e($hIndex); ?>">
                                            <div class="col-md-4">
                                                <input
                                                    name="itineraries[<?php echo e($i); ?>][highlights][<?php echo e($hIndex); ?>][highlight_places]"
                                                    class="form-control" value="<?php echo e($highlight->highlight_places); ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <input
                                                    name="itineraries[<?php echo e($i); ?>][highlights][<?php echo e($hIndex); ?>][description]"
                                                    class="form-control" value="<?php echo e($highlight->description); ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="file"
                                                    name="itineraries[<?php echo e($i); ?>][highlights][<?php echo e($hIndex); ?>][images]"
                                                    class="form-control">
                                                <?php if($highlight->images): ?>
                                                    
                                                    <input type="hidden"
                                                        name="itineraries[<?php echo e($i); ?>][highlights][<?php echo e($hIndex); ?>][existing_image]"
                                                        value="<?php echo e($highlight->images); ?>">
                                                    <img src="<?php echo e(asset('storage/' . $highlight->images)); ?>"
                                                        width="80" class="mt-2 rounded">
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center">
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="removeElement('highlight-<?php echo e($i); ?>-<?php echo e($hIndex); ?>')">X</button>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <button type="button" class="btn btn-sm btn-secondary mt-2"
                                        onclick="addHighlight(<?php echo e($i); ?>)">+ Add Highlight</button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Update Package</button>
                </div>
            </form>
        </div>
    </div>

    
    <script>
        // ======= HOTEL LIST (from backend) =======
        const hotels = <?php echo json_encode(
            $hotels->map(fn($h) => [
                    'id' => $h->id, 'hotel_name' => $h->hotel_name, ])) ?>;

        // ======= DESTINATIONS (used for dropdowns) =======
        const destinations = <?php echo json_encode($destinations->map(fn($d) => ['id' => $d->id, 'name' => $d->name]), 512) ?>;

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Edit Tour Package'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/tour/edit.blade.php ENDPATH**/ ?>