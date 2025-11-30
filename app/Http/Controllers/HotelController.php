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

            $query->where(function ($q) use ($search) {
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
            'status' => 'required|in:1,0,2',
            'description' => 'nullable|string',
            'room_type' => 'nullable|array',
            'room_type.*' => 'nullable|string|max:255',
            'meal_plan' => 'nullable|array',
            'meal_plan.*' => 'nullable|string|max:255',
            'room_price' => 'nullable|array',
            'room_price.*' => 'nullable|numeric',
            'room_currency' => 'nullable|array',
            'room_currency.*' => 'nullable|string|max:5',
            'room_image' => 'nullable|array',
            'room_image.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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
            'contact_person' => 'nullable|string|max:255',
            'landline_number' => 'nullable|string|max:50',
            'mobile_number' => 'nullable|string|max:50',
        ]);

        // Handle hotel main pictures
        $imagePaths = [];
        if ($request->hasFile('pictures')) {
            foreach ($request->file('pictures') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('hotel'), $filename);
                $imagePaths[] = 'hotel/' . $filename;
            }
        }

        // Handle meal costs
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

        // Handle room types with individual images
        $rooms = [];
        if ($request->room_type && is_array($request->room_type)) {
            foreach ($request->room_type as $index => $type) {
                $imagePath = null;

                if ($request->hasFile('room_image') && isset($request->file('room_image')[$index])) {
                    $file = $request->file('room_image')[$index];
                    if ($file && $file->isValid()) {
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('hotel/room_types'), $filename);
                        $imagePath = 'hotel/room_types/' . $filename;
                    }
                }

                $rooms[] = [
                    'type' => $type,
                    'meal_plan' => $request->meal_plan[$index] ?? null,
                    'price' => $request->room_price[$index] ?? 0,
                    'currency' => $request->room_currency[$index] ?? 'USD',
                    'image' => $imagePath,
                ];
            }
        }

        // Create hotel
        $hotel = Hotel::create([
            'hotel_name' => $request->hotel_name,
            'star' => $request->star,
            'status' => $request->status,
            'room_type' => $rooms,
            'meal_costs' => $mealCosts,
            'description' => $request->description,
            'facilities' => $request->facilities,
            'entertainment' => $request->entertainment,
            'city' => $request->city,
            'address' => $request->address,
            'country' => $request->country,
            'hotel_category' => $request->hotel_category,
            'pictures' => $imagePaths,
            'contact_person' => $request->contact_person,
            'landline_number' => $request->landline_number,
            'mobile_number' => $request->mobile_number,
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
            'status' => 'required|in:1,0,2',
            'description' => 'nullable|string',
            'room_type' => 'nullable|array',
            'room_type.*' => 'nullable|string|max:255',
            'meal_plan' => 'nullable|array',
            'meal_plan.*' => 'nullable|string|max:255',
            'room_price' => 'nullable|array',
            'room_price.*' => 'nullable|numeric',
            'room_currency' => 'nullable|array',
            'room_currency.*' => 'nullable|string|max:5',
            'room_image' => 'nullable|array',
            'room_image.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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
            'contact_person' => 'nullable|string|max:255',
            'landline_number' => 'nullable|string|max:50',
            'mobile_number' => 'nullable|string|max:50',
        ]);

        // -------------------------
        // Handle hotel pictures
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

            $imagePaths = [];
            foreach ($request->file('pictures') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('hotel'), $filename);
                $imagePaths[] = 'hotel/' . $filename;
            }
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
        // Handle Room Types with Images
        // -------------------------
        $roomTypes = $request->input('room_type', []);
        $mealPlans = $request->input('meal_plan', []);
        $prices = $request->input('room_price', []);
        $currencies = $request->input('room_currency', []);
        $roomImages = $request->file('room_image', []);

        $finalRoomTypes = [];

        foreach ($roomTypes as $index => $type) {
            $imagePath = null;

            // Handle uploaded room image
            if (isset($roomImages[$index]) && $roomImages[$index] && $roomImages[$index]->isValid()) {
                $filename = time() . '_' . uniqid() . '.' . $roomImages[$index]->getClientOriginalExtension();
                $roomImages[$index]->move(public_path('hotel/room_types'), $filename);
                $imagePath = 'hotel/room_types/' . $filename;
            } else {
                // keep existing image if updating and not uploading a new one
                $existingRooms = $hotel->room_type ?? [];
                $imagePath = $existingRooms[$index]['image'] ?? null;
            }

            $finalRoomTypes[] = [
                'type' => $type,
                'meal_plan' => $mealPlans[$index] ?? null,
                'price' => $prices[$index] ?? 0,
                'currency' => $currencies[$index] ?? 'USD',
                'image' => $imagePath,
            ];
        }

        // -------------------------
        // Update hotel
        // -------------------------
        $hotel->update([
            'hotel_name' => $request->hotel_name,
            'star' => $request->star,
            'status' => $request->status,
            'room_type' => $finalRoomTypes,
            'meal_costs' => $mealCosts,
            'description' => $request->description,
            'facilities' => $request->facilities,
            'entertainment' => $request->entertainment,
            'city' => $request->city,
            'address' => $request->address,
            'country' => $request->country,
            'hotel_category' => $request->hotel_category,
            'pictures' => $imagePaths,
            'contact_person' => $request->contact_person,
            'landline_number' => $request->landline_number,
            'mobile_number' => $request->mobile_number,
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
