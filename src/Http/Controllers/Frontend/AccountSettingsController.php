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

        return view('cortex/fort::frontend.account.settings', compact('twoFactor', 'countries', 'languages'));
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
        $input = $request->all();
        $currentUser = $request->user($this->getGuard());

        // Update profile
        $currentUser->fill($input)->save();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/fort::messages.account.'.(isset($input['email_verified']) ? 'reverify' : 'updated'))]
                      + (isset($input['two_factor']) ? ['warning' => trans('cortex/fort::messages.verification.twofactor.phone.auto_disabled')] : []),
        ]);
    }
}
