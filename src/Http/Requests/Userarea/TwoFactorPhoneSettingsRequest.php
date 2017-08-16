<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Userarea;

use Rinvex\Fort\Exceptions\GenericException;
use Rinvex\Support\Http\Requests\FormRequest;

class TwoFactorPhoneSettingsRequest extends FormRequest
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

        if (! in_array('phone', config('rinvex.fort.twofactor.providers'))) {
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.phone.globaly_disabled'), route('userarea.account.settings'));
        }

        if (mb_strpos($this->route()->getName(), 'userarea.account.twofactor.phone') !== false && (! $user->phone || ! $user->phone_verified)) {
            throw new GenericException(trans('cortex/fort::messages.account.'.(! $user->phone ? 'phone_field_required' : 'phone_verification_required')), route('userarea.account.settings'));
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