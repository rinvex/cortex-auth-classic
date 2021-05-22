<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Tenantarea;

use Cortex\Auth\Exceptions\AccountException;

class PhoneVerificationProcessRequest extends PhoneVerificationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Cortex\Auth\Exceptions\AccountException
     *
     * @return bool
     */
    public function authorize(): bool
    {
        parent::authorize();

        $user = $this->user()
                ?? $this->attemptUser()
                   ?? app('cortex.auth.member')->whereNotNull('phone')->where('phone', $this->get('phone'))->first();

        if (! $user) {
            // User instance required to detect active TwoFactor methods
            throw new AccountException(trans('cortex/auth::messages.unauthenticated'), route('tenantarea.cortex.auth.account.login'), null, 401);
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
        return [
            'token' => 'required|digits_between:6,10',
        ];
    }
}
