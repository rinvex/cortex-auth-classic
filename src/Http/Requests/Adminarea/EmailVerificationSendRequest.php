<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

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
            'email' => ['required', ...config('validation.rules.email'), 'exists:'.config('cortex.auth.models.admin').',email'],
        ];
    }
}
