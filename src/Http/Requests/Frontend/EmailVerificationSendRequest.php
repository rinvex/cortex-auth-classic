<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Frontend;

class EmailVerificationSendRequest extends EmailVerificationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|min:3|max:250|exists:'.config('rinvex.fort.tables.users').',email',
        ];
    }
}
