<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Frontarea;

use Cortex\Fort\Traits\TwoFactorAuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;

class ReauthenticationController extends AuthenticatedController
{

    use TwoFactorAuthenticatesUsers;

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function processPassword( Request $request ) {

        $session_name = session(config('cortex.fort.reauthentication.prefix').'.session_name');
        $redirect_url = session(config('cortex.fort.reauthentication.prefix').'.intended');

        if( Hash::check($request->input('password'), request()->user()->password) ) {
            $this->setSession($session_name);

            return intend([
                'intended' => url($redirect_url)
            ]);
        } else {
            return intend([
                'intended' => url($redirect_url),
                'withErrors' => ['password' => trans('cortex/fort::messages.auth.failed')
                ]
            ]);
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function processTwofactor( Request $request ) {

        $session_name = session(config('cortex.fort.reauthentication.prefix').'.session_name');
        $redirect_url = session(config('cortex.fort.reauthentication.prefix').'.intended');

        $guard = $this->getGuard();
        $token = $request->input('token');
        $user = $request->user($guard);

        if( $this->attemptTwoFactor($user, $token) ) {
            $this->setSession($session_name);

            return intend([
                'intended' => url($redirect_url)
            ]);
        } else {
            return intend([
                'intended' => url($redirect_url),
                'withErrors' => ['token' => trans('cortex/fort::messages.verification.twofactor.invalid_token')],
            ]);
        }
    }

    /**
     * @param $session_name
     */
    protected  function setSession($session_name) {
        session()->put($session_name, time());
        session()->forget(config('cortex.fort.reauthentication.prefix').'.session_name');
        session()->forget(config('cortex.fort.reauthentication.prefix').'.intended');
        session()->forget('rinvex.fort.twofactor.totp');
    }

}
