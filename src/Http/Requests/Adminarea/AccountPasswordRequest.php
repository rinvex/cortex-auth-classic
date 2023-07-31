<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

use Cortex\Foundation\Http\FormRequest;

class AccountPasswordRequest extends FormRequest
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
        $validator->after(function ($validator) {
            if (! auth()->getProvider()->validateCredentials($this->user(), ['password' => $this->get('old_password')])) {
                $validator->errors()->add('old_password', trans('cortex/auth::messages.account.wrong_password'));
            }

            if (auth()->getProvider()->validateCredentials($this->user(), ['password' => $this->get('new_password')])) {
                $validator->errors()->add('new_password', trans('cortex/auth::messages.account.different_password'));
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
            'old_password' => ['required', config('validation.rules.password')],
            'new_password' => ['required', 'confirmed', config('validation.rules.password')],
        ];
    }
}
