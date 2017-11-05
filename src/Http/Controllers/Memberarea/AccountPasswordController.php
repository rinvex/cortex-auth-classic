<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Memberarea;

use Illuminate\Http\Request;
use Cortex\Foundation\Http\Controllers\AuthenticatedController;
use Cortex\Fort\Http\Requests\Memberarea\AccountPasswordRequest;

class AccountPasswordController extends AuthenticatedController
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
        return view('cortex/fort::memberarea.forms.password');
    }

    /**
     * Process the account update form.
     *
     * @param \Cortex\Fort\Http\Requests\Memberarea\AccountPasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(AccountPasswordRequest $request)
    {
        $data = $request->all();
        $currentUser = $request->user($this->getGuard());

        // Update password
        $currentUser->fill($data)->save();

        return intend([
            'back' => true,
            'with' => ['success' => trans('cortex/fort::messages.account.updated')],
        ]);
    }
}
