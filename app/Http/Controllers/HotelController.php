<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;

class HotelController extends Controller
{
    // Show list
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
            'star' => 'nullable|integer|min:1|max:7', // optional
            'status' => 'required|in:1,0',
        ]);

        $hotel = Hotel::create($request->only('hotel_name', 'star', 'status'));

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
        ]);

        $hotel->update($request->only('hotel_name', 'star', 'status'));

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
        $hotel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hotel deleted successfully',
        ]);
    }
}
