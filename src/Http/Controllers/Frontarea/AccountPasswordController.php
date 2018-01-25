<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Frontarea;

use Cortex\Fort\Http\Requests\Frontarea\AccountPasswordRequest;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;

class AccountPasswordController extends AuthenticatedController
{
    /**
     * Show the account update form.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('cortex/fort::frontarea.pages.account-password');
    }

    /**
     * Process the account update form.
     *
     * @param \Cortex\Fort\Http\Requests\Frontarea\AccountPasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(AccountPasswordRequest $request)
    {
        $currentUser = $request->user($this->getGuard());

        // Update profile
        $currentUser->fill(['password' => $request->get('new_password')])->save();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/fort::messages.account.updated_password')],
        ]);
    }
}
