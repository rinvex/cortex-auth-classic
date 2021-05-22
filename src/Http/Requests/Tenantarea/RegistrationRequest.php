<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Tenantarea;

use Rinvex\Support\Traits\Escaper;
use Cortex\Foundation\Http\FormRequest;
use Cortex\Auth\Exceptions\AccountException;

class RegistrationRequest extends FormRequest
{
    use Escaper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Cortex\Auth\Exceptions\AccountException
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (! config('cortex.auth.registration.enabled')) {
            throw new AccountException(trans('cortex/auth::messages.register.disabled'));
        }

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
        // Sanitize input data before submission
        $this->replace(array_merge($this->all(), $this->escape($this->except(['password', 'password_confirmation']))));
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
