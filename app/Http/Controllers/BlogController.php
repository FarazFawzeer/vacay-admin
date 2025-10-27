<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::query();

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Status filter: convert 'active'/'inactive' to 1/0
       if ($request->filled('status')) {
        $query->where('status', $request->status);
    }


        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $blogs = $query->latest()->paginate(10);

        $types = BlogPost::select('type')->distinct()->pluck('type');

        if ($request->ajax()) {
            return view('blog.table', compact('blogs'))->render();
        }

        return view('blog.view', compact('blogs', 'types'));
    }



    // Show create form
    public function create()
    {
        return view('blog.create');
    }

    // Store new blog
    // Store new blog
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'posted_time'   => 'nullable|date',
            'likes_count'   => 'nullable|integer|min:0',
            'hashtags'      => 'nullable|string', // single text input (comma-separated)
            'image_post.*'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'type'          => 'required|string|max:255',
        ]);

        // Handle multiple images
        $imagePaths = [];
        if ($request->hasFile('image_post')) {
            foreach ($request->file('image_post') as $file) {
                $path = $file->store('blogs', 'public');
                $imagePaths[] = $path;
            }
        }

        // Handle hashtags
        $hashtags = array_filter(explode(',', $request->input('hashtags') ?? ''));

        $data['image_post'] = $imagePaths; // Laravel auto-encodes if casted
        $data['hashtags'] = $hashtags;
        $data['status'] = 1; // default published

        BlogPost::create($data);

        return redirect()->route('admin.blogs.create')->with('success', 'Blog post created successfully!');
    }

    // Show single blog
    public function show(BlogPost $blog)
    {
        return view('blog.show', compact('blog'));
    }

    // Edit blog
    public function edit(BlogPost $blog)
    {
        return view('blog.edit', compact('blog'));
    }

    // Update blog

    public function update(Request $request, BlogPost $blog)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'posted_time'   => 'nullable|date',
            'likes_count'   => 'nullable|integer|min:0',
            'hashtags'      => 'nullable|string',
            'image_post.*'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'type'          => 'required|string|max:255',
        ]);

        // Load existing images
        $imagePaths = $blog->image_post ?? [];

        // Handle removed images
        $removeImages = json_decode($request->input('remove_images'), true) ?? [];
        foreach ($removeImages as $index) {
            if (isset($imagePaths[$index])) {
                Storage::disk('public')->delete($imagePaths[$index]);
                unset($imagePaths[$index]);
            }
        }
        $imagePaths = array_values($imagePaths); // reindex

        // Handle new uploads
        if ($request->hasFile('image_post')) {
            foreach ($request->file('image_post') as $file) {
                $path = $file->store('blogs', 'public');
                $imagePaths[] = $path;
            }
        }

        // Hashtags: split by comma
        $hashtags = array_filter(array_map('trim', explode(',', $request->input('hashtags') ?? '')));

        $blog->update([
            'title'       => $data['title'],
            'description' => $data['description'],
            'posted_time' => $data['posted_time'],
            'likes_count' => $data['likes_count'] ?? 0,
            'type'        => $data['type'],
            'image_post'  => $imagePaths,
            'hashtags'    => $hashtags,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully!');
    }


    // Delete blog
    public function destroy(BlogPost $blog)
    {
        $blog->delete();

        return response()->json(['success' => true, 'message' => 'Blog post deleted successfully!']);
    }

    // Toggle publish status (AJAX)
    public function toggleStatus(BlogPost $blog, Request $request)
    {
        $blog->status = $request->status;
        $blog->save();

        return response()->json(['success' => true, 'message' => 'Blog status updated successfully!']);
    }
}
