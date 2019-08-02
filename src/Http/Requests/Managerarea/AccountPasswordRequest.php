<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Managerarea;

use Illuminate\Foundation\Http\FormRequest;

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
            if (! auth()->guard($this->route('guard'))->getProvider()->validateCredentials($this->user($this->route('guard')), ['password' => $this->get('old_password')])) {
                $validator->errors()->add('old_password', trans('cortex/auth::messages.account.wrong_password'));
            }

            if (auth()->guard($this->route('guard'))->getProvider()->validateCredentials($this->user($this->route('guard')), ['password' => $this->get('new_password')])) {
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
            'old_password' => 'required|min:'.config('cortex.auth.password_min_chars'),
            'new_password' => 'required|confirmed|min:'.config('cortex.auth.password_min_chars'),
        ];
    }
}
