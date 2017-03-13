<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Cortex Fort Module.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Cortex Fort Module
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Frontend;

use Illuminate\Support\Facades\Auth;

class PhoneVerificationSendRequest extends PhoneVerificationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->user() ?? Auth::guard()->attemptUser();

        return $this->isMethod('post') ? [
            'phone' => 'required|numeric|exists:'.config('rinvex.fort.tables.users').',phone,id,'.$user->id,
            'method' => 'required',
        ] : [];
    }
}
