<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Tenantarea;

use Illuminate\Foundation\Http\FormRequest;
use Rinvex\Fort\Exceptions\GenericException;

class PhoneVerificationSendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Rinvex\Fort\Exceptions\GenericException
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $attemptUser = auth()->attemptUser();

        if ($user && ! $user->country_code) {
            // Country field required for phone verification
            throw new GenericException(trans('cortex/fort::messages.account.country_required'), route('tenantarea.account.settings'));
        }

        if ($user && ! $user->phone) {
            // Phone field required before verification
            throw new GenericException(trans('cortex/fort::messages.account.phone_required'), route('tenantarea.account.settings'));
        }

        if ($attemptUser && ! $attemptUser->country_code) {
            // Country field required for TwoFactor authentication
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.phone.country_required'), route('tenantarea.home'));
        }

        if ($attemptUser && ! $attemptUser->phone) {
            // Phone field required for TwoFactor authentication
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.phone.phone_required'), route('tenantarea.home'));
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
