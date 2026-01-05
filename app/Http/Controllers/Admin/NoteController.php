<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    // List all notes for the logged-in user
    // List all notes for the logged-in user with search filter
    public function index(Request $request)
    {
        $userId = auth()->id();

        $notes = Note::where('user_id', $userId)

            // 🔍 Search filter
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                        ->orWhere('note', 'like', '%' . $request->search . '%');
                });
            })

            ->orderBy('created_at', 'desc')
            ->get();

        return view('notes.index', compact('notes'));
    }
    // Show form to create a new note
    public function create()
    {
        return view('notes.create');
    }

    // Store new note
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'note' => 'required|string',
            'attachments.*' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $attachments = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('notes', 'public');
            }
        }

        Note::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'note' => $request->note,
            'attachments' => $attachments, // 👈 JSON stored
        ]);

        return redirect()
            ->route('admin.notes.index')
            ->with('success', 'Note added successfully!');
    }


    // Show form to edit a note
    public function edit(Note $note)
    {
        return view('notes.edit', compact('note'));
    }

    // Update a note
    public function update(Request $request, Note $note)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'note' => 'required|string',
            'attachments.*' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        // Existing attachments
        $attachments = $note->attachments ?? [];

        // Remove selected attachments
        if ($request->filled('remove_attachments')) {
            foreach ($request->remove_attachments as $removeFile) {
                Storage::disk('public')->delete($removeFile);
                $attachments = array_values(array_diff($attachments, [$removeFile]));
            }
        }

        // Add new attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('notes', 'public');
            }
        }

        $note->update([
            'title' => $request->title,
            'note' => $request->note,
            'attachments' => $attachments,
        ]);

        return redirect()
            ->route('admin.notes.index')
            ->with('success', 'Note updated!');
    }

    // Delete a note
    public function destroy(Note $note)
    {
        $note->delete();

        return redirect()->route('admin.notes.index')->with('success', 'Note deleted!');
    }
}
