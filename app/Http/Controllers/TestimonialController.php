<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    /**
     * Display a listing of testimonials with filters.
     */
    public function index(Request $request)
    {
        $query = Testimonial::query();

        // Filter by source
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or message
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $testimonials = $query->latest()->paginate(10);

        // Distinct sources for sidebar filter
        $sources = Testimonial::select('source')->distinct()->pluck('source');

        // For AJAX table reload
        if ($request->ajax()) {
            return view('testimonials.table', compact('testimonials'))->render();
        }

        return view('testimonials.view', compact('testimonials', 'sources'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('testimonials.create');
    }

    /**
     * Store a new testimonial.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'source'    => 'nullable|string|max:255',
            'rating'    => 'nullable|integer|min:1|max:5',
            'message'   => 'required|string',
            'postedate' => 'nullable|date',
            'link'      => 'nullable|url|max:255', // <-- added
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('testimonials', 'public');
        }

        $data['status'] = 1; // default active
        Testimonial::create($data);

        return redirect()->route('admin.testimonials.create')
            ->with('success', 'Testimonial added successfully!');
    }


    /**
     * Show edit form.
     */
    public function edit(Testimonial $testimonial)
    {
        return view('testimonials.edit', compact('testimonial'));
    }

    /**
     * Update existing testimonial.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'source'    => 'nullable|string|max:255',
            'rating'    => 'nullable|integer|min:1|max:5',
            'message'   => 'required|string',
            'postedate' => 'nullable|date',
            'link'      => 'nullable|url|max:255', // <-- added
        ]);

        // Handle new image (delete old if replaced)
        if ($request->hasFile('image')) {
            if ($testimonial->image) {
                Storage::disk('public')->delete($testimonial->image);
            }
            $data['image'] = $request->file('image')->store('testimonials', 'public');
        }

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully!');
    }


    /**
     * Delete a testimonial (AJAX or standard).
     */
    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->image) {
            Storage::disk('public')->delete($testimonial->image);
        }

        $testimonial->delete();

        return response()->json(['success' => true, 'message' => 'Testimonial deleted successfully!']);
    }

    /**
     * Toggle testimonial active/inactive (AJAX).
     */
    public function toggleStatus(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->status = $request->status;
        $testimonial->save();

        return response()->json([
            'success' => true,
            'message' => 'Testimonial status updated successfully!'
        ]);
    }
}
