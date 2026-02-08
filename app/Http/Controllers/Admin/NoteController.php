<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $uid = Auth::id();
        $isSuper = auth()->user()->type === 'Super Admin';

        $notes = Note::query()
            ->where(function ($q) use ($uid, $isSuper) {
                $q->where('is_global', 1)
                    ->orWhere('user_id', $uid);

                if ($isSuper) {
                    $q->orWhere('created_by', $uid); // ✅ notes created for others
                }
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
        $users = [];

        if (auth()->user()->type === 'Super Admin') {
            $users = User::select('id', 'name')->orderBy('name')->get();
        }

        return view('notes.create', compact('users'));
    }

    public function store(Request $request)
    {
        $isSuper = auth()->user()->type === 'Super Admin';
        $allowedAudiences = $isSuper ? 'global,me,user' : 'me';

        $request->validate([
            'audience' => 'required|in:' . $allowedAudiences,
            'user_id'  => 'nullable|required_if:audience,user|exists:users,id',

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

        $audience = $request->audience;

        Note::create([
            'created_by' => Auth::id(),
            'is_global'  => $audience === 'global' ? 1 : 0,

            // global => user_id NULL (make sure column nullable)
            // me     => user_id = me
            // user   => user_id = selected user
            'user_id'    => $audience === 'me'
                ? Auth::id()
                : ($audience === 'user' ? $request->user_id : null),

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

        $users = [];
        if (auth()->user()->type === 'Super Admin') {
            $users = User::select('id', 'name')->orderBy('name')->get();
        }

        return view('notes.edit', compact('note', 'users'));
    }

    public function update(Request $request, Note $note)
    {
        $this->authorizeNote($note);

        $isSuper = auth()->user()->type === 'Super Admin';
        $allowedAudiences = $isSuper ? 'global,me,user' : 'me';

        $request->validate([
            'audience' => 'required|in:' . $allowedAudiences,
            'user_id'  => 'nullable|required_if:audience,user|exists:users,id',

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

        // ✅ audience update
        $audience = $isSuper ? $request->audience : 'me';

        $isGlobal = $audience === 'global';
        $newUserId = null;

        if ($audience === 'me') {
            $newUserId = auth()->id();
        } elseif ($audience === 'user') {
            $newUserId = (int) $request->user_id;
        } else {
            $newUserId = null; // global
        }

        $note->update([
            'is_global' => $isGlobal ? 1 : 0,
            'user_id'   => $newUserId,

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
        $this->authorizeView($note);
        return view('notes.show', compact('note'));
    }

    public function destroy(Note $note)
    {
        $this->authorizeNote($note);

        if (!empty($note->attachments)) {
            foreach ($note->attachments as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        $note->delete();

        return redirect()
            ->route('admin.notes.index')
            ->with('success', 'Note deleted!');
    }

    private function authorizeView(Note $note)
    {
        $uid = auth()->id();
        $isSuper = auth()->user()->type === 'Super Admin';

        if ($note->is_global) return;

        if ($note->user_id === $uid) return;

        if ($isSuper && $note->created_by === $uid) return;

        abort(403, 'Unauthorized access');
    }

    /**
     * Manage permissions:
     * - Global => only creator can edit/delete
     * - Personal => owner can edit/delete
     * - Super admin => can manage notes they created for others
     */
    private function authorizeNote(Note $note)
    {
        $uid = auth()->id();
        $isSuper = auth()->user()->type === 'Super Admin';

        if ($note->is_global) {
            if ($note->created_by !== $uid) abort(403, 'Unauthorized access');
            return;
        }

        if ($note->user_id === $uid) return;

        if ($isSuper && $note->created_by === $uid) return;

        abort(403, 'Unauthorized access');
    }
}
