<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Afficher la page Profile
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $orders = $user->orders()
            ->latest()
            ->take(10)
            ->get();

        return view('profile.index', compact('user', 'orders'));
    }

    /**
     * Mettre à jour le profil
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();

    // avatar
    if ($request->hasFile('avatar')) {
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
    }

    // update normal fields
    $user->fill($request->validated());

    // password (IMPORTANT)
    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    // email reset check
    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    return redirect()->route('profile')
        ->with('success', 'Profil mis à jour.');
}

/**
 * Supprimer le compte
 */
public function destroy(Request $request): RedirectResponse
{
    $request->validateWithBag('userDeletion', [
        'password' => ['required', 'current_password'],
    ]);

    $user = $request->user();

    Auth::logout();

    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return Redirect::to('/');
}
}