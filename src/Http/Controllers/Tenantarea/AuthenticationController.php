<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Tenantarea;

use Illuminate\Http\Request;
use Cortex\Fort\Traits\AuthenticatesUsers;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Cortex\Fort\Http\Requests\Tenantarea\AuthenticationRequest;

class AuthenticationController extends AbstractController
{
    use AuthenticatesUsers;

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

        return view('cortex/fort::tenantarea.pages.authentication');
    }

    /**
     * Process to the login form.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\AuthenticationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function login(AuthenticationRequest $request)
    {
        // Prepare variables
        $loginField = get_login_field($request->input($this->username()));
        $credentials = [
            'is_active' => true,
            $loginField => $request->input('loginfield'),
            'password' => $request->input('password'),
        ];

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if (auth()->guard($this->getGuard())->attempt($credentials, $request->filled('remember'))) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Logout currently logged in user.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $this->processLogout($request);

        return intend([
            'url' => route('tenantarea.home'),
            'with' => ['warning' => trans('cortex/fort::messages.auth.logout')],
        ]);
    }
}
