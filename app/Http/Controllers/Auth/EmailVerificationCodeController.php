<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class EmailVerificationCodeController extends Controller
{
    /** صفحة "أدخل الكود" بعد التسجيل */
    public function show(): View|RedirectResponse
    {
        $userId = session('verify_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        return view('auth.verify-code');
    }

    /** التحقق من الكود اللي دخلو المستخدم */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);

        $userId = session('verify_user_id');
        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('login');
        }

        if (
            $user->email_verification_code !== $request->code
            || now()->greaterThan($user->email_verification_code_expires_at)
        ) {
            return back()->with('error', 'الكود غير صحيح أو انتهت صلاحيته');
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_code' => null,
            'email_verification_code_expires_at' => null,
        ]);

        session()->forget('verify_user_id');
        Auth::login($user, remember: true);

        return redirect()->route('dashboard')->with('success', 'تم تأكيد حسابك بنجاح');
    }

    /** إعادة بعث الكود */
    public function resend(): RedirectResponse
    {
        $userId = session('verify_user_id');
        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('login');
        }

        $this->sendCode($user);

        return back()->with('success', 'تم إعادة إرسال الكود');
    }

    /** دالة مشتركة: توليد كود جديد وبعثو بالإيميل */
    public static function sendCode(User $user): void
    {
        $code = (string) random_int(100000, 999999);

        $user->update([
            'email_verification_code' => $code,
            'email_verification_code_expires_at' => now()->addMinutes(10),
        ]);

        Mail::send('emails.verification-code', ['code' => $code, 'name' => $user->name], function ($message) use ($user) {
            $message->to($user->email)->subject('Votre code de vérification — Boutique Paris');
        });
    }
}