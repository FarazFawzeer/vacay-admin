<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visa;
use Illuminate\Support\Facades\Storage;

class VisaController extends Controller
{
    // Show all visas
    public function index()
    {
        $visas = Visa::orderBy('updated_at', 'desc')->paginate(10);
        return view('details.visa', compact('visas'));
    }

    // Store new visa
    public function store(Request $request)
    {
        $request->validate([
            'country' => 'required|string|max:255',
            'visa_type' => 'required|string',
            'visa_details' => 'nullable|string',
            'documents' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('documents')) {
            $imagePath = $request->file('documents')->store('visa', 'public');
        }

        $visa = Visa::create([
            'country' => $request->country,
            'visa_type' => $request->visa_type,
            'visa_details' => $request->visa_details,
            'documents' => $imagePath,
        ]);

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
            'documents' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $visa->documents;

        if ($request->hasFile('documents')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('documents')->store('visa', 'public');
        }

        $visa->update([
            'country' => $request->country,
            'visa_type' => $request->visa_type,
            'visa_details' => $request->visa_details,
            'documents' => $imagePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Visa record updated successfully!',
            'visa' => $visa,
        ]);
    }

    // Delete visa
    public function destroy($id)
    {
        $visa = Visa::findOrFail($id);

        if ($visa->documents) {
            Storage::disk('public')->delete($visa->documents);
        }

        $visa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Visa record deleted successfully!',
        ]);
    }
}
