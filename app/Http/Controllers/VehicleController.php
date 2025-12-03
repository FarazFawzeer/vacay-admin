<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VehicleDetail;
use App\Models\Agent;

class VehicleController extends Controller
{
    // Show list of vehicles
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $vehicles = VehicleDetail::query()
                ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('make', 'like', "%{$request->search}%")
                    ->orWhere('model', 'like', "%{$request->search}%"))
                ->when(isset($request->status), fn($q) => $q->where('status', $request->status))
                ->get();

            return response()->json([
                'vehicles' => $vehicles
            ]);
        }

        $vehicles = VehicleDetail::paginate(10);
        $agents = Agent::orderBy('name')->get();
        return view('details.vehicle', compact('vehicles', 'agents'));
    }


    // Store new vehicle (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'make'            => 'required|string|max:255',
            'model'           => 'required|string|max:255',
            'name'            => 'required|string|max:255',
            'type'            => 'nullable|string|max:100',
            'condition'       => 'nullable|string|max:100',
            'transmission'    => 'nullable|string|max:50',
            'milage'          => 'nullable',
            'seats'           => 'nullable|integer|min:1',
            'luggage_space'   => 'nullable|string|max:255',
            'air_conditioned' => 'nullable|boolean',
            'helmet'          => 'nullable|boolean',
            'first_aid_kit'   => 'nullable|boolean',
            'fuel_type'       => 'nullable|string|max:50',
            'insurance_type'  => 'nullable|string|max:255',
            'price'           => 'nullable|numeric|min:0',
            'vehicle_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'sub_image'       => 'nullable|array|max:4',
            'sub_image.*'     => 'image|mimes:jpg,jpeg,png,webp',
            'status'          => 'required',
            'agent_id'        => 'nullable|exists:agents,id',
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
            'make'            => 'required|string|max:255',
            'model'           => 'required|string|max:255',
            'name'            => 'required|string|max:255',
            'type'            => 'nullable|string|max:100',
            'condition'       => 'nullable|string|max:100',
            'transmission'    => 'nullable|string|max:50',
            'milage'          => 'nullable',
            'seats'           => 'nullable|integer|min:1',
            'max_seating_capacity' => 'nullable|integer|min:1',
            'luggage_space'   => 'nullable|string|max:255',
            'air_conditioned' => 'nullable|boolean',
            'helmet'          => 'nullable|boolean',
            'first_aid_kit'   => 'nullable|boolean',
            'fuel_type'       => 'nullable|string|max:50',          // added
            'insurance_type'  => 'nullable|string|max:255',         // added
            'agent_id'        => 'nullable|exists:agents,id',       // added
            'price'           => 'nullable|numeric|min:0',
            'vehicle_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'sub_image'       => 'nullable|array|max:4',
            'sub_image.*'     => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'status'          => 'required',
        ]);

        $data = $request->except(['vehicle_image', 'sub_image']);

        // ✅ Update main image if uploaded
        if ($request->hasFile('vehicle_image')) {
            if ($vehicle->vehicle_image && \Storage::disk('public')->exists($vehicle->vehicle_image)) {
                \Storage::disk('public')->delete($vehicle->vehicle_image);
            }
            $data['vehicle_image'] = $request->file('vehicle_image')->store('vehicles', 'public');
        }

        // ✅ Replace sub images if new ones uploaded
        if ($request->hasFile('sub_image')) {
            if (!empty($vehicle->sub_image)) {
                foreach ($vehicle->sub_image as $oldImg) {
                    if (\Storage::disk('public')->exists($oldImg)) {
                        \Storage::disk('public')->delete($oldImg);
                    }
                }
            }

            $newImages = [];
            foreach ($request->file('sub_image') as $file) {
                $newImages[] = $file->store('vehicles/sub_images', 'public');
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

    public function show(VehicleDetail $vehicle)
    {
        $agents = Agent::orderBy('name')->get(); // optional if you want agent details
        return view('details.vehicle_show', compact('vehicle', 'agents'));
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
