<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    private function googleConfig(): array
    {
        return [
            'client_id' => Setting::get('google_client_id'),
            'client_secret' => Setting::get('google_client_secret'),
            'redirect' => Setting::get('google_redirect_uri'),
        ];
    }

  public function redirect(): RedirectResponse
  {
      $config = $this->googleConfig();
  
      config([
          'services.google' => $config,
      ]);
  
      return Socialite::driver('google')->redirect();
  }
    public function callback(): RedirectResponse
    {
        config([
            'services.google' => $this->googleConfig(),
        ]);

        $googleUser = Socialite::driver('google')->user();

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Str::random(24),
                'email_verified_at' => now(),
            ]);
        } elseif (! $user->google_id) {
            $user->update([
                'google_id' => $googleUser->getId(),
            ]);
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('home'));
    }
}