<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Adminarea;

use Cortex\Fort\Http\Requests\Adminarea\AccountPasswordRequest;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;

class AccountPasswordController extends AuthenticatedController
{
    /**
     * Edit account possword.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('cortex/fort::adminarea.pages.account-password');
    }

    /**
     * Update account password.
     *
     * @param \Cortex\Fort\Http\Requests\Adminarea\AccountPasswordRequest $request
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
