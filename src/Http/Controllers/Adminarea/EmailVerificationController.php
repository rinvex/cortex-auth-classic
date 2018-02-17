<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Cortex\Foundation\Http\Controllers\AbstractController;
use Rinvex\Auth\Contracts\EmailVerificationBrokerContract;
use Cortex\Auth\Http\Requests\Adminarea\EmailVerificationRequest;
use Cortex\Auth\Http\Requests\Adminarea\EmailVerificationSendRequest;
use Cortex\Auth\Http\Requests\Adminarea\EmailVerificationProcessRequest;

class EmailVerificationController extends AbstractController
{
    /**
     * Show the email verification request form.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\EmailVerificationRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function request(EmailVerificationRequest $request)
    {
        return view('cortex/auth::adminarea.pages.verification-email-request');
    }

    /**
     * Process the email verification request form.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\EmailVerificationSendRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function send(EmailVerificationSendRequest $request)
    {
        $result = app('rinvex.auth.emailverification')
            ->broker($this->getBroker())
            ->sendVerificationLink($request->only(['email']));

        switch ($result) {
            case EmailVerificationBrokerContract::LINK_SENT:
                return intend([
                    'url' => route('adminarea.home'),
                    'with' => ['success' => trans('cortex/auth::'.$result)],
                ]);

            case EmailVerificationBrokerContract::INVALID_USER:
            default:
                return intend([
                    'back' => true,
                    'withInput' => $request->only(['email']),
                    'withErrors' => ['email' => trans('cortex/auth::'.$result)],
                ]);
        }
    }

    /**
     * Process the email verification.
     *
     * @param \Cortex\Auth\Http\Requests\Adminarea\EmailVerificationProcessRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function verify(EmailVerificationProcessRequest $request)
    {
        $result = app('rinvex.auth.emailverification')
            ->broker($this->getBroker())
            ->verify($request->only(['email', 'expiration', 'token']), function ($user) {
                $user->fill([
                    'email_verified' => true,
                    'email_verified_at' => now(),
                ])->forceSave();
            });

        switch ($result) {
            case EmailVerificationBrokerContract::EMAIL_VERIFIED:
                return intend([
                    'url' => route('adminarea.account.settings'),
                    'with' => ['success' => trans('cortex/auth::'.$result)],
                ]);

            case EmailVerificationBrokerContract::INVALID_USER:
            case EmailVerificationBrokerContract::INVALID_TOKEN:
            case EmailVerificationBrokerContract::EXPIRED_TOKEN:
            default:
                return intend([
                    'url' => route('adminarea.verification.email.request'),
                    'withInput' => $request->only(['email']),
                    'withErrors' => ['email' => trans('cortex/auth::'.$result)],
                ]);
        }
    }
}
