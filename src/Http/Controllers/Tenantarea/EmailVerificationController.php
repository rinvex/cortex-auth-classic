<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Tenantarea;

use Cortex\Foundation\Http\Controllers\AbstractController;
use Rinvex\Fort\Contracts\EmailVerificationBrokerContract;
use Cortex\Fort\Http\Requests\Tenantarea\EmailVerificationRequest;
use Cortex\Fort\Http\Requests\Tenantarea\EmailVerificationSendRequest;
use Cortex\Fort\Http\Requests\Tenantarea\EmailVerificationProcessRequest;

class EmailVerificationController extends AbstractController
{
    /**
     * Show the email verification request form.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\EmailVerificationRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function request(EmailVerificationRequest $request)
    {
        return view('cortex/fort::tenantarea.pages.verification-email-request');
    }

    /**
     * Process the email verification request form.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\EmailVerificationSendRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function send(EmailVerificationSendRequest $request)
    {
        $result = app('rinvex.fort.emailverification')
            ->broker($this->getBroker())
            ->sendVerificationLink($request->only(['email']));

        switch ($result) {
            case EmailVerificationBrokerContract::LINK_SENT:
                return intend([
                    'url' => route('tenantarea.home'),
                    'with' => ['success' => trans('cortex/fort::'.$result)],
                ]);

            case EmailVerificationBrokerContract::INVALID_USER:
            default:
                return intend([
                    'back' => true,
                    'withInput' => $request->only(['email']),
                    'withErrors' => ['email' => trans('cortex/fort::'.$result)],
                ]);
        }
    }

    /**
     * Process the email verification.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\EmailVerificationProcessRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function verify(EmailVerificationProcessRequest $request)
    {
        $result = app('rinvex.fort.emailverification')
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
                    'url' => route('tenantarea.account.settings'),
                    'with' => ['success' => trans('cortex/fort::'.$result)],
                ]);

            case EmailVerificationBrokerContract::INVALID_USER:
            case EmailVerificationBrokerContract::INVALID_TOKEN:
            case EmailVerificationBrokerContract::EXPIRED_TOKEN:
            default:
                return intend([
                    'url' => route('tenantarea.verification.email.request'),
                    'withInput' => $request->only(['email']),
                    'withErrors' => ['email' => trans('cortex/fort::'.$result)],
                ]);
        }
    }
}
