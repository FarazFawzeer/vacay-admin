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

        $protectedVehicleIds = VehicleDetail::orderBy('id', 'asc')->limit(20)->pluck('id')->toArray();
        $isSuper = auth()->user()->type === 'Super Admin';


        return view('details.vehicle', compact('vehicles', 'agents', 'protectedVehicleIds', 'isSuper'));
    }


    // Store new vehicle (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'make'            => 'required|string|max:255',
            'model'           => 'required|string|max:255',
            'name'            => 'required|string|max:255',
            'vehicle_number'   => 'required|string|max:255|unique:vehicle_details,vehicle_number',
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
            'currency'        => 'nullable|string|in:LKR,USD',
            'vehicle_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'sub_image'       => 'nullable|array|max:4',
            'sub_image.*'     => 'image|mimes:jpg,jpeg,png,webp',
            'status'          => 'required',
            'agent_id'        => 'nullable|exists:agents,id',
        ]);

        $data = $request->except(['vehicle_image', 'sub_image']);

        // Default currency to LKR if not provided
        $data['currency'] = $request->currency ?? 'LKR';


        if ($request->hasFile('vehicle_image')) {
            $file = $request->file('vehicle_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Store in storage/app/public/vehicles
            $file->storeAs('vehicles', $filename, 'public');

            // Save path for asset access
            $data['vehicle_image'] = 'vehicles/' . $filename;
        }

        // Sub images
        if ($request->hasFile('sub_image')) {
            $subImages = [];
            foreach ($request->file('sub_image') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Store in storage disk
                $file->storeAs('vehicles/sub_images', $filename, 'public');
                $subImages[] = 'vehicles/sub_images/' . $filename;
            }
            $data['sub_image'] = $subImages;
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

        $this->blockIfProtected($vehicle); // ✅ lock check

        $request->validate([
            'make'             => 'required|string|max:255',
            'model'            => 'nullable|string|max:255',
            'name'             => 'required|string|max:255',
            'vehicle_number'   => 'required|string|max:255|unique:vehicle_details,vehicle_number,' . $id,
            'type'             => 'nullable|string|max:100',
            'condition'        => 'nullable|string|max:100',
            'transmission'     => 'nullable|string|max:50',
            'milage'           => 'nullable',
            'seats'            => 'nullable|integer|min:1',
            'max_seating_capacity' => 'nullable|integer|min:1',
            'luggage_space'    => 'nullable|string|max:255',
            'air_conditioned'  => 'nullable|boolean',
            'helmet'           => 'nullable|boolean',
            'first_aid_kit'    => 'nullable|boolean',
            'fuel_type'        => 'nullable|string|max:50',
            'insurance_type'   => 'nullable|string|max:255',
            'agent_id'         => 'nullable|exists:agents,id',
            'price'            => 'nullable|numeric|min:0',
            'currency'         => 'nullable|string|in:LKR,USD',
            'vehicle_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'sub_image'        => 'nullable|array|max:4',
            'sub_image.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'status'           => 'required|boolean',
        ]);

        $data = $request->except(['vehicle_image', 'sub_image']);
        if ($request->hasFile('vehicle_image')) {
            $file = $request->file('vehicle_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Store in storage/app/public/vehicles
            $file->storeAs('vehicles', $filename, 'public');

            // Save path for asset access
            $data['vehicle_image'] = 'vehicles/' . $filename;
        }

        // Sub images
        if ($request->hasFile('sub_image')) {
            $subImages = [];
            foreach ($request->file('sub_image') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Store in storage disk
                $file->storeAs('vehicles/sub_images', $filename, 'public');
                $subImages[] = 'vehicles/sub_images/' . $filename;
            }
            $data['sub_image'] = $subImages;
        }


        // Ensure booleans are set correctly
        $booleanFields = ['air_conditioned', 'helmet', 'first_aid_kit'];
        foreach ($booleanFields as $field) {
            $data[$field] = $request->has($field) ? (bool)$request->$field : null;
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
        $this->blockIfProtected($vehicle); // ✅ lock check
        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully',
        ]);
    }

    public function toggleStatus(VehicleDetail $vehicle)
    {

        $this->blockIfProtected($vehicle); // ✅ lock check
        $vehicle->status = !$vehicle->status;
        $vehicle->save();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle status updated successfully!',
            'new_status' => $vehicle->status
        ]);
    }

    private function protectedVehicleIds()
    {
        // first 20 records by ID (oldest 20)
        return \App\Models\VehicleDetail::orderBy('id', 'asc')->limit(20)->pluck('id')->toArray();
    }

    private function isSuperAdmin(): bool
    {
        return auth()->user()->type === 'Super Admin';
    }

    private function blockIfProtected(\App\Models\VehicleDetail $vehicle)
    {
        if (!$this->isSuperAdmin() && in_array($vehicle->id, $this->protectedVehicleIds())) {
            abort(403, 'This vehicle is locked. Only Super Admin can modify it.');
        }
    }
}
