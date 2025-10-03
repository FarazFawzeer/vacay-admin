

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Tour Packages', 'subtitle' => 'Create'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">New Tour Package</h5>
        </div>

        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>


            <form id="packageForm" action="<?php echo e(route('admin.packages.store')); ?>" method="POST"
                enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                
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
                    <div class="col-md-4 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">-- Select Category --</option>
                            <option value="special">Special</option>
                            <option value="city">City</option>
                            <option value="tailor">Tailor Made</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="days" class="form-label">Days</label>
                        <input type="number" name="days" id="days" class="form-control" placeholder="e.g., 7">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="nights" class="form-label">Nights</label>
                        <input type="number" name="nights" id="nights" class="form-control" placeholder="e.g., 6">
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

                
                <div class="card my-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Tour Summaries</span>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addSummary()">+ Add
                            Summary</button>
                    </div>
                    <div class="card-body" id="summaryWrapper"></div>
                </div>

                
                <div class="card my-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Itineraries</span>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addItinerary()">+ Add
                            Itinerary</button>
                    </div>
                    <div class="card-body" id="itineraryWrapper"></div>
                </div>

                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Create Package</button>
                </div>
            </form>

        </div>
    </div>

    
    <script>
        const hotels = <?php echo json_encode(
            $hotels->map(fn($h) => [
                    'id' => $h->id, 'hotel_name' => $h->hotel_name, ])) ?>;
    </script>


    <script>
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
                            <?php $__currentLoopData = $destinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             <option value="">-- Select Destination --</option>
                                <option value="<?php echo e($d->name); ?>"><?php echo e($d->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            const destinationOptions = <?php echo json_encode($destinations->map(fn($d) => ['id' => $d->id, 'name' => $d->name]), 512) ?>;

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['subtitle' => 'Create Tour Package'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/tour/create.blade.php ENDPATH**/ ?>