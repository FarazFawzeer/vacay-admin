<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OnlineUserController extends Controller
{
    public function index()
{
    // Get active sessions (last 5 minutes)
    $sessions = DB::table('sessions')
        ->whereNotNull('user_id')
        ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
        ->get()
        ->keyBy('user_id'); // key by user_id for easy access

    $onlineUsers = User::whereIn('id', $sessions->keys())
        ->orderBy('name')
        ->get();

    return view('admin.users.online', compact('onlineUsers', 'sessions'));
}

}
