<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Frontarea;

use Carbon\Carbon;
use Cortex\Auth\Traits\TwoFactorAuthenticatesUsers;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Cortex\Auth\Http\Requests\Frontarea\PhoneVerificationRequest;
use Cortex\Auth\Http\Requests\Frontarea\PhoneVerificationSendRequest;
use Cortex\Auth\Http\Requests\Frontarea\PhoneVerificationVerifyRequest;
use Cortex\Auth\Http\Requests\Frontarea\PhoneVerificationProcessRequest;

class PhoneVerificationController extends AbstractController
{
    use TwoFactorAuthenticatesUsers;

    /**
     * Show the phone verification form.
     *
     * @param \Cortex\Auth\Http\Requests\Frontarea\PhoneVerificationRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function request(PhoneVerificationRequest $request)
    {
        return view('cortex/auth::frontarea.pages.verification-phone-request');
    }

    /**
     * Process the phone verification request form.
     *
     * @param \Cortex\Auth\Http\Requests\Frontarea\PhoneVerificationSendRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function send(PhoneVerificationSendRequest $request)
    {
        $user = $request->user(app('request.guard'))
                ?? $request->attemptUser(app('request.guard'))
                   ?? app('cortex.auth.member')->whereNotNull('phone')->where('phone', $request->input('phone'))->first();

        $user->sendPhoneVerificationNotification($request->get('method'), true);

        return intend([
            'url' => route('frontarea.cortex.auth.account.verification.phone.verify', ['phone' => $user->phone]),
            'with' => ['success' => trans('cortex/auth::messages.verification.phone.sent')],
        ]);
    }

    /**
     * Show the phone verification form.
     *
     * @param \Cortex\Auth\Http\Requests\Frontarea\PhoneVerificationVerifyRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function verify(PhoneVerificationVerifyRequest $request)
    {
        return view('cortex/auth::frontarea.pages.verification-phone-token');
    }

    /**
     * Process the phone verification form.
     *
     * @param \Cortex\Auth\Http\Requests\Frontarea\PhoneVerificationProcessRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function process(PhoneVerificationProcessRequest $request)
    {
        // Guest trying to authenticate through TwoFactor
        if (($attemptUser = $request->attemptUser(app('request.guard'))) && $this->attemptTwoFactor($attemptUser, $request->get('token'))) {
            auth()->guard(app('request.guard'))->login($attemptUser, $request->session()->get('cortex.auth.twofactor.remember'));
            $request->session()->forget('cortex.auth.twofactor'); // @TODO: Do we need to forget session, or it's already gone after login?

            return intend([
                'intended' => route('frontarea.home'),
                'with' => ['success' => trans('cortex/auth::messages.auth.login')],
            ]);
        }

        // Logged in user OR A GUEST trying to verify phone
        if (($user = $request->user(app('request.guard')) ?? app('cortex.auth.member')->whereNotNull('phone')->where('phone', $request->get('phone'))->first()) && $this->isValidTwoFactorPhone($user, $request->get('token'))) {
            // Profile update
            $user->fill([
                'phone_verified_at' => Carbon::now(),
            ])->forceSave();

            return intend([
                'url' => route('frontarea.cortex.auth.account.settings'),
                'with' => ['success' => trans('cortex/auth::messages.verification.phone.verified')],
            ]);
        }

        return intend([
            'back' => true,
            'withErrors' => ['token' => trans('cortex/auth::messages.verification.twofactor.invalid_token')],
        ]);
    }
}
