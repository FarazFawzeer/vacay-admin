<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    // List all notes for the logged-in user
    public function index()
    {
        $userId = auth()->id(); // get current user
        $notes = Note::where('user_id', $userId)
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
            'note'  => 'required|string',
        ]);

        Note::create([
            'user_id' => auth()->id(),
            'title'   => $request->title,
            'note'    => $request->note,
        ]);

        return redirect()->route('admin.notes.index')->with('success', 'Note added successfully!');
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
            'note'  => 'required|string',
        ]);

        $note->update($request->only('title', 'note'));

        return redirect()->route('admin.notes.index')->with('success', 'Note updated!');
    }

    // Delete a note
    public function destroy(Note $note)
    {
        $note->delete();

        return redirect()->route('admin.notes.index')->with('success', 'Note deleted!');
    }
}
