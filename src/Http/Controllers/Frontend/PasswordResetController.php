<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Cortex Fort Module.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Cortex Fort Module
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Frontend;

use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\PasswordBroker;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Cortex\Fort\Http\Requests\Frontend\PasswordResetRequest;
use Cortex\Fort\Http\Requests\Frontend\PasswordResetSendRequest;

class PasswordResetController extends AbstractController
{
    /**
     * Create a new password reset controller instance.
     */
    public function __construct()
    {
        $this->middleware($this->getGuestMiddleware(), ['except' => $this->middlewareWhitelist]);
    }

    /**
     * Show the password reset request form.
     *
     * @return \Illuminate\Http\Response
     */
    public function request()
    {
        return view('cortex/fort::frontend.passwordreset.request');
    }

    /**
     * Process the password reset request form.
     *
     * @param \Cortex\Fort\Http\Requests\Frontend\PasswordResetSendRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function send(PasswordResetSendRequest $request)
    {
        $result = app('auth.password')->broker($this->getBroker())->sendResetLink($request->only(['email']));

        switch ($result) {
            case PasswordBroker::RESET_LINK_SENT:
                return intend([
                    'url' => '/',
                    'with' => ['success' => trans($result)],
                ]);

            case PasswordBroker::INVALID_USER:
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
     * @param \Cortex\Fort\Http\Requests\Frontend\PasswordResetRequest $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function reset(PasswordResetRequest $request)
    {
        $token = $request->get('token');
        $email = $request->get('email');

        if (is_null($user = app('auth.password')->broker($this->getBroker())->getUser($request->only(['email'])))) {
            return intend([
                'route' => 'frontend.passwordreset.request',
                'withInput' => $request->only(['email']),
                'withErrors' => ['email' => trans(PasswordBroker::INVALID_USER)],
            ]);
        }

        if (! app('auth.password')->broker($this->getBroker())->tokenExists($user, $token)) {
            return intend([
                'route' => 'frontend.passwordreset.request',
                'withInput' => $request->only(['email']),
                'withErrors' => ['email' => trans(PasswordBroker::INVALID_TOKEN)],
            ]);
        }

        return view('cortex/fort::frontend.passwordreset.reset')->with(compact('token', 'email'));
    }

    /**
     * Process the password reset form.
     *
     * @param \Cortex\Fort\Http\Requests\Frontend\PasswordResetRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function process(PasswordResetRequest $request)
    {
        $result = app('auth.password')
            ->broker($this->getBroker())
            ->reset($request->only(['email', 'token', 'password', 'password_confirmation']), function ($user, $password) {
                $user->fill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->forceSave();
            });

        switch ($result) {
            case PasswordBroker::PASSWORD_RESET:
                return intend([
                    'route' => 'frontend.auth.login',
                    'with' => ['success' => trans($result)],
                ]);

            case PasswordBroker::INVALID_USER:
            case PasswordBroker::INVALID_TOKEN:
            case PasswordBroker::INVALID_PASSWORD:
            default:
                return intend([
                    'back' => true,
                    'withInput' => $request->only(['email']),
                    'withErrors' => ['email' => trans($result)],
                ]);
        }
    }
}
