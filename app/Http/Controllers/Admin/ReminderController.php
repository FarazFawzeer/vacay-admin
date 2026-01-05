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
    public function index()
    {
        $reminders = Reminder::where('user_id', Auth::id())
            ->orderBy('due_date', 'asc')
            ->get();

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
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'required|date|after_or_equal:now',
        ]);

        Reminder::create([
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
            'due_date'    => $request->due_date,
            'status'      => 'pending',
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
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'required|date',
            'status'      => 'required|in:pending,completed',
        ]);

        $reminder->update([
            'title'       => $request->title,
            'description' => $request->description,
            'due_date'    => $request->due_date,
            'status'      => $request->status,
            'is_notified' => $request->status === 'completed' ? 1 : $reminder->is_notified,
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
