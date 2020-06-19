<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Managerarea;

use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\Exceptions\GenericException;

class EmailVerificationRequest extends FormRequest
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
        $user = $this->user(app('request.guard')) ?: $this->attemptUser(app('request.guard'));

        // Redirect users if their email already verified, no need to process their request
        if ($user && $user->hasVerifiedEmail()) {
            throw new GenericException(trans('cortex/auth::messages.verification.email.already_verified'), route('managerarea.account.settings'));
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
