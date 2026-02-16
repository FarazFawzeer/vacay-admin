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
        $isSuper = auth()->user()->type === 'Super Admin';

        $query = Reminder::query()
            ->where(function ($q) use ($isSuper) {
                $q->where('is_global', 1)
                    ->orWhere('user_id', auth()->id());

                if ($isSuper) {
                    $q->orWhere('created_by', auth()->id()); // ✅ see reminders you created for others
                }
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
        $users = [];

        if (auth()->user()->type === 'Super Admin') {
            $users = User::select('id', 'name')->orderBy('name')->get();
        }

        return view('reminders.create', compact('users'));
    }


    public function store(Request $request)
    {
        $isSuper = auth()->user()->type === 'Super Admin';

        // Super admin can do: global, me, user
        // Normal admin can do: me only
        $allowedAudiences = $isSuper ? 'global,me,user' : 'me';

        $request->validate([
            'audience' => 'required|in:' . $allowedAudiences,
            'user_id'  => 'nullable|required_if:audience,user|exists:users,id',

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

        $audience = $request->audience;

        Reminder::create([
            'created_by' => Auth::id(),
            'is_global'  => $audience === 'global' ? 1 : 0,

            // For "me" => user_id = me
            // For "user" => user_id = selected user
            // For "global" => user_id = NULL (IMPORTANT your DB column must allow null)
            'user_id'    => $audience === 'me' ? Auth::id() : ($audience === 'user' ? $request->user_id : null),

            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'attachments' => $attachments,
            'status' => 'pending',
            'is_notified' => 0,
        ]);

        return redirect()->route('admin.reminders.index')
            ->with('success', 'Reminder created successfully.');
    }



    /**
     * Show edit form
     */
    public function edit(Reminder $reminder)
    {
        $this->authorizeReminder($reminder);

        $users = [];
        if (auth()->user()->type === 'Super Admin') {
            $users = User::select('id', 'name')->orderBy('name')->get();
        }

        return view('reminders.edit', compact('reminder', 'users'));
    }


    /**
     * Update reminder
     */
    public function update(Request $request, Reminder $reminder)
    {
        $this->authorizeReminder($reminder);

        $isSuper = auth()->user()->type === 'Super Admin';

        // super admin can change audience, normal cannot
        $allowedAudiences = $isSuper ? ['global', 'me', 'user'] : ['me'];

        $request->validate([
            'audience' => ['required', \Illuminate\Validation\Rule::in($allowedAudiences)],
            'user_id'  => ['nullable', 'required_if:audience,user', 'exists:users,id'],

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

        // ✅ Audience update (ONLY for super admin, otherwise always "me")
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

        $reminder->update([
            'is_global' => $isGlobal ? 1 : 0,
            'user_id'   => $newUserId,

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
        $uid = auth()->id();
        $isSuper = auth()->user()->type === 'Super Admin';

        if ($reminder->is_global) {
            if ($reminder->created_by !== $uid) abort(403);
            return;
        }

        // ✅ assigned user can access
        if ($reminder->user_id === $uid) return;

        // ✅ super admin can access reminders they created for others
        if ($isSuper && $reminder->created_by === $uid) return;

        abort(403, 'Unauthorized access');
    }


    /**
 * Update reminder status (dropdown)
 */
public function updateStatus(Request $request, Reminder $reminder)
{
    $this->authorizeReminder($reminder);

    $request->validate([
        'status' => 'required|in:pending,completed',
    ]);

    $reminder->update([
        'status' => $request->status,
        'is_notified' => $request->status === 'completed' ? 1 : 0,
    ]);

    return back()->with('success', 'Status updated successfully.');
}
}
