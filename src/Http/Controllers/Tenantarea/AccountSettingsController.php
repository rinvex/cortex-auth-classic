<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Tenantarea;

use Illuminate\Http\Request;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;
use Cortex\Fort\Http\Requests\Tenantarea\AccountSettingsRequest;

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
        $genders = ['m' => trans('cortex/fort::common.male'), 'f' => trans('cortex/fort::common.female')];

        return view('cortex/fort::tenantarea.pages.settings', compact('countries', 'languages', 'genders'));
    }

    /**
     * Process the account update form.
     *
     * @param \Cortex\Fort\Http\Requests\Tenantarea\AccountSettingsRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(AccountSettingsRequest $request)
    {
        $data = $request->validated();
        $currentUser = $request->user($this->getGuard());

        // Update profile
        $currentUser->fill($data)->save();

        if (config('rinvex.fort.emailverification.required')) {
            return intend([
                'url' => route('tenantarea.verification.email.request'),
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
