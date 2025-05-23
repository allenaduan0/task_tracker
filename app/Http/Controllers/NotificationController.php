<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'member') {
            $notifications = $user->notifications;
        } elseif (in_array($user->role, ['admin', 'manager'])) {
            $notifications = $user->notifications;
        } else {
            $notifications = collect();
        }

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back();
    }
}
