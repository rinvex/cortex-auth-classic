<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

use Cortex\Foundation\Http\FormRequest;
use Cortex\Foundation\Exceptions\GenericException;

class EmailVerificationRequest extends FormRequest
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
        // Redirect users if their email already verified, no need to process their request
        if (($user = $this->user()) && $user->hasVerifiedEmail()) {
            throw new GenericException(trans('cortex/auth::messages.verification.email.already_verified'), route('adminarea.cortex.auth.account.settings'));
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
