<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Tenantarea;

use Cortex\Fort\Traits\TwoFactorAuthenticatesUsers;
use Cortex\Foundation\Http\Controllers\AbstractController;
use Cortex\Fort\Http\Requests\Tenantarea\PhoneVerificationRequest;
use Cortex\Fort\Http\Requests\Tenantarea\PhoneVerificationSendRequest;
use Cortex\Fort\Http\Requests\Tenantarea\PhoneVerificationVerifyRequest;
use Cortex\Fort\Http\Requests\Tenantarea\PhoneVerificationProcessRequest;

class PhoneVerificationController extends AbstractController
{
    use TwoFactorAuthenticatesUsers;

    /**
     * Show the phone verification form.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\PhoneVerificationRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function request(PhoneVerificationRequest $request)
    {
        return view('cortex/fort::tenantarea.pages.verification-phone-request');
    }

    /**
     * Process the phone verification request form.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\PhoneVerificationSendRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function send(PhoneVerificationSendRequest $request)
    {
        $user = $request->user($this->getGuard())
                ?? $request->attemptUser($this->getGuard())
                   ?? app('cortex.fort.user')->whereNotNull('phone')->where('phone', $request->input('phone'))->first();

        $user->sendPhoneVerificationNotification($request->get('method'), true);

        return intend([
            'url' => route('tenantarea.verification.phone.verify', ['phone' => $user->phone]),
            'with' => ['success' => trans('cortex/fort::messages.verification.phone.sent')],
        ]);
    }

    /**
     * Show the phone verification form.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\PhoneVerificationVerifyRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function verify(PhoneVerificationVerifyRequest $request)
    {
        return view('cortex/fort::tenantarea.pages.verification-phone-token');
    }

    /**
     * Process the phone verification form.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\PhoneVerificationProcessRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function process(PhoneVerificationProcessRequest $request)
    {
        // Guest trying to authenticate through TwoFactor
        if (($attemptUser = $request->attemptUser($this->getGuard())) && $this->attemptTwoFactor($attemptUser, $request->get('token'))) {
            auth()->guard($this->getGuard())->login($attemptUser, $request->session()->get('cortex.fort.twofactor.remember'));
            $request->session()->forget('cortex.fort.twofactor'); // @TODO: Do we need to forget session, or it's already gone after login?

            return intend([
                'intended' => route('tenantarea.home'),
                'with' => ['success' => trans('cortex/fort::messages.auth.login')],
            ]);
        }

        // Logged in user OR A GUEST trying to verify phone
        if (($user = $request->user($this->getGuard()) ?? app('cortex.fort.user')->whereNotNull('phone')->where('phone', $request->get('phone'))->first()) && $this->isValidTwoFactorPhone($user, $request->get('token'))) {
            // Profile update
            $user->fill([
                'phone_verified' => true,
                'phone_verified_at' => now(),
            ])->forceSave();

            return intend([
                'url' => route('tenantarea.account.settings'),
                'with' => ['success' => trans('cortex/fort::messages.verification.phone.verified')],
            ]);
        }

        return intend([
            'back' => true,
            'withErrors' => ['token' => trans('cortex/fort::messages.verification.twofactor.invalid_token')],
        ]);
    }
}
