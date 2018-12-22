<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

use Illuminate\Foundation\Http\FormRequest;

class AccountTwoFactorPhoneRequest extends FormRequest
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
        return true;
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator): void
    {
        $user = $this->user($this->route('guard'));

        $validator->after(function ($validator) use ($user) {
            if (! $user->phone || ! $user->hasVerifiedPhone()) {
                $validator->errors()->add('phone', trans('cortex/auth::messages.account.'.(! $user->phone ? 'phone_field_required' : 'phone_verification_required')));
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
        return [];
    }
}
