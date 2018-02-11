<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Tenantarea;

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
            'phone' => 'required|numeric|min:4|exists:'.config('cortex.fort.tables.users').',phone',
        ];
    }
}
