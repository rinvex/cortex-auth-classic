<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Managerarea;

use Cortex\Foundation\Http\Controllers\AuthenticatedController;
use Cortex\Auth\Http\Requests\Managerarea\AccountPasswordRequest;

class AccountPasswordController extends AuthenticatedController
{
    /**
     * Edit account possword.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('cortex/auth::managerarea.pages.account-password');
    }

    /**
     * Update account password.
     *
     * @param \Cortex\Auth\Http\Requests\Managerarea\AccountPasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(AccountPasswordRequest $request)
    {
        // Update profile
        $request->user()->fill(['password' => $request->input('new_password')])->forceSave();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/auth::messages.account.updated_password')],
        ]);
    }
}
