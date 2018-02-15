<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Adminarea;

use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\Exceptions\GenericException;

class TwoFactorTotpBackupSettingsRequest extends FormRequest
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
        $user = $this->user($this->get('guard'));
        $twoFactor = $user->getTwoFactor();

        if (empty($twoFactor['totp']['enabled'])) {
            throw new GenericException(trans('cortex/fort::messages.verification.twofactor.totp.cant_backup'), route('adminarea.account.settings'));
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
