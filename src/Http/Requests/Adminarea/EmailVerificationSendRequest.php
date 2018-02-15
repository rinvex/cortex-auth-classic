<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Adminarea;

class EmailVerificationSendRequest extends EmailVerificationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|min:3|max:150|exists:'.config('cortex.fort.tables.admins').',email',
        ];
    }
}
