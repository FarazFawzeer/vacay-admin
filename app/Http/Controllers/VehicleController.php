<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VehicleDetail;

class VehicleController extends Controller
{
    // Show list of vehicles
    public function index()
    {
        $vehicles = VehicleDetail::orderBy('updated_at', 'desc')->paginate(10);

        return view('details.vehicle', compact('vehicles'));
    }

    // Store new vehicle (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'make'                  => 'required|string|max:255',
            'model'                 => 'required|string|max:255',
            'condition'             => 'nullable|string|max:100',
            'seats'                 => 'nullable|integer|min:1',
            'max_seating_capacity'  => 'nullable|integer|min:1',
            'luggage_space'         => 'nullable|string|max:255',
            'air_conditioned'       => 'nullable|boolean',
            'helmet'                => 'nullable|boolean',
            'first_aid_kit'         => 'nullable|boolean',
            'transmission'          => 'nullable|string|max:50',
            'milage'                => 'nullable|numeric|min:0',
            'price'                 => 'nullable|numeric|min:0',
            'label'                 => 'nullable|string|max:255',
            'name'                  => 'required|string|max:255',
            'availability'          => 'nullable|boolean',
            'vehicle_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'type'                  => 'nullable|string|max:100',
            'status'                => 'required',
        ]);

        $data = $request->except('vehicle_image');

        // Handle image upload
        if ($request->hasFile('vehicle_image')) {
            $path = $request->file('vehicle_image')->store('vehicles', 'public');
            $data['vehicle_image'] = $path;
        }

        $vehicle = VehicleDetail::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle created successfully',
            'vehicle' => $vehicle,
        ]);
    }

    // Update vehicle (AJAX)
    public function update(Request $request, $id)
    {
        $vehicle = VehicleDetail::findOrFail($id);

        $request->validate([
            'make'                  => 'required|string|max:255',
            'model'                 => 'required|string|max:255',
            'condition'             => 'nullable|string|max:100',
            'seats'                 => 'nullable|integer|min:1',
            'max_seating_capacity'  => 'nullable|integer|min:1',
            'luggage_space'         => 'nullable|string|max:255',
            'air_conditioned'       => 'nullable|boolean',
            'helmet'                => 'nullable|boolean',
            'first_aid_kit'         => 'nullable|boolean',
            'transmission'          => 'nullable|string|max:50',
            'milage'                => 'nullable|numeric|min:0',
            'price'                 => 'nullable|numeric|min:0',
            'label'                 => 'nullable|string|max:255',
            'name'                  => 'required|string|max:255',
            'availability'          => 'nullable|boolean',
            'vehicle_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'type'                  => 'nullable|string|max:100',
            'status'                => 'required',
        ]);

        $data = $request->except('vehicle_image');

        // Update image if uploaded
        if ($request->hasFile('vehicle_image')) {
            $path = $request->file('vehicle_image')->store('vehicles', 'public');
            $data['vehicle_image'] = $path;
        }

        $vehicle->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle updated successfully',
            'vehicle' => $vehicle,
        ]);
    }

    // Delete vehicle (AJAX)
    public function destroy($id)
    {
        $vehicle = VehicleDetail::findOrFail($id);
        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully',
        ]);
    }

    public function toggleStatus(VehicleDetail $vehicle)
    {
        $vehicle->status = !$vehicle->status;
        $vehicle->save();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle status updated successfully!',
            'new_status' => $vehicle->status
        ]);
    }
}
