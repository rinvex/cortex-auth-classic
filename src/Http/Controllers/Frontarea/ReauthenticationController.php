<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Frontarea;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Cortex\Auth\Traits\TwoFactorAuthenticatesUsers;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;

class ReauthenticationController extends AuthenticatedController
{
    use TwoFactorAuthenticatesUsers;

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function processPassword(Request $request)
    {
        $redirect_url = session('cortex.auth.reauthentication.intended');
        $session_name = session('cortex.auth.reauthentication.session_name');

        if (Hash::check($request->input('password'), request()->user($this->getGuard())->password)) {
            $this->setSession($session_name);

            return intend([
                'intended' => url($redirect_url),
            ]);
        }

        return intend([
            'intended' => url($redirect_url),
            'withErrors' => [
                'password' => trans('cortex/auth::messages.auth.failed'),
            ],
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function processTwofactor(Request $request)
    {
        $redirect_url = session('cortex.auth.reauthentication.intended');
        $session_name = session('cortex.auth.reauthentication.session_name');

        $user = $request->user($this->getGuard());
        $token = (int) $request->input('token');

        if ($this->attemptTwoFactor($user, $token)) {
            $this->setSession($session_name);

            return intend([
                'intended' => url($redirect_url),
            ]);
        }

        return intend([
            'intended' => url($redirect_url),
            'withErrors' => ['token' => trans('cortex/auth::messages.verification.twofactor.invalid_token')],
        ]);
    }

    /**
     * @param $session_name
     */
    protected function setSession($session_name)
    {
        session()->put($session_name, time());
        session()->forget('cortex.auth.reauthentication.intended');
        session()->forget('cortex.auth.reauthentication.session_name');
    }
}
