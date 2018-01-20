<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Tenantarea;

class PhoneVerificationProcessRequest extends PhoneVerificationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return ['token' => 'required|integer'];
    }
}
