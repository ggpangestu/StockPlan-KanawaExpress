<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    private const MAX_ATTEMPTS = 5;

    private const ATTEMPT_WINDOW_SECONDS = 30;

    private const LOCKOUT_SECONDS = 30;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => [
                'nullable',
                'required_without:email',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'required_without:username',
                'string',
                'email',
                'max:255',
            ],

            'password' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->string('username')->value()
            ?: $this->string('email')->value();

        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        if (! Auth::attempt([
            $field => $login,
            'password' => $this->password,
        ], $this->boolean('remember'))) {

            $throttleKey = $this->throttleKey();

            /*
             * Count failed login attempts.
             *
             * This key is separate from the actual lockout key.
             */
            RateLimiter::hit(
                $throttleKey,
                self::ATTEMPT_WINDOW_SECONDS
            );

            /*
             * Fifth failed attempt:
             * create a dedicated 30-second lockout.
             */
            if (RateLimiter::tooManyAttempts(
                $throttleKey,
                self::MAX_ATTEMPTS
            )) {
                RateLimiter::clear($throttleKey);

                RateLimiter::clear($this->lockoutKey());

                RateLimiter::hit(
                    $this->lockoutKey(),
                    self::LOCKOUT_SECONDS
                );

                $this->session()->put(
                    'login.lockout_identifier',
                    $login
                );
            }

            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }

        /*
         * Successful login:
         * clear both attempt and lockout state.
         */
        RateLimiter::clear($this->throttleKey());
        RateLimiter::clear($this->lockoutKey());

        $this->session()->forget('login.lockout_identifier');
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $lockoutKey = $this->lockoutKey();

        /*
         * No active lockout.
         */
        if (! RateLimiter::tooManyAttempts(
            $lockoutKey,
            1
        )) {
            return;
        }

        $login = $this->string('username')->value()
            ?: $this->string('email')->value();

        /*
         * Keep the identifier in session so the GET /login
         * request can restore the remaining countdown.
         */
        $this->session()->put(
            'login.lockout_identifier',
            $login
        );

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn(
            $lockoutKey
        );

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for failed attempts.
     */
    public function throttleKey(): string
    {
        $login = $this->string('username')->value()
            ?: $this->string('email')->value();

        return Str::transliterate(
            Str::lower($login) . '|' . $this->ip()
        );
    }

    /**
     * Get the dedicated lockout key.
     */
    private function lockoutKey(): string
    {
        return 'login-lockout|' . $this->throttleKey();
    }
}