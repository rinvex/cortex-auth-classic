<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Tenantarea;

use Illuminate\Foundation\Http\FormRequest;
use Rinvex\Fort\Exceptions\GenericException;

class PhoneVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Rinvex\Fort\Exceptions\GenericException
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $attemptUser = auth()->attemptUser();

        if (empty(config('rinvex.fort.twofactor.providers'))) {
            // At least one TwoFactor provider required for phone verification
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.globaly_disabled'), ! $user ? route('tenantarea.login') : route('tenantarea.account.settings'));
        }

        if (! $user && ! $attemptUser) {
            // User instance required to detect active TwoFactor methods
            throw new GenericException(trans('cortex/foundation::messages.session_required'), route('tenantarea.login'));
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
