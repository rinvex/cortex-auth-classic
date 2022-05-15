<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Managerarea;

use Rinvex\Auth\Contracts\PasswordResetBrokerContract;

class PasswordResetProcessRequest extends PasswordResetRequest
{
    /**
     * @TODO: Review and refactor!
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator): void
    {
        $credentials = $this->only('email', 'expiration', 'token');
        $passwordResetBroker = app('auth.password')->broker($this->passwordResetBroker());

        $validator->after(function ($validator) use ($passwordResetBroker, $credentials) {
            if (! ($user = $passwordResetBroker->getUser($credentials))) {
                $validator->errors()->add('email', trans('cortex/auth::'.PasswordResetBrokerContract::INVALID_USER));
            }

            if ($user && ! $passwordResetBroker->validateToken($user, $credentials)) {
                $validator->errors()->add('email', trans('cortex/auth::'.PasswordResetBrokerContract::INVALID_TOKEN));
            }

            if (empty($credentials['expiration']) || ! $passwordResetBroker->validateTimestamp($credentials['expiration'])) {
                $validator->errors()->add('email', trans('cortex/auth::'.PasswordResetBrokerContract::EXPIRED_TOKEN));
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Do not validate `token` here since at this stage we can NOT generate viewable error,
            // and it is been processed in the controller through PasswordResetBroker anyway
            //'token' => 'required|regex:/^([0-9a-f]*)$/',
            'email' => 'required|email:rfc,dns|min:3|max:128|exists:'.config('cortex.auth.tables.managers').',email',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getRedirectUrl()
    {
        return $this->redirector->getUrlGenerator()->route('managerarea.cortex.auth.account.passwordreset.request');
    }
}
