<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Reminder;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrap();

        View::composer('*', function ($view) {

            $now = Carbon::now();
            $todayEnd = Carbon::today()->endOfDay();

            $topReminders = Reminder::where('status', 'pending')
                ->where('due_date', '<=', $todayEnd)
                ->orderBy('due_date')
                ->get();

            $topReminderCount = $topReminders->count();

            /** -------------------------
             * 🟢 Online Users (NEW)
             * ------------------------- */
            $sessions = DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
                ->get()
                ->keyBy('user_id');

            $onlineUsers = User::whereIn('id', $sessions->keys())
                ->orderBy('name')
                ->get();

            $onlineUserCount = $sessions->count();

            $view->with([
                'topReminders'      => $topReminders,
                'topReminderCount'  => $topReminderCount,
                'topOnlineUsers'    => $onlineUsers,
                'topOnlineUserCount' => $onlineUserCount,
            ]);
        });
    }
}
