<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Tenantarea;

use Illuminate\Foundation\Http\FormRequest;
use Rinvex\Fort\Exceptions\GenericException;

class PhoneVerificationProcessRequest extends FormRequest
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
        if (empty(config('rinvex.fort.twofactor.providers'))) {
            // At least one TwoFactor provider required for phone verification
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.globaly_disabled'), route('frontarea.account.settings'));
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
        return ['token' => 'required|integer'];
    }
}
