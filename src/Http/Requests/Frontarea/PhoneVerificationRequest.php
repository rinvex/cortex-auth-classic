<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Frontarea;

use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\Exceptions\GenericException;

class PhoneVerificationRequest extends FormRequest
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
        $user = $this->user()
                ?? $this->attemptUser()
                   ?? app('rinvex.fort.user')->whereNotNull('phone')->where('phone', $this->get('phone'))->first();

        if ($user && $user->phone_verified) {
            // Redirect users if their phone already verified, no need to process their request
            throw new GenericException(trans('cortex/fort::messages.verification.phone.already_verified'), route('frontarea.account.settings'));
        }

        if ($user && ! $user->phone) {
            // Phone field required before verification
            throw new GenericException(trans('cortex/fort::messages.account.phone_required'), route('frontarea.account.settings'));
        }

        if ($user && ! $user->country_code) {
            // Country field required for phone verification
            throw new GenericException(trans('cortex/fort::messages.account.country_required'), route('frontarea.account.settings'));
        }

        if ($user && ! $user->email_verified) {
            // Email verification required for phone verification
            throw new GenericException(trans('cortex/fort::messages.account.email_verification_required'), route('frontarea.verification.email.request'));
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
