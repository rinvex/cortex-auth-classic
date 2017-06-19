<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Frontend;

use Rinvex\Support\Http\Requests\FormRequest;
use Cortex\Foundation\Exceptions\GenericException;

class TwoFactorPhoneSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Cortex\Foundation\Exceptions\GenericException
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();

        if (! in_array('phone', config('rinvex.fort.twofactor.providers'))) {
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.phone.globaly_disabled'), route('frontend.account.settings'));
        }

        if (strpos($this->route()->getName(), 'frontend.account.twofactor.phone') !== false && (! $user->phone || ! $user->phone_verified)) {
            throw new GenericException(trans('cortex/fort::messages.account.'.(! $user->phone ? 'phone_field_required' : 'phone_verification_required')), route('frontend.account.settings'));
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
