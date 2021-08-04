<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Frontarea;

use Cortex\Foundation\Http\FormRequest;
use Cortex\Foundation\Exceptions\GenericException;

class PasswordResetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Cortex\Foundation\Exceptions\GenericException
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if ($this->user()) {
            throw new GenericException(trans('cortex/auth::messages.passwordreset.already_logged'), route('frontarea.cortex.auth.account.settings').'#security-tab');
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
