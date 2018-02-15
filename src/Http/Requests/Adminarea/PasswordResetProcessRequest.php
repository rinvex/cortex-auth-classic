<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Adminarea;

use Rinvex\Fort\Contracts\PasswordResetBrokerContract;

class PasswordResetProcessRequest extends PasswordResetRequest
{
    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator): void
    {
        $credentials = $this->only('email', 'expiration', 'token');
        $broker = app('auth.password')->broker($this->get('broker'));

        $validator->after(function ($validator) use ($broker, $credentials) {
            if (! ($user = $broker->getUser($credentials))) {
                $validator->errors()->add('email', trans('cortex/fort::'.PasswordResetBrokerContract::INVALID_USER));
            }

            if ($user && ! $broker->validateToken($user, $credentials)) {
                $validator->errors()->add('email', trans('cortex/fort::'.PasswordResetBrokerContract::INVALID_TOKEN));
            }

            if (! $broker->validateTimestamp($credentials['expiration'])) {
                $validator->errors()->add('email', trans('cortex/fort::'.PasswordResetBrokerContract::EXPIRED_TOKEN));
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
            // and it is been processed in the controller through EmailVerificationBroker anyway
            //'token' => 'required|regex:/^([0-9a-f]*)$/',
            'email' => 'required|email|min:3|max:150|exists:'.config('cortex.fort.tables.admins').',email',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getRedirectUrl()
    {
        return $this->redirector->getUrlGenerator()->route('adminarea.passwordreset.request');
    }
}
