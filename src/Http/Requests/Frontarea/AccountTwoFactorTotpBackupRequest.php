<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Frontarea;

use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\Exceptions\GenericException;

class AccountTwoFactorTotpBackupRequest extends FormRequest
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
        $twoFactor = $this->user(app('request.guard'))->getTwoFactor();

        if (! $twoFactor['totp']['enabled']) {
            throw new GenericException(trans('cortex/auth::messages.verification.twofactor.totp.cant_backup'), route('frontarea.cortex.auth.account.settings'));
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
