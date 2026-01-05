<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    /**
     * List all reminders
     */
    public function index(Request $request)
    {
        $query = Reminder::query();

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

    /**
     * Store reminder
     */

    public function store(Request $request)
    {
        $request->validate([
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

        Reminder::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'attachments' => $attachments, // 👈 JSON stored
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
                // Delete file from storage
                \Storage::disk('public')->delete($removeFile);

                // Remove from array
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

    /**
     * Delete reminder
     */
    public function destroy(Reminder $reminder)
    {
        $this->authorizeReminder($reminder);

        $reminder->delete();

        return back()->with('success', 'Reminder deleted successfully.');
    }

    /**
     * Authorization check
     */
    private function authorizeReminder(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
    }
}
