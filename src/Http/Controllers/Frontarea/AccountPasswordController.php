<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Frontarea;

use Cortex\Auth\Http\Requests\Frontarea\AccountPasswordRequest;
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
        return view('cortex/auth::frontarea.pages.account-password');
    }

    /**
     * Update account password.
     *
     * @param \Cortex\Auth\Http\Requests\Frontarea\AccountPasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(AccountPasswordRequest $request)
    {
        // Update profile
        app('request.user')->fill(['password' => $request->get('new_password')])->forceSave();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/auth::messages.account.updated_password')],
        ]);
    }
}
