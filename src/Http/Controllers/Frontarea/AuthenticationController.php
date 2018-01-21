<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Frontarea;

use Illuminate\Http\Request;
use Rinvex\Fort\Guards\SessionGuard;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Cortex\Fort\Http\Requests\Frontarea\AuthenticationRequest;

class AuthenticationController extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    protected $middlewareWhitelist = ['logout'];

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware($this->getGuestMiddleware(), ['except' => $this->middlewareWhitelist]);
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function form()
    {
        // Remember previous URL for later redirect back
        session()->put('url.intended', url()->previous());

        return view('cortex/fort::frontarea.pages.authentication');
    }

    /**
     * Process to the login form.
     *
     * @param \Cortex\Fort\Http\Requests\Frontarea\AuthenticationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function login(AuthenticationRequest $request)
    {
        // Prepare variables
        $remember = $request->has('remember');
        $loginField = get_login_field($request->get('loginfield'));
        $credentials = [
            'is_active' => true,
            $loginField => $request->input('loginfield'),
            'password' => $request->input('password'),
        ];

        $result = auth()->guard($this->getGuard())->attempt($credentials, $remember);

        return $this->getLoginResponse($request, $result);
    }

    /**
     * Logout currently logged in user.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth()->guard($this->getGuard())->logout();

        return intend([
            'url' => route('frontarea.home'),
            'with' => ['warning' => trans('cortex/fort::messages.auth.logout')],
        ]);
    }

    /**
     * Get login response upon the given request & result.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $result
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function getLoginResponse(Request $request, $result)
    {
        switch ($result) {
            // Too many failed logins, user locked out
            case SessionGuard::AUTH_LOCKED_OUT:
                $seconds = auth()->guard($this->getGuard())->secondsRemainingOnLockout($request);

                return intend([
                    'url' => route('frontarea.home'),
                    'withInput' => $request->only(['loginfield', 'remember']),
                    'withErrors' => ['loginfield' => trans('cortex/fort::'.$result, ['seconds' => $seconds])],
                ]);

            // Valid credentials, but user is unverified; Can NOT login!
            case SessionGuard::AUTH_UNVERIFIED:
                return intend([
                    'url' => route('frontarea.verification.email.request'),
                    'withErrors' => ['email' => trans('cortex/fort::'.$result)],
                ]);

            // Wrong credentials, failed login
            case SessionGuard::AUTH_FAILED:
                return intend([
                    'back' => true,
                    'withInput' => $request->only(['loginfield', 'remember']),
                    'withErrors' => ['loginfield' => trans('cortex/fort::'.$result)],
                ]);

            // TwoFactor authentication required
            case SessionGuard::AUTH_TWOFACTOR_REQUIRED:
                $route = session('_twofactor.totp')
                    ? route('frontarea.verification.phone.verify')
                    : route('frontarea.verification.phone.request');

                return intend([
                    'url' => $route,
                    'with' => ['warning' => trans('cortex/fort::'.$result)],
                ]);

            // Login successful and everything is fine!
            case SessionGuard::AUTH_LOGIN:
            default:
                return intend([
                    'intended' => route('frontarea.home'),
                    'with' => ['success' => trans('cortex/fort::'.$result)],
                ]);
        }
    }
}
