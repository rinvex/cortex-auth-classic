<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Userarea;

use Rinvex\Support\Http\Requests\FormRequest;
use Cortex\Foundation\Exceptions\GenericException;

class TwoFactorTotpSettingsRequest extends FormRequest
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
        if (! in_array('totp', config('rinvex.fort.twofactor.providers'))) {
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.totp.globaly_disabled'), route('userarea.account.settings'));
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
