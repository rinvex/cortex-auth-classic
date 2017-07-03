<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Cortex\Fort\Http\Requests\Frontend\AccountSettingsRequest;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;

class AccountSettingsController extends AuthenticatedController
{
    /**
     * Show the account update form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $countries = countries();
        $languages = collect(languages())->pluck('name', 'iso_639_1');
        $twoFactor = $request->user($this->getGuard())->getTwoFactor();
        $genders = ['m' => trans('common.male'), 'f' => trans('common.female')];

        return view('cortex/fort::frontend.account.settings', compact('twoFactor', 'countries', 'languages', 'genders'));
    }

    /**
     * Process the account update form.
     *
     * @param \Cortex\Fort\Http\Requests\Frontend\AccountSettingsRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(AccountSettingsRequest $request)
    {
        $data = $request->all();
        $currentUser = $request->user($this->getGuard());

        // Update profile
        $currentUser->fill($data)->save();

        if (config('rinvex.fort.emailverification.required')) {
            return intend([
                'url' => route('frontend.verification.email.request'),
                'with' => ['success' => trans('cortex/fort::messages.account.reverify')]
                          + (isset($data['two_factor']) ? ['warning' => trans('cortex/fort::messages.verification.twofactor.phone.auto_disabled')] : []),
            ]);
        }

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/fort::messages.account.updated')]
                      + (isset($data['two_factor']) ? ['warning' => trans('cortex/fort::messages.verification.twofactor.phone.auto_disabled')] : []),
        ]);
    }
}
