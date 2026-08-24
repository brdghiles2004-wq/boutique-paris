<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /** عرض صفحة Login */
    public function create(): View
    {
        return view('auth.login');
    }

    /** معالجة محاولة تسجيل الدخول */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // إذا 2FA مفعّل عند هاد المستخدم، نوقفو الدخول الكامل ونوديوه لصفحة الكود
        if ($user->two_factor_enabled) {
            Auth::logout();

            $request->session()->put('2fa_user_id', $user->id);

            return redirect()->route('two-factor.challenge');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    /** تسجيل الخروج */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}