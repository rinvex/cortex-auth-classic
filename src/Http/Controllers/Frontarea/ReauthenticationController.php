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

        $session_name = session('cortex.fort.reauthentication.session_name');
        $redirect_url = session('cortex.fort.reauthentication.intended');

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

        $session_name = session('cortex.fort.reauthentication.session_name');
        $redirect_url = session('cortex.fort.reauthentication.intended');

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
        session()->forget('cortex.fort.reauthentication.session_name');
        session()->forget('cortex.fort.reauthentication.intended');
        session()->forget('rinvex.fort.twofactor.totp');
    }

}
