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
            'vehicle_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'sub_image'             => 'nullable|array|max:4',
            'sub_image.*'           => 'image|mimes:jpg,jpeg,png,webp',
            'type'                  => 'nullable|string|max:100',
            'status'                => 'required',
        ]);

        $data = $request->except(['vehicle_image', 'sub_image']);

        // ✅ Handle main vehicle image upload
        if ($request->hasFile('vehicle_image')) {
            $path = $request->file('vehicle_image')->store('vehicles', 'public');
            $data['vehicle_image'] = $path;
        }

        // ✅ Handle multiple sub images upload
        if ($request->hasFile('sub_image')) {
            $subImages = [];
            foreach ($request->file('sub_image') as $file) {
                $path = $file->store('vehicles/sub_images', 'public');
                $subImages[] = $path;
            }
            $data['sub_image'] = $subImages; // JSON encoded automatically
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
            'vehicle_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'sub_image'             => 'nullable|array|max:4',
            'sub_image.*'           => 'image|mimes:jpg,jpeg,png,webp',
            'type'                  => 'nullable|string|max:100',
            'status'                => 'required',
        ]);

        $data = $request->except(['vehicle_image', 'sub_image']);

        // ✅ Update main image if uploaded
        if ($request->hasFile('vehicle_image')) {
            // delete old image if exists
            if ($vehicle->vehicle_image && \Storage::disk('public')->exists($vehicle->vehicle_image)) {
                \Storage::disk('public')->delete($vehicle->vehicle_image);
            }

            $path = $request->file('vehicle_image')->store('vehicles', 'public');
            $data['vehicle_image'] = $path;
        }

        // ✅ Replace sub images if new ones uploaded
        if ($request->hasFile('sub_image')) {
            // delete old sub images
            if (!empty($vehicle->sub_image)) {
                foreach ($vehicle->sub_image as $oldImg) {
                    if (\Storage::disk('public')->exists($oldImg)) {
                        \Storage::disk('public')->delete($oldImg);
                    }
                }
            }

            // upload new sub images
            $newImages = [];
            foreach ($request->file('sub_image') as $file) {
                $path = $file->store('vehicles/sub_images', 'public');
                $newImages[] = $path;
            }

            $data['sub_image'] = $newImages;
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
