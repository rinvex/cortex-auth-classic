<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Frontend;

use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\PasswordBroker;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Cortex\Fort\Http\Requests\Frontend\PasswordResetRequest;
use Cortex\Fort\Http\Requests\Frontend\PasswordResetProcessRequest;
use Cortex\Fort\Http\Requests\Frontend\PasswordResetPostProcessRequest;

class PasswordResetController extends AbstractController
{
    /**
     * Show the password reset request form.
     *
     * @param Cortex\Fort\Http\Requests\Frontend\PasswordResetRequest
     *
     * @return \Illuminate\Http\Response
     */
    public function request(PasswordResetRequest $request)
    {
        return view('cortex/fort::frontend.passwordreset.request');
    }

    /**
     * Process the password reset request form.
     *
     * @param \Cortex\Fort\Http\Requests\Frontend\PasswordResetProcessRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function send(PasswordResetProcessRequest $request)
    {
        $result = app('auth.password')
            ->broker($this->getBroker())
            ->sendResetLink($request->only(['email']));

        switch ($result) {
            case PasswordBroker::RESET_LINK_SENT:
                return intend([
                    'url' => '/',
                    'with' => ['success' => trans('cortex/fort::'.$result)],
                ]);

            case PasswordBroker::INVALID_USER:
            default:
                return intend([
                    'back' => true,
                    'withInput' => $request->only(['email']),
                    'withErrors' => ['email' => trans('cortex/fort::'.$result)],
                ]);
        }
    }

    /**
     * Show the password reset form.
     *
     * @param \Cortex\Fort\Http\Requests\Frontend\PasswordResetProcessRequest $request
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function reset(PasswordResetProcessRequest $request)
    {
        $token = $request->get('token');
        $email = $request->get('email');

        $broker = app('auth.password')->broker($this->getBroker());
        $user = $broker->getUser($request->only(['email']));
        $tokenExists = $broker->tokenExists($user, $token);

        if (! $user || ! $tokenExists) {
            return intend([
                'url' => route('frontend.passwordreset.request'),
                'withInput' => $request->only(['email']),
                'withErrors' => ['email' => ! $user ? trans(PasswordBroker::INVALID_USER) : trans(PasswordBroker::INVALID_TOKEN)],
            ]);
        }

        return view('cortex/fort::frontend.passwordreset.reset')->with(compact('token', 'email'));
    }

    /**
     * Process the password reset form.
     *
     * @param \Cortex\Fort\Http\Requests\Frontend\PasswordResetPostProcessRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function process(PasswordResetPostProcessRequest $request)
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
                    'url' => route('frontend.auth.login'),
                    'with' => ['success' => trans('cortex/fort::'.$result)],
                ]);

            case PasswordBroker::INVALID_USER:
            case PasswordBroker::INVALID_TOKEN:
            case PasswordBroker::INVALID_PASSWORD:
            default:
                return intend([
                    'back' => true,
                    'withInput' => $request->only(['email']),
                    'withErrors' => ['email' => trans('cortex/fort::'.$result)],
                ]);
        }
    }
}
