<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\View\View;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class TwoFactorController extends Controller
{
    /** صفحة "فعّل 2FA" — تورّي QR Code */
    public function setup(): View
    {
        $user = Auth::user();

        $secret = Google2FA::generateSecretKey();

        // نخزنو الـ secret مؤقت فـ session لين ما المستخدم يأكد الكود (ماخزنوهش فالقاعدة قبل التأكيد)
        session(['2fa_secret_pending' => $secret]);

        $qrCodeUrl = Google2FA::getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('auth.two-factor-setup', compact('qrCodeUrl', 'secret'));
    }

    /** تأكيد الكود الأول وتفعيل 2FA فعلياً */
    public function enable(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $secret = session('2fa_secret_pending');

        if (! $secret) {
            return redirect()->route('two-factor.setup')->with('error', 'انتهت صلاحية الجلسة، حاول من جديد');
        }

        $valid = Google2FA::verifyKey($secret, $request->code);

        if (! $valid) {
            return back()->with('error', 'الكود غير صحيح، حاول مرة أخرى');
        }

        $user = Auth::user();
        $user->update([
            'two_factor_secret' => Crypt::encryptString($secret),
            'two_factor_enabled' => true,
        ]);

        session()->forget('2fa_secret_pending');

        return redirect()->route('profile.edit')->with('success', 'تم تفعيل التحقق بخطوتين بنجاح');
    }

    /** تعطيل 2FA */
    public function disable(): RedirectResponse
    {
        Auth::user()->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
        ]);

        return redirect()->route('profile.edit')->with('success', 'تم تعطيل التحقق بخطوتين');
    }

    /** صفحة إدخال الكود وقت تسجيل الدخول */
    public function challenge(): View|RedirectResponse
    {
        if (! session('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);

        $userId = session('2fa_user_id');
        $user = \App\Models\User::find($userId);

        if (! $user) {
            return redirect()->route('login');
        }

        $secret = Crypt::decryptString($user->two_factor_secret);
        $valid = Google2FA::verifyKey($secret, $request->code, 8);

        if (! $valid) {
            return back()->with('error', 'الكود غير صحيح');
        }

        session()->forget('2fa_user_id');
        Auth::login($user, remember: true);

        return redirect()->intended(route('home'));
    }
}