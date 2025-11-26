<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    // Show all hotels
   public function index(Request $request)
{
    $query = Hotel::query();

    // Search by hotel name
    if ($request->filled('search')) {
        $query->where('hotel_name', 'like', '%' . $request->search . '%');
    }

    // Filter by category
    if ($request->filled('category')) {
        $query->where('hotel_category', $request->category);
    }

    $hotels = $query->orderBy('updated_at', 'desc')->paginate(10);

    // For AJAX requests, return a partial table
    if ($request->ajax()) {
        return view('details.partials.hotel_table', compact('hotels'))->render();
    }

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
        'city' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:255',
        'hotel_category' => 'nullable|string|max:255',
        'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $imagePaths = [];

    if ($request->hasFile('pictures')) {
        foreach ($request->file('pictures') as $image) {
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('hotel'), $filename);
            $imagePaths[] = 'hotel/' . $filename; // store relative path
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
        'city' => $request->city,
        'address' => $request->address,
        'hotel_category' => $request->hotel_category,
        'pictures' => $imagePaths,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Hotel created successfully',
        'hotel' => $hotel,
    ]);
}



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
        'city' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:255',
        'hotel_category' => 'nullable|string|max:255',
        'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $imagePaths = $hotel->pictures ?? [];

    // If new images uploaded → delete old ones
    if ($request->hasFile('pictures')) {

        // Delete old images
        if (!empty($imagePaths)) {
            foreach ($imagePaths as $oldImage) {
                $oldPath = public_path($oldImage);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        // Upload new ones
        $newPaths = [];
        foreach ($request->file('pictures') as $image) {
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('hotel'), $filename);
            $newPaths[] = 'hotel/' . $filename;
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
        'city' => $request->city,
        'address' => $request->address,
        'hotel_category' => $request->hotel_category,
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
            foreach ($hotel->pictures as $path) {
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
