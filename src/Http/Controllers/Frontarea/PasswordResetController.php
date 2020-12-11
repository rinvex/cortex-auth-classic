<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Frontarea;

use Illuminate\Support\Str;
use Rinvex\Auth\Contracts\PasswordResetBrokerContract;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Cortex\Auth\Http\Requests\Frontarea\PasswordResetRequest;
use Cortex\Auth\Http\Requests\Frontarea\PasswordResetSendRequest;
use Cortex\Auth\Http\Requests\Frontarea\PasswordResetProcessRequest;
use Cortex\Auth\Http\Requests\Frontarea\PasswordResetPostProcessRequest;

class PasswordResetController extends AbstractController
{
    /**
     * Show the password reset request form.
     *
     * @param \Cortex\Auth\Http\Requests\Frontarea\PasswordResetRequest
     *
     * @return \Illuminate\View\View
     */
    public function request(PasswordResetRequest $request)
    {
        return view('cortex/auth::frontarea.pages.passwordreset-request');
    }

    /**
     * Process the password reset request form.
     *
     * @param \Cortex\Auth\Http\Requests\Frontarea\PasswordResetSendRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function send(PasswordResetSendRequest $request)
    {
        $result = app('auth.password')
            ->broker(app('request.passwordResetBroker'))
            ->sendResetLink($request->only(['email']));

        switch ($result) {
            case PasswordResetBrokerContract::RESET_LINK_SENT:
                return intend([
                    'url' => route('frontarea.home'),
                    'with' => ['success' => trans($result)],
                ]);

            case PasswordResetBrokerContract::INVALID_USER:
            default:
                return intend([
                    'back' => true,
                    'withInput' => $request->only(['email']),
                    'withErrors' => ['email' => trans($result)],
                ]);
        }
    }

    /**
     * Show the password reset form.
     *
     * @param \Cortex\Auth\Http\Requests\Frontarea\PasswordResetProcessRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function reset(PasswordResetProcessRequest $request)
    {
        $credentials = $request->only('email', 'expiration', 'token');

        return view('cortex/auth::frontarea.pages.passwordreset')->with($credentials);
    }

    /**
     * Process the password reset form.
     *
     * @param \Cortex\Auth\Http\Requests\Frontarea\PasswordResetPostProcessRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function process(PasswordResetPostProcessRequest $request)
    {
        $result = app('auth.password')
            ->broker(app('request.passwordResetBroker'))
            ->reset($request->only(['email', 'expiration', 'token', 'password', 'password_confirmation']), function ($user, $password) {
                $user->fill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->forceSave();
            });

        switch ($result) {
            case PasswordResetBrokerContract::PASSWORD_RESET:
                return intend([
                    'url' => route('frontarea.cortex.auth.account.login'),
                    'with' => ['success' => trans($result)],
                ]);

            case PasswordResetBrokerContract::INVALID_USER:
            case PasswordResetBrokerContract::INVALID_TOKEN:
            case PasswordResetBrokerContract::EXPIRED_TOKEN:
            default:
                return intend([
                    'back' => true,
                    'withInput' => $request->only(['email']),
                    'withErrors' => ['email' => trans($result)],
                ]);
        }
    }
}
