<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Managerarea;

use Carbon\Carbon;
use Cortex\Auth\Traits\TwoFactorAuthenticatesUsers;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Cortex\Auth\Http\Requests\Managerarea\PhoneVerificationRequest;
use Cortex\Auth\Http\Requests\Managerarea\PhoneVerificationSendRequest;
use Cortex\Auth\Http\Requests\Managerarea\PhoneVerificationVerifyRequest;
use Cortex\Auth\Http\Requests\Managerarea\PhoneVerificationProcessRequest;

class PhoneVerificationController extends AbstractController
{
    use TwoFactorAuthenticatesUsers;

    /**
     * Show the phone verification form.
     *
     * @param \Cortex\Auth\Http\Requests\Managerarea\PhoneVerificationRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function request(PhoneVerificationRequest $request)
    {
        return view('cortex/auth::managerarea.pages.verification-phone-request');
    }

    /**
     * Process the phone verification request form.
     *
     * @param \Cortex\Auth\Http\Requests\Managerarea\PhoneVerificationSendRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function send(PhoneVerificationSendRequest $request)
    {
        $user = $request->user()
                ?? $request->attemptUser()
                   ?? app('cortex.auth.manager')->whereNotNull('phone')->where('phone', $request->input('phone'))->first();

        $user->sendPhoneVerificationNotification($request->input('method'), true);

        return intend([
            'url' => route('managerarea.cortex.auth.account.verification.phone.verify', ['phone' => $user->phone]),
            'with' => ['success' => trans('cortex/auth::messages.verification.phone.sent')],
        ]);
    }

    /**
     * Show the phone verification form.
     *
     * @param \Cortex\Auth\Http\Requests\Managerarea\PhoneVerificationVerifyRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function verify(PhoneVerificationVerifyRequest $request)
    {
        return view('cortex/auth::managerarea.pages.verification-phone-token');
    }

    /**
     * Process the phone verification form.
     *
     * @param \Cortex\Auth\Http\Requests\Managerarea\PhoneVerificationProcessRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function process(PhoneVerificationProcessRequest $request)
    {
        // Guest trying to authenticate through TwoFactor
        if (($attemptUser = $request->attemptUser()) && $this->attemptTwoFactor($attemptUser, $request->input('token'))) {
            auth()->login($attemptUser, $request->session()->get('cortex.auth.twofactor.remember'));
            $request->session()->forget('cortex.auth.twofactor'); // @TODO: Do we need to forget session, or it's already gone after login?

            return intend([
                'intended' => route('managerarea.home'),
                'with' => ['success' => trans('cortex/auth::messages.auth.login')],
            ]);
        }

        // Logged in user OR A GUEST trying to verify phone
        if (($user = $request->user() ?? app('cortex.auth.manager')->whereNotNull('phone')->where('phone', $request->input('phone'))->first()) && $this->isValidTwoFactorPhone($user, $request->input('token'))) {
            // Profile update
            $user->fill([
                'phone_verified_at' => Carbon::now(),
            ])->forceSave();

            return intend([
                'url' => route('managerarea.cortex.auth.account.settings'),
                'with' => ['success' => trans('cortex/auth::messages.verification.phone.verified')],
            ]);
        }

        return intend([
            'back' => true,
            'withErrors' => ['token' => trans('cortex/auth::messages.verification.twofactor.invalid_token')],
        ]);
    }
}
