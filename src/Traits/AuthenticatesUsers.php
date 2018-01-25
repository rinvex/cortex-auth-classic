<?php

namespace Cortex\Fort\Traits;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;

trait AuthenticatesUsers
{
    use ThrottlesLogins;

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $user = auth()->guard($this->getGuard())->user();

        $twofactor = $user->getTwoFactor();
        $totpStatus = $twofactor['totp']['enabled'] ?? false;
        $phoneStatus = $twofactor['phone']['enabled'] ?? false;

        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        // Enforce TwoFactor authentication
        if ($totpStatus || $phoneStatus) {
            $this->processLogout($request);

            $request->session()->put('rinvex.fort.twofactor', ['user_id' => $user->getKey(), 'remember' => $request->filled('remember'), 'totp' => $totpStatus, 'phone' => $phoneStatus]);

            $route = $totpStatus
                ? route('frontarea.verification.phone.verify')
                : route('frontarea.verification.phone.request');

            return intend([
                'url' => $route,
                'with' => ['warning' => trans('cortex/fort::messages.verification.twofactor.totp.required')],
            ]);
        }

        return intend([
            'intended' => route('frontarea.home'),
            'with' => ['success' => trans('cortex/fort::messages.auth.login')],
        ]);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws ValidationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('cortex/fort::messages.auth.failed')],
        ]);
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            $this->username() => [trans('cortex/fort::messages.auth.lockout', ['seconds' => $seconds])],
        ])->status(423);
    }

    /**
     * Process logout.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function processLogout(Request $request): void
    {
        auth()->guard($this->getGuard())->logout();

        $request->session()->invalidate();
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    protected function username()
    {
        return 'loginfield';
    }
}
