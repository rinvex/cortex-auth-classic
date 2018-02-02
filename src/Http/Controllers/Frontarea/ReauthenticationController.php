<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Frontarea;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Cortex\Fort\Traits\TwoFactorAuthenticatesUsers;
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
        $redirect_url = session('cortex.fort.reauthentication.intended');
        $session_name = session('cortex.fort.reauthentication.session_name');

        if (Hash::check($request->input('password'), request()->user()->password)) {
            $this->setSession($session_name);

            return intend([
                'intended' => url($redirect_url),
            ]);
        }

        return intend([
            'intended' => url($redirect_url),
            'withErrors' => [
                'password' => trans('cortex/fort::messages.auth.failed'),
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
        $redirect_url = session('cortex.fort.reauthentication.intended');
        $session_name = session('cortex.fort.reauthentication.session_name');

        $guard = $this->getGuard();
        $user = $request->user($guard);
        $token = (int) $request->input('token');

        if ($this->attemptTwoFactor($user, $token)) {
            $this->setSession($session_name);

            return intend([
                'intended' => url($redirect_url),
            ]);
        }

        return intend([
            'intended' => url($redirect_url),
            'withErrors' => ['token' => trans('cortex/fort::messages.verification.twofactor.invalid_token')],
        ]);
    }

    /**
     * @param $session_name
     */
    protected function setSession($session_name)
    {
        session()->put($session_name, time());
        session()->forget('cortex.fort.reauthentication.intended');
        session()->forget('cortex.fort.reauthentication.session_name');
    }
}
