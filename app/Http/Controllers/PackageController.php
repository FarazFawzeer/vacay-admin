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

                // --- Determine the destination name (This part is fine) ---
                $destinationName = null;
                if (!empty($itinerary['place_id'])) {
                    $destination = Destination::find($itinerary['place_id']);
                    $destinationName = $destination ? $destination->name : null;
                }

                // Upload itinerary picture (This part is fine, just ensure $itinerary['pictures'] is available)
                $picturePath = null;
                if (isset($itinerary['pictures']) && $itinerary['pictures'] instanceof \Illuminate\Http\UploadedFile) {
                    $file = $itinerary['pictures'];
                    $typeFolder = $request->type ?? 'general';
                    // Use $destinationName if available, otherwise fallback
                    $placeFolder = $destinationName ? Str::slug($destinationName) : 'unknown';
                    $filename = $placeFolder . '-itinerary-' . Str::random(4) . '.' . $file->getClientOriginalExtension();

                    // Store in "public/inbound/detail-itineraries/kandy-itinerary-xxxx.jpg"
                    $picturePath = $file->storeAs("$typeFolder/detail-itineraries", $filename, 'public');
                }


                // Program points as JSON array (This part is fine)
                $programPoints = isset($itinerary['program_points'])
                    ? json_encode($itinerary['program_points'])
                    : json_encode([]);

                // Create DetailItinerary (This part is fine)
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

                // === Highlights (Corrected Logic) ===
                if (!empty($itinerary['highlights'])) {
                    // 🎯 CORRECT BASE PATH: Use the path structure provided by the user.
                    $basePath = "destination_highlights";

                    foreach ($itinerary['highlights'] as $highlightIndex => $highlight) {
                        $imagePath = null;

                        // CASE 1: New file uploaded (from manual add)
                        if (isset($highlight['images']) && $highlight['images'] instanceof \Illuminate\Http\UploadedFile) {
                            $file = $highlight['images'];

                            // 🎯 NEW FILENAME: Generate a random string to match the desired format.
                            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

                            // Store the new file in the required uniform location
                            // e.g., destination_highlights/random_string.jpg
                            $imagePath = $file->storeAs($basePath, $filename, 'public');

                            // CASE 2: Pre-existing image path (from AJAX fetch - this is a string)
                        } elseif (isset($highlight['images']) && is_string($highlight['images'])) {
                            // Re-use the path from the hidden input field. This path will already be 
                            // in the format 'destination_highlights/...' if fetched correctly.
                            $imagePath = $highlight['images'];

                            // CASE 3: No image was submitted for this highlight
                        } else {
                            $imagePath = null;
                        }

                        // Save highlight
                        Highlight::create([
                            'itinerary_id' => $detail->id,
                            'highlight_places' => $highlight['highlight_places'] ?? null,
                            'description' => $highlight['description'] ?? null,
                            'images'  => $imagePath,
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Tour Package created successfully!');
    }


    // For AJAX edit
    public function edit($id)
    {
        $package = Package::with(['tourSummaries', 'detailItineraries.highlights'])->findOrFail($id);

        $destinations = Destination::all();
        $hotels = Hotel::all();

        return view('tour.edit', compact('package', 'destinations', 'hotels'));
    }

    // For update
 public function update(Request $request, $id)
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

    $package = Package::findOrFail($id);

    // === Handle main_picture update ===
    if ($request->hasFile('main_picture')) {
        $file = $request->file('main_picture');
        $typeFolder = $request->type ?? 'general';
        $filename = Str::slug($request->place ?? 'image') . Str::random(5) . '.' . $file->getClientOriginalExtension();
        $mainPicturePath = $file->storeAs($typeFolder, $filename, 'public');
    } else {
        $mainPicturePath = $package->picture; // keep old
    }

    // === Handle map_image update ===
    if ($request->hasFile('map_image')) {
        $file = $request->file('map_image');
        $typeFolder = $request->type ?? 'general';
        $placeFolder = $request->place ? Str::slug($request->place) : 'unknown';
        $filename = $placeFolder . '-map.' . $file->getClientOriginalExtension();
        $mapImagePath = $file->storeAs($typeFolder, $filename, 'public');
    } else {
        $mapImagePath = $package->map_image; // keep old
    }

    // === Update package ===
    $package->update([
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

    // === Update Tour Summaries ===
    if ($request->tour_summaries) {
        // Optional: delete old summaries first if full replace
        TourSummary::where('package_id', $package->id)->delete();

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

    // === Update Itineraries ===
    if ($request->itineraries) {
        // Optional: delete old itineraries and highlights before re-inserting
        $oldItineraries = DetailItinerary::where('package_id', $package->id)->get();
        foreach ($oldItineraries as $old) {
            Highlight::where('itinerary_id', $old->id)->delete();
        }
        DetailItinerary::where('package_id', $package->id)->delete();

        foreach ($request->itineraries as $itinerary) {
            $destinationName = null;
            if (!empty($itinerary['place_id'])) {
                $destination = Destination::find($itinerary['place_id']);
                $destinationName = $destination ? $destination->name : null;
            }

            // Upload itinerary picture
            $picturePath = null;
            if (isset($itinerary['pictures']) && $itinerary['pictures'] instanceof \Illuminate\Http\UploadedFile) {
                $file = $itinerary['pictures'];
                $typeFolder = $request->type ?? 'general';
                $placeFolder = $destinationName ? Str::slug($destinationName) : 'unknown';
                $filename = $placeFolder . '-itinerary-' . Str::random(4) . '.' . $file->getClientOriginalExtension();
                $picturePath = $file->storeAs("$typeFolder/detail-itineraries", $filename, 'public');
            }

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

            // === Update Highlights ===
            if (!empty($itinerary['highlights'])) {
                $basePath = "destination_highlights";

                foreach ($itinerary['highlights'] as $highlight) {
                    $imagePath = null;

                    if (isset($highlight['images']) && $highlight['images'] instanceof \Illuminate\Http\UploadedFile) {
                        $file = $highlight['images'];
                        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                        $imagePath = $file->storeAs($basePath, $filename, 'public');
                    } elseif (isset($highlight['images']) && is_string($highlight['images'])) {
                        $imagePath = $highlight['images'];
                    }

                    Highlight::create([
                        'itinerary_id' => $detail->id,
                        'highlight_places' => $highlight['highlight_places'] ?? null,
                        'description' => $highlight['description'] ?? null,
                        'images' => $imagePath,
                    ]);
                }
            }
        }
    }

    return redirect()->back()->with('success', 'Tour Package updated successfully!');
}

}
