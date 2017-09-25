<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Memberarea;

use Illuminate\Http\Request;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;
use Cortex\Fort\Http\Requests\Memberarea\AccountSettingsRequest;

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
        $genders = ['m' => trans('cortex/fort::common.male'), 'f' => trans('cortex/fort::common.female')];

        return view('cortex/fort::memberarea.forms.settings', compact('twoFactor', 'countries', 'languages', 'genders'));
    }

    /**
     * Process the account update form.
     *
     * @param \Cortex\Fort\Http\Requests\Memberarea\AccountSettingsRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(AccountSettingsRequest $request)
    {
        $data = $request->all();
        $currentUser = $request->user($this->getGuard());

        // Update profile
        $currentUser->fill($data)->save();

        // redirect the user to the email verification page
        // in case that this feature is enabled and the user changed their email address
        if (config('rinvex.fort.emailverification.required') && ! $currentUser->email_verified) {
            return intend([
                'url' => route('guestarea.verification.email.request'),
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
