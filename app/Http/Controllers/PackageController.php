<?php

// app/Http/Controllers/Admin/PackageController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Hotel;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\TourSummary;
use App\Models\DetailItinerary;
use App\Models\Highlight;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PackageController extends Controller
{

    public function index(Request $request)
{
    $query = Package::query();

    if ($request->type) {
        $query->where('type', $request->type);
    }
    if ($request->category) {
        $query->where('tour_category', $request->category);
    }
    if ($request->status) {
        $query->where('status', $request->status);
    }

    $packages = $query->paginate(10)->appends($request->all()); // ✅ keep filters with pagination

    if ($request->ajax()) {
        return view('tour.table', compact('packages'))->render();
    }

    $types = Package::select('type')->distinct()->pluck('type');
    $categories = Package::select('tour_category')->distinct()->pluck('tour_category');

    return view('tour.view', compact('packages', 'types', 'categories'));
}



    public function create()
    {
        $destinations = Destination::all();  // for dropdowns
        $hotels = Hotel::all();

        return view('tour.create', compact('destinations', 'hotels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'tour_ref_no' => 'required|string|max:100',
            'description' => 'nullable|string',
            'summary_description' => 'nullable|string',
            'country' => 'nullable|string|max:255',
            'place' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
            'days' => 'nullable|integer',
            'nights' => 'nullable|integer',
            'ratings' => 'nullable|numeric|min:0|max:5',
            'price' => 'nullable|numeric',
            'status' => 'nullable',
            'main_picture' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
            'map_image' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
        ]);

        // === Upload images if present ===
        if ($request->hasFile('main_picture')) {
            $file = $request->file('main_picture');

            // Use only type folder (no subfolder)
            $typeFolder = $request->type ?? 'general';

            // Build filename: tailor + random string + extension
            $filename = Str::slug($request->place ?? 'image') . Str::random(5) . '.' . $file->getClientOriginalExtension();

            // Store in "public/inbound/tailor8.jpg" style
            $mainPicturePath = $file->storeAs($typeFolder, $filename, 'public');
        } else {
            $mainPicturePath = null;
        }

        if ($request->hasFile('map_image')) {
            $file = $request->file('map_image');

            // Only use type folder (no subfolder)
            $typeFolder = $request->type ?? 'general';
            $placeFolder = $request->place ? Str::slug($request->place) : 'unknown';

            // Build filename: kandy-map.jpg
            $filename = $placeFolder . '-map.' . $file->getClientOriginalExtension();

            // Store in "public/inbound/kandy-map.jpg"
            $mapImagePath = $file->storeAs($typeFolder, $filename, 'public');
        } else {
            $mapImagePath = null;
        }


        // === Create Package ===
        $package = Package::create([
            'heading' => $request->heading,
            'tour_ref_no' => $request->tour_ref_no,
            'description' => $request->description,
            'summary_description' => $request->summary_description,
            'country_name' => $request->country,
            'place' => $request->place,
            'type' => $request->type,
            'tour_category' => $request->category,
            'days' => $request->days,
            'nights' => $request->nights,
            'ratings' => $request->ratings,
            'price' => $request->price,
            'status' => $request->status,
            'picture' => $mainPicturePath,
            'map_image' => $mapImagePath,
        ]);

        // === Tour Summaries ===
        if ($request->tour_summaries) {
            foreach ($request->tour_summaries as $summary) {
                TourSummary::create([
                    'package_id' => $package->id,
                    'city' => $summary['city'] ?? null,
                    'theme' => $summary['theme'] ?? null,
                    'day' => $summary['day'] ?? null,
                    'key_attributes' => isset($summary['key_attributes']) ? json_encode($summary['key_attributes']) : null,
                    'images' => isset($summary['images']) ? json_encode($summary['images']) : null,
                ]);
            }
        }

        // === Itineraries ===
        if ($request->itineraries) {
            foreach ($request->itineraries as $index => $itinerary) {

                // --- Determine the destination name ---
                $destinationName = null;
                if (!empty($itinerary['place_id'])) {
                    $destination = Destination::find($itinerary['place_id']);
                    $destinationName = $destination ? $destination->name : null;
                }

                // Upload itinerary picture
                if (isset($itinerary['pictures']) && $itinerary['pictures']) {
                    $file = $itinerary['pictures'];

                    // Determine type and place folder
                    $typeFolder = $request->type ?? 'general';
                    $placeFolder = isset($itinerary['place_name']) ? Str::slug($itinerary['place_name']) : 'unknown';

                    // Build filename
                    $filename = $placeFolder . '-itinerary.' . $file->getClientOriginalExtension();

                    // Store in "public/inbound/detail-itineraries/kandy-itinerary.jpg"
                    $picturePath = $file->storeAs("$typeFolder/detail-itineraries", $filename, 'public');
                } else {
                    $picturePath = null;
                }

                // Program points as JSON array
                $programPoints = isset($itinerary['program_points'])
                    ? json_encode($itinerary['program_points'])
                    : json_encode([]);




                $detail = DetailItinerary::create([
                    'package_id' => $package->id,
                    'place_name' => $destinationName,
                    'day' => $itinerary['day'] ?? null,
                    'pictures' => $picturePath,
                    'description' => $itinerary['description'] ?? null,
                    'program_points' => $programPoints,
                    'overnight_stay' => $itinerary['overnight_stay'] ?? null,
                    'meal_plan' => $itinerary['meal_plan'] ?? null,
                    'approximate_travel_time' => $itinerary['approximate_travel_time'] ?? null,
                ]);

                // === Highlights ===
                // === Highlights ===
                // === Highlights ===
                if (!empty($itinerary['highlights'])) {
                    foreach ($itinerary['highlights'] as $highlight) {
                        $imagePath = null;

                        if (!empty($highlight['images']) && $highlight['images'] instanceof \Illuminate\Http\UploadedFile) {
                            // store file
                        } elseif (!empty($highlight['images']) && is_string($highlight['images'])) {
                            $imagePath = $highlight['images'];
                        } else {
                            $imagePath = null;
                        }

                        // Save highlight
                        Highlight::create([
                            'itinerary_id'     => $detail->id,
                            'highlight_places' => $highlight['highlight_places'] ?? null,
                            'description'      => $highlight['description'] ?? null,
                            'images'           => $imagePath, // uploaded file path, or reused path, or null
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Tour Package created successfully!');
    }


    // For AJAX edit
    public function edit(Package $package)
    {
        $package->load('tourSummaries', 'detailItineraries.highlights');
        return response()->json($package);
    }

    // For update
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'tour_ref_no' => 'required|string|max:100',
            'description' => 'nullable|string',
            'summary_description' => 'nullable|string',
            'country' => 'nullable|string|max:255',
            'place' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
            'days' => 'nullable|integer',
            'nights' => 'nullable|integer',
            'ratings' => 'nullable|numeric|min:0|max:5',
            'price' => 'nullable|numeric',
            'status' => 'nullable',
            'main_picture' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
            'map_image' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
        ]);

        // Update main images if uploaded
        if ($request->hasFile('main_picture')) {
            $file = $request->file('main_picture');
            $filename = Str::slug($request->place ?? 'image') . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $package->picture = $file->storeAs($request->type ?? 'general', $filename, 'public');
        }

        if ($request->hasFile('map_image')) {
            $file = $request->file('map_image');
            $filename = Str::slug($request->place ?? 'map') . '-map.' . $file->getClientOriginalExtension();
            $package->map_image = $file->storeAs($request->type ?? 'general', $filename, 'public');
        }

        $package->update($request->only([
            'heading',
            'tour_ref_no',
            'description',
            'summary_description',
            'country',
            'place',
            'type',
            'category',
            'days',
            'nights',
            'ratings',
            'price',
            'status'
        ]));

        // TODO: Update Tour Summaries, Itineraries, Highlights via dynamic input
        // You can loop $request->tour_summaries / $request->itineraries as in store()

        return redirect()->back()->with('success', 'Package updated successfully!');
    }
}
