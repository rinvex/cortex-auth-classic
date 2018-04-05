<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

class PhoneVerificationSendRequest extends PhoneVerificationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone' => 'required|phone:AUTO|exists:'.config('cortex.auth.tables.admins').',phone',
        ];
    }
}
