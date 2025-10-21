<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DestinationHighlight;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DestinationHighlightController extends Controller
{
    /**
     * Display a listing of highlights.
     */
    public function index()
    {
        $highlights = DestinationHighlight::with('destination')->latest()->paginate(10);
        $destinations = Destination::all(); // for dropdown in create form

        return view('details.highlight', compact('highlights', 'destinations'));
    }

    /**
     * Store a new highlight.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'place_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('destination_highlights', 'public');
        }

        DestinationHighlight::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Highlight created successfully!',
        ]);
    }

    /**
     * Update an existing highlight.
     */
    public function update(Request $request, DestinationHighlight $destination_highlight)
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'place_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($destination_highlight->image) {
                Storage::disk('public')->delete($destination_highlight->image);
            }
            $validated['image'] = $request->file('image')->store('destination_highlights', 'public');
        }

        $destination_highlight->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Highlight updated successfully!',
        ]);
    }

    /**
     * Delete a highlight.
     */
    public function destroy(DestinationHighlight $destination_highlight)
    {
        if ($destination_highlight->image) {
            Storage::disk('public')->delete($destination_highlight->image);
        }

        $destination_highlight->delete();

        return response()->json([
            'success' => true,
            'message' => 'Highlight deleted successfully!',
        ]);
    }
}
