<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Frontarea;

use Cortex\Foundation\Http\FormRequest;
use Cortex\Auth\Exceptions\AccountException;

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
            throw new AccountException(trans('cortex/auth::messages.verification.email.already_verified'), route('frontarea.cortex.auth.account.settings'));
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
