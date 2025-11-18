<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inclusion;

class InclusionController extends Controller
{
    // Show all 3 types in one page
    public function index()
    {
        $data = [
            'inclusion' => Inclusion::where('type', 'inclusion')->first(),
            'exclusion' => Inclusion::where('type', 'exclusion')->first(),
            'cancellation' => Inclusion::where('type', 'cancellation')->first(),
        ];

        return view('details.inclusion', $data);
    }

    // Store or update based on type
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:inclusion,exclusion,cancellation',
            'heading' => 'required|string|max:255',
            'points' => 'required|array|min:1',
            'note' => 'nullable|string',
        ]);

        // Ensure one record per type
        $record = Inclusion::where('type', $request->type)->first();

        $data = [
            'heading' => $request->heading,
            'points' => $request->points,
            'note' => $request->note,
            'type' => $request->type,
        ];

        if ($record) {
            $record->update($data);
            $message = ucfirst($request->type) . ' updated successfully!';
        } else {
            Inclusion::create($data);
            $message = ucfirst($request->type) . ' created successfully!';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}
