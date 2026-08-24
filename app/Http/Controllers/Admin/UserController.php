<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::withCount('orders')
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function toggleAdmin(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        $user->update(['is_admin' => ! $user->is_admin]);

        return back()->with('success', $user->is_admin ? 'Admin activé ✅' : 'Admin désactivé');
    }
}