<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $now = Carbon::now();

        // How many days ahead to show reminders in topbar
        $daysAhead = 3;

        $topReminders = Reminder::where('user_id', $userId)
            ->where('status', 'pending')
            ->whereBetween('due_date', [$now, $now->copy()->addDays($daysAhead)])
            ->orderBy('due_date')
            ->get();

        $topReminderCount = $topReminders->count();

        return view('index', [
            'topReminders' => $topReminders,
            'topReminderCount' => $topReminderCount,
        ]);
    }
}
