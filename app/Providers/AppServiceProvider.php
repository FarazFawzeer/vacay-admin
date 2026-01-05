<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Reminder;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;


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
            ->limit(5)
            ->get();

        $topReminderCount = $topReminders->count();

        $view->with([
            'topReminders' => $topReminders,
            'topReminderCount' => $topReminderCount
        ]);
    });
         
    }
}
