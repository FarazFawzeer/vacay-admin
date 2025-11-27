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
if ($request->search) {
    $search = $request->search;

    $query->where(function($q) use ($search) {
        $q->where('hotel_name', 'LIKE', "%{$search}%")
          ->orWhere('city', 'LIKE', "%{$search}%")
          ->orWhere('country', 'LIKE', "%{$search}%")
          ->orWhere('hotel_category', 'LIKE', "%{$search}%");
    });
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
    // Store new hotel (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'hotel_name' => 'required|string|max:255',
            'star' => 'nullable|integer|min:1|max:7',
            'status' => 'required|in:1,0,2', // Added "2" for Expire
            'meal_plan' => 'nullable|string',
            'description' => 'nullable|string',
            'room_type' => 'nullable|array',
            'room_type.*' => 'nullable|string|max:255',
            'facilities' => 'nullable|array',
            'facilities.*' => 'nullable|string|max:255',
            'entertainment' => 'nullable|array',
            'entertainment.*' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'hotel_category' => 'nullable|string|max:255',
            'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'meal_name' => 'nullable|array',
            'meal_price' => 'nullable|array',
            'meal_currency' => 'nullable|array',
        ]);

        // Handle pictures
        $imagePaths = [];
        if ($request->hasFile('pictures')) {
            foreach ($request->file('pictures') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('hotel'), $filename);
                $imagePaths[] = 'hotel/' . $filename; // store relative path
            }
        }

        // Prepare meal costs array
        $mealCosts = [];
        if ($request->meal_name && is_array($request->meal_name)) {
            foreach ($request->meal_name as $index => $name) {
                $mealCosts[] = [
                    'name' => $name,
                    'price' => $request->meal_price[$index] ?? 0,
                    'currency' => $request->meal_currency[$index] ?? 'USD',
                ];
            }
        }

        $rooms = [];
        if ($request->room_type && is_array($request->room_type)) {
            foreach ($request->room_type as $index => $type) {
                $rooms[] = [
                    'type' => $type,
                    'price' => $request->room_price[$index] ?? 0,
                    'currency' => $request->room_currency[$index] ?? 'USD',
                ];
            }
        }

        // Create hotel
        $hotel = Hotel::create([
            'hotel_name' => $request->hotel_name,
            'star' => $request->star,
            'status' => $request->status,
            'room_type' => $rooms,
            'meal_plan' => $request->meal_plan,
            'meal_costs' => $mealCosts, // <-- store meal costs here
            'description' => $request->description,
            'facilities' => $request->facilities,
            'entertainment' => $request->entertainment,
            'city' => $request->city,
            'address' => $request->address,
            'country' => $request->country,
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
            'status' => 'required|in:1,0,2', // Added "2" for Expire
            'meal_plan' => 'nullable|string',
            'description' => 'nullable|string',
            'room_type' => 'nullable|array',
            'room_type.*' => 'nullable|string|max:255',
            'room_price' => 'nullable|array',
            'room_currency' => 'nullable|array',
            'facilities' => 'nullable|array',
            'facilities.*' => 'nullable|string|max:255',
            'entertainment' => 'nullable|array',
            'entertainment.*' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'hotel_category' => 'nullable|string|max:255',
            'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'meal_name' => 'nullable|array',
            'meal_price' => 'nullable|array',
            'meal_currency' => 'nullable|array',
        ]);

        // -------------------------
        // Handle Pictures
        // -------------------------
        $imagePaths = $hotel->pictures ?? [];

        if ($request->hasFile('pictures')) {
            // Delete old images
            if (!empty($imagePaths)) {
                foreach ($imagePaths as $oldImage) {
                    $oldPath = public_path($oldImage);
                    if (file_exists($oldPath)) unlink($oldPath);
                }
            }

            // Upload new images
            $newPaths = [];
            foreach ($request->file('pictures') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('hotel'), $filename);
                $newPaths[] = 'hotel/' . $filename;
            }
            $imagePaths = $newPaths;
        }

        // -------------------------
        // Handle Meal Costs
        // -------------------------
        $mealCosts = [];
        if ($request->meal_name && is_array($request->meal_name)) {
            foreach ($request->meal_name as $index => $name) {
                $mealCosts[] = [
                    'name' => $name,
                    'price' => $request->meal_price[$index] ?? 0,
                    'currency' => $request->meal_currency[$index] ?? 'USD',
                ];
            }
        }

        // -------------------------
        // Handle Room Types
        // -------------------------
        $rooms = [];
        if ($request->room_type && is_array($request->room_type)) {
            foreach ($request->room_type as $index => $type) {
                $rooms[] = [
                    'type' => $type,
                    'price' => $request->room_price[$index] ?? 0,
                    'currency' => $request->room_currency[$index] ?? 'USD',
                ];
            }
        }

        // -------------------------
        // Update Hotel
        // -------------------------
        $hotel->update([
            'hotel_name' => $request->hotel_name,
            'star' => $request->star,
            'status' => $request->status,
            'room_type' => $rooms,
            'meal_plan' => $request->meal_plan,
            'meal_costs' => $mealCosts,
            'description' => $request->description,
            'facilities' => $request->facilities,
            'entertainment' => $request->entertainment,
            'city' => $request->city,
            'address' => $request->address,
            'country' => $request->country,
            'hotel_category' => $request->hotel_category,
            'pictures' => $imagePaths,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hotel updated successfully',
            'hotel' => $hotel,
        ]);
    }


    public function show(Hotel $hotel)
    {
        // The $hotel variable is automatically populated with the Hotel model 
        // corresponding to the ID in the URL, thanks to Route Model Binding.

        return view('details.hotel_show', compact('hotel'));
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
