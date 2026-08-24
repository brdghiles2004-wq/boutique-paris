<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /** عرض صفحة Register */
    public function create(): View
    {
        return view('auth.register');
    }

    /** معالجة فورم التسجيل */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // نبعثو كود التحقق مباشرة (دالة مشتركة من EmailVerificationCodeController)
        EmailVerificationCodeController::sendCode($user);
        // إشعار Admin بتسجيل جديد
$admins = \App\Models\User::where('is_admin', true)->get();
foreach ($admins as $admin) {
    $admin->notify(new \App\Notifications\NewUserNotification($user));
}

        // نخزنو الـ user_id فـ session مؤقتاً، لين ما يأكد الكود
        session(['verify_user_id' => $user->id]);

        return redirect()->route('verification.code');
    }
}