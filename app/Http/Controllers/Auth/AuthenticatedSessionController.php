<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $lockSeconds = 0;

        /*
         * The login identifier is stored in the session
         * whenever a lockout is created.
         */
        $lockIdentifier = $request->session()->get(
            'login.lockout_identifier'
        );

        if ($lockIdentifier) {

            $throttleKey = Str::transliterate(
                Str::lower($lockIdentifier)
                . '|'
                . $request->ip()
            );

            /*
             * This is the dedicated lockout key created
             * by LoginRequest.
             */
            $lockoutKey = 'login-lockout|' . $throttleKey;

            if (RateLimiter::tooManyAttempts(
                $lockoutKey,
                1
            )) {

                /*
                 * Read the actual remaining server-side
                 * lockout time.
                 */
                $lockSeconds = RateLimiter::availableIn(
                    $lockoutKey
                );

            } else {

                /*
                 * Lockout expired.
                 */
                $request->session()->forget(
                    'login.lockout_identifier'
                );
            }
        }

        return view('auth.login', [
            'lockSeconds' => $lockSeconds,
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(
        LoginRequest $request
    ): RedirectResponse {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(
            route('dashboard', absolute: false)
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(
        Request $request
    ): RedirectResponse {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}