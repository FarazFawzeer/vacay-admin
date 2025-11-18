<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    // Show all hotels
    public function index()
    {
        $hotels = Hotel::orderBy('updated_at', 'desc')->paginate(10);
        return view('details.hotel', compact('hotels'));
    }

    // Store new hotel (AJAX)
 public function store(Request $request)
{
    $request->validate([
        'hotel_name' => 'required|string|max:255',
        'star' => 'nullable|integer|min:1|max:7',
        'status' => 'required|in:1,0',
        'room_type' => 'nullable|string',
        'meal_plan' => 'nullable|string',
        'description' => 'nullable|string',
        'facilities' => 'nullable|string',
        'entertainment' => 'nullable|string',
        'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $imagePaths = [];

    if ($request->hasFile('pictures')) {
        foreach ($request->file('pictures') as $image) {
            $path = $image->store('hotel', 'public');
            $imagePaths[] = $path;
        }
    }

    $hotel = Hotel::create([
        'hotel_name' => $request->hotel_name,
        'star' => $request->star,
        'status' => $request->status,
        'room_type' => $request->room_type,
        'meal_plan' => $request->meal_plan,
        'description' => $request->description,
        'facilities' => $request->facilities,
        'entertainment' => $request->entertainment,
        'pictures' => $imagePaths,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Hotel created successfully',
        'hotel' => $hotel,
    ]);
}


    // Update hotel (AJAX)
public function update(Request $request, $id)
{
    $hotel = Hotel::findOrFail($id);

    $request->validate([
        'hotel_name' => 'required|string|max:255',
        'star' => 'nullable|integer|min:1|max:7',
        'status' => 'required|in:1,0',
        'room_type' => 'nullable|string',
        'meal_plan' => 'nullable|string',
        'description' => 'nullable|string',
        'facilities' => 'nullable|string',
        'entertainment' => 'nullable|string',
        'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $imagePaths = $hotel->pictures ?? [];

    // If new images are uploaded → delete old ones first
    if ($request->hasFile('pictures')) {
        // Delete old images from storage
        if (!empty($imagePaths)) {
            foreach ($imagePaths as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        // Store new images
        $newPaths = [];
        foreach ($request->file('pictures') as $image) {
            $newPaths[] = $image->store('hotel', 'public');
        }

        $imagePaths = $newPaths;
    }

    $hotel->update([
        'hotel_name' => $request->hotel_name,
        'star' => $request->star,
        'status' => $request->status,
        'room_type' => $request->room_type,
        'meal_plan' => $request->meal_plan,
        'description' => $request->description,
        'facilities' => $request->facilities,
        'entertainment' => $request->entertainment,
        'pictures' => $imagePaths,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Hotel updated successfully',
        'hotel' => $hotel,
    ]);
}



    // Delete hotel (AJAX)
    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);

        // Delete stored images
        if ($hotel->pictures) {
            $images = json_decode($hotel->pictures, true);
            foreach ($images as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $hotel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hotel deleted successfully',
        ]);
    }
}
