<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\DestinationHighlight;
use Illuminate\Support\Facades\Validator;

class DestinationController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->get('search');

        $destinations = Destination::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhereJsonContains('program_points', [['point' => $search]]);
        })
            ->latest()
            ->paginate(10);

        // If AJAX request, return only table HTML
        if ($request->ajax()) {
            return view('details.partials.destination-table', compact('destinations'))->render();
        }

        return view('details.destination', compact('destinations'));
    }



    public function create()
    {
        $destinations = Destination::latest()->paginate(10);
        return view('details.destination', compact('destinations'));
    }

    /**
     * Store a new destination.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'program_points' => 'nullable|array',
            'program_points.*' => 'nullable|string|max:255',
        ]);

        $programPoints = collect($validated['program_points'] ?? [])
            ->filter()
            ->map(fn($point) => ['point' => $point])
            ->values()
            ->toArray();

        try {
            Destination::create([
                'name' => $validated['name'],
                'program_points' => $programPoints,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Destination created successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create destination. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'program_points' => 'nullable|array',
            'program_points.*' => 'nullable|string|max:500',
        ]);

        $programPoints = collect($validated['program_points'] ?? [])
            ->filter()
            ->map(fn($point) => ['point' => $point])
            ->values()
            ->toArray();

        try {
            $destination->update([
                'name' => $validated['name'],
                'program_points' => $programPoints,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Destination updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update destination. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Destination $destination)
    {
        try {
            $destination->delete();

            return response()->json([
                'success' => true,
                'message' => 'Destination deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete destination. ' . $e->getMessage(),
            ], 500);
        }
    }


    public function getDetails($id)
    {
        $destination = Destination::findOrFail($id);

        return response()->json([
            'program_points' => $destination->program_points,
            'highlights' => $destination->highlights()->get(['id', 'place_name', 'description', 'image']),
        ]);
    }
}
