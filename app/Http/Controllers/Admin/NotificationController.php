<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(20);

        auth()->user()->unreadNotifications->markAsRead();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markRead(string $id): RedirectResponse
    {
        auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
        return back();
    }

    public function readAll(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Toutes les notifications marquées comme lues ✅');
    }
}