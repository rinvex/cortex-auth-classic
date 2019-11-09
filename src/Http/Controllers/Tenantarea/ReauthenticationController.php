<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Tenantarea;

use Illuminate\Http\Request;
use Cortex\Auth\Traits\TwoFactorAuthenticatesUsers;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;
use Cortex\Auth\Http\Requests\Tenantarea\ReauthenticatePasswordFormRequest;

class ReauthenticationController extends AuthenticatedController
{
    use TwoFactorAuthenticatesUsers;

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function confirmPassword(Request $request)
    {
        return view('cortex/auth::frontarea.pages.reauthentication-password');
    }

    /**
     * Process password.
     *
     * @param \Cortex\Auth\Http\Requests\Tenantarea\ReauthenticatePasswordFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function processPassword(ReauthenticatePasswordFormRequest $request)
    {
        $this->resetSessionConfirmationTimeout($request, 'password');

        return intend([
            'intended' => url(route('frontarea.home')),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function processTwofactor(Request $request)
    {
        $user = $request->user($this->getGuard());
        $token = (int) $request->input('token');

        if ($this->attemptTwoFactor($user, $token)) {
            $this->resetSessionConfirmationTimeout($request, 'twofactor');

            return intend([
                'intended' => url(route('tenantarea.home')),
            ]);
        }

        return intend([
            'intended' => url(route('tenantarea.home')),
            'withErrors' => ['token' => trans('cortex/auth::messages.verification.twofactor.invalid_token')],
        ]);
    }

    /**
     * Reset the session confirmation timeout.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $type
     *
     * @return void
     */
    protected function resetSessionConfirmationTimeout(Request $request, string $type = 'password'): void
    {
        $request->session()->put("auth.{$type}_confirmed_at", time());
    }
}
