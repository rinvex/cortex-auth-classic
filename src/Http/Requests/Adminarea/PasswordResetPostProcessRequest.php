<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

class PasswordResetPostProcessRequest extends PasswordResetRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //'token' => 'required|regex:/^([0-9a-f]*)$/',
            //'expiration' => 'required|date_format:U',
            // Do not validate `token` or `expiration` here since at this stage we can NOT generate viewable
            // error, and it is been processed in the controller through EmailVerificationBroker anyway
            'email' => ['required', ...config('validation.rules.email'), 'exists:'.config('cortex.auth.models.admin').',email'],
            'password' => ['required', 'confirmed', config('validation.rules.password')],
        ];
    }
}
