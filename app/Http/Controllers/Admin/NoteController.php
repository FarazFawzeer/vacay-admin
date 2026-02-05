<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $notes = Note::query()
            ->where(function ($q) use ($userId) {
                $q->where('is_global', 1)
                  ->orWhere('user_id', $userId);
            })
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

    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'audience' => 'required|in:global,me',
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

        $isGlobal = $request->audience === 'global';

        Note::create([
            'created_by' => Auth::id(),
            'is_global'  => $isGlobal ? 1 : 0,
            'user_id'    => $isGlobal ? null : Auth::id(),

            'title' => $request->title,
            'note' => $request->note,
            'attachments' => $attachments,
        ]);

        return redirect()
            ->route('admin.notes.index')
            ->with('success', 'Note added successfully!');
    }

    public function edit(Note $note)
    {
        $this->authorizeNote($note);
        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note)
    {
        $this->authorizeNote($note);

        $request->validate([
            'title' => 'required|string|max:255',
            'note' => 'required|string',
            'attachments.*' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $attachments = $note->attachments ?? [];

        if ($request->filled('remove_attachments')) {
            foreach ($request->remove_attachments as $removeFile) {
                Storage::disk('public')->delete($removeFile);
                $attachments = array_values(array_diff($attachments, [$removeFile]));
            }
        }

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

    public function show(Note $note)
    {
        // allow viewing global notes for everyone, but personal notes only owner
        if (!$note->is_global && $note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('notes.show', compact('note'));
    }

    public function destroy(Note $note)
    {
        $this->authorizeNote($note);

        // delete files
        if (!empty($note->attachments)) {
            foreach ($note->attachments as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        $note->delete();

        return redirect()->route('admin.notes.index')->with('success', 'Note deleted!');
    }

    /**
     * Only creator can manage global notes, only owner can manage personal notes
     */
    private function authorizeNote(Note $note)
    {
        $uid = Auth::id();

        if ($note->is_global) {
            if ($note->created_by !== $uid) {
                abort(403, 'Unauthorized access');
            }
            return;
        }

        if ($note->user_id !== $uid) {
            abort(403, 'Unauthorized access');
        }
    }
}
