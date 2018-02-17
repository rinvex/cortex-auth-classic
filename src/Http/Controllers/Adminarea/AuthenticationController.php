<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Cortex\Auth\Http\Requests\Adminarea\AuthenticationRequest;

class AuthenticationController extends AbstractController
{
    use ThrottlesLogins;

    /**
     * {@inheritdoc}
     */
    protected $middlewareWhitelist = [
        'logout',
    ];

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware($this->getGuestMiddleware())->except($this->middlewareWhitelist]);
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

        return view('cortex/auth::adminarea.pages.authentication');
    }

    /**
     * Process to the login form.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\AuthenticationRequest $request
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $this->processLogout($request);

        return intend([
            'url' => route('adminarea.home'),
            'with' => ['warning' => trans('cortex/auth::messages.auth.logout')],
        ]);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
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

            $request->session()->put('cortex.auth.twofactor', ['user_id' => $user->getKey(), 'remember' => $request->filled('remember'), 'totp' => $totpStatus, 'phone' => $phoneStatus]);

            $route = $totpStatus
                ? route('adminarea.verification.phone.verify')
                : route('adminarea.verification.phone.request');

            return intend([
                'url' => $route,
                'with' => ['warning' => trans('cortex/auth::messages.verification.twofactor.totp.required')],
            ]);
        }

        return intend([
            'intended' => route('adminarea.home'),
            'with' => ['success' => trans('cortex/auth::messages.auth.login')],
        ]);
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws ValidationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('cortex/auth::messages.auth.failed')],
        ]);
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param \Illuminate\Http\Request $request
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
            $this->username() => [trans('cortex/auth::messages.auth.lockout', ['seconds' => $seconds])],
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
