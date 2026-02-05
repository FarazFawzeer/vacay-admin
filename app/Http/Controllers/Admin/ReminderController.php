<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReminderController extends Controller
{
    /**
     * List reminders visible to current user (Global + My reminders)
     */
    public function index(Request $request)
    {
        $query = Reminder::query()
            ->where(function ($q) {
                $q->where('is_global', 1)
                    ->orWhere('user_id', Auth::id());
            });

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'overdue') {
                $query->where('due_date', '<', now())
                    ->where('status', 'pending');
            } else {
                $query->where('status', $request->status);
            }
        }

        $reminders = $query->orderBy('due_date')->get();

        return view('reminders.index', compact('reminders'));
    }

    /**
     * Show create reminder form
     */
    public function create()
    {
        return view('reminders.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'audience' => 'required|in:global,me',

            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:now',
            'attachments.*' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('reminders', 'public');
            }
        }

        $isGlobal = $request->audience === 'global';

        Reminder::create([
            'created_by' => Auth::id(),
            'is_global'  => $isGlobal ? 1 : 0,
            'user_id'    => $isGlobal ? null : Auth::id(), // ✅ only me

            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'attachments' => $attachments,
            'status' => 'pending',
            'is_notified' => 0,
        ]);

        return redirect()
            ->route('admin.reminders.index')
            ->with('success', 'Reminder created successfully.');
    }


    /**
     * Show edit form
     */
    public function edit(Reminder $reminder)
    {
        $this->authorizeReminder($reminder);
        return view('reminders.edit', compact('reminder'));
    }

    /**
     * Update reminder
     */
    public function update(Request $request, Reminder $reminder)
    {
        $this->authorizeReminder($reminder);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'attachments.*' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        // Existing attachments
        $attachments = $reminder->attachments ?? [];

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
                $attachments[] = $file->store('reminders', 'public');
            }
        }

        $reminder->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'attachments' => $attachments,
            'is_notified' => $request->status === 'completed'
                ? 1
                : $reminder->is_notified,
        ]);

        return redirect()
            ->route('admin.reminders.index')
            ->with('success', 'Reminder updated successfully.');
    }

    /**
     * Mark reminder as completed (quick action)
     */
    public function complete(Reminder $reminder)
    {
        $this->authorizeReminder($reminder);

        $reminder->update([
            'status' => 'completed'
        ]);

        return back()->with('success', 'Reminder marked as completed.');
    }

    public function show(Reminder $reminder)
    {
        $this->authorizeReminder($reminder);

        return view('reminders.show', compact('reminder'));
    }

    /**
     * Delete reminder
     */
    public function destroy(Reminder $reminder)
    {
        $this->authorizeReminder($reminder);

        // Optional: delete attachments from disk too
        if (!empty($reminder->attachments)) {
            foreach ($reminder->attachments as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        $reminder->delete();

        return back()->with('success', 'Reminder deleted successfully.');
    }

    /**
     * Authorization check
     * - Global reminder => only creator can edit/delete/view
     * - User reminder => only assigned user can edit/delete/view
     */
    private function authorizeReminder(Reminder $reminder)
    {
        $uid = Auth::id();

        if ($reminder->is_global) {
            if ($reminder->created_by !== $uid) {
                abort(403, 'Unauthorized access');
            }
            return;
        }

        if ($reminder->user_id !== $uid) {
            abort(403, 'Unauthorized access');
        }
    }
}
