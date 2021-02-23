<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Managerarea;

use Carbon\Carbon;
use Cortex\Auth\Models\User;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Rinvex\Auth\Contracts\EmailVerificationBrokerContract;
use Cortex\Auth\Http\Requests\Managerarea\EmailVerificationRequest;
use Cortex\Auth\Http\Requests\Managerarea\EmailVerificationSendRequest;
use Cortex\Auth\Http\Requests\Managerarea\EmailVerificationProcessRequest;

class EmailVerificationController extends AbstractController
{
    /**
     * Show the email verification request form.
     *
     * @param \Cortex\Auth\Http\Requests\Managerarea\EmailVerificationRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function request(EmailVerificationRequest $request)
    {
        return view('cortex/auth::managerarea.pages.verification-email-request');
    }

    /**
     * Process the email verification request form.
     *
     * @param \Cortex\Auth\Http\Requests\Managerarea\EmailVerificationSendRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function send(EmailVerificationSendRequest $request)
    {
        $result = app('rinvex.auth.emailverification')
            ->broker($request->emailVerificationBroker())
            ->sendVerificationLink($request->only(['email']));

        switch ($result) {
            case EmailVerificationBrokerContract::LINK_SENT:
                return intend([
                    'url' => route('managerarea.home'),
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
     * @param \Cortex\Auth\Http\Requests\Managerarea\EmailVerificationProcessRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function verify(EmailVerificationProcessRequest $request)
    {
        $result = app('rinvex.auth.emailverification')
            ->broker($request->emailVerificationBroker())
            ->verify($request->only(['email', 'expiration', 'token']), function (User $user) {
                $user->fill([
                    'email_verified_at' => Carbon::now(),
                ])->forceSave();
            });

        switch ($result) {
            case EmailVerificationBrokerContract::EMAIL_VERIFIED:
                return intend([
                    'url' => route('managerarea.cortex.auth.account.settings'),
                    'with' => ['success' => trans('cortex/auth::'.$result)],
                ]);

            case EmailVerificationBrokerContract::INVALID_USER:
            case EmailVerificationBrokerContract::INVALID_TOKEN:
            case EmailVerificationBrokerContract::EXPIRED_TOKEN:
            default:
                return intend([
                    'url' => route('managerarea.cortex.auth.account.verification.email.request'),
                    'withInput' => $request->only(['email']),
                    'withErrors' => ['email' => trans('cortex/auth::'.$result)],
                ]);
        }
    }
}
