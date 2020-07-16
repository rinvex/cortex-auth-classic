<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Frontarea;

use Illuminate\Http\Request;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;
use Cortex\Auth\Http\Requests\Frontarea\AccountAttributesRequest;

class AccountAttributesController extends AuthenticatedController
{
    /**
     * Edit account attributes.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        return view('cortex/auth::frontarea.pages.account-attributes');
    }

    /**
     * Update account attributes.
     *
     * @param \Cortex\Auth\Http\Requests\Frontarea\AccountAttributesRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(AccountAttributesRequest $request)
    {
        $data = $request->validated();

        // Update profile
        app('request.user')->fill($data)->save();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/auth::messages.account.updated_attributes')],
        ]);
    }
}
