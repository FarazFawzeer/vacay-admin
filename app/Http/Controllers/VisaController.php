<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visa;
use App\Models\Agent;
use Illuminate\Support\Facades\Storage;

class VisaController extends Controller
{
    public function index(Request $request)
    {
        $query = Visa::with('agents')->orderBy('updated_at', 'desc');

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('agent')) {
            $agentId = $request->agent;
            $query->whereHas('agents', function ($q) use ($agentId) {
                $q->where('agents.id', $agentId); // Fix ambiguity here
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('country', 'like', "%{$search}%")
                    ->orWhere('visa_type', 'like', "%{$search}%")
                    ->orWhere('visa_details', 'like', "%{$search}%");
            });
        }

        $visas = $query->paginate(10);

        if ($request->ajax()) {
            return view('details.visa_table', compact('visas'))->render(); // Partial
        }

        $agents = Agent::orderBy('name')->get();
        $countries = json_decode(file_get_contents(resource_path('data/countries.json')), true);

        return view('details.visa', compact('visas', 'agents', 'countries'));
    }


    // Store new visa
    public function store(Request $request)
    {
        $request->validate([
            'country' => 'required|string|max:255',
            'visa_type' => 'required|string',
            'visa_details' => 'nullable|string',
            'documents' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'note' => 'nullable|string',
            'agents' => 'nullable|array',
        ]);

        $imagePath = null;
        if ($request->hasFile('documents')) {
            $imagePath = $request->file('documents')->store('visa', 'public');
        }

        // Create visa with user_id
        $visa = Visa::create([
            'country' => $request->country,
            'visa_type' => $request->visa_type,
            'visa_details' => $request->visa_details,
            'documents' => $imagePath,
            'note' => $request->note,
            'user_id' => auth()->id(),   // <--- Save logged user ID
        ]);

        // Attach selected agents
        if ($request->agents) {
            $visa->agents()->attach($request->agents);
        }

        return response()->json([
            'success' => true,
            'message' => 'Visa record added successfully!',
            'visa' => $visa,
        ]);
    }

    // Update visa
    public function update(Request $request, $id)
    {
        $visa = Visa::findOrFail($id);

        $request->validate([
            'country' => 'required|string|max:255',
            'visa_type' => 'required|string',
            'visa_details' => 'nullable|string',
            'note' => 'nullable|string',
            'documents' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'agents' => 'array'
        ]);

        // Handle image
        $imagePath = $visa->documents;
        if ($request->hasFile('documents')) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            $imagePath = $request->file('documents')->store('visa', 'public');
        }

        // Update main data
        $visa->update([
            'country' => $request->country,
            'visa_type' => $request->visa_type,
            'visa_details' => $request->visa_details,
            'documents' => $imagePath,
            'note' => $request->note,
            'user_id' => auth()->id(),
        ]);

        // Sync agents in pivot table
        $visa->agents()->sync($request->agents ?? []);

        return response()->json([
            'success' => true,
            'message' => 'Visa record updated successfully!',
            'visa' => $visa,
        ]);
    }

    public function show($id)
    {
        $visa = Visa::with('agents', 'user')->findOrFail($id);



        return view('details.show_visa', compact('visa'));
    }

    // Delete visa
    public function destroy($id)
    {
        $visa = Visa::find($id); // Use find instead of findOrFail
        if (!$visa) {
            return response()->json([
                'success' => false,
                'message' => 'Visa record not found.'
            ], 404);
        }

        // Optional: delete documents if exists
        if ($visa->documents && file_exists(storage_path('app/public/' . $visa->documents))) {
            unlink(storage_path('app/public/' . $visa->documents));
        }

        $visa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Visa deleted successfully.'
        ]);
    }
}
