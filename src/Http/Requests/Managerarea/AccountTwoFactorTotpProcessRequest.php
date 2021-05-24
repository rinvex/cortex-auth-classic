<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Managerarea;

use Cortex\Foundation\Http\FormRequest;

class AccountTwoFactorTotpProcessRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Cortex\Auth\Exceptions\AccountException
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => 'required|digits:6',
        ];
    }
}
