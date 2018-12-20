<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

use Rinvex\Support\Traits\Escaper;
use Illuminate\Foundation\Http\FormRequest;

class AccountSettingsRequest extends FormRequest
{
    use Escaper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        $country = $data['country_code'] ?? null;
        $email = $data['email'] ?? null;
        $phone = $data['phone'] ?? null;
        $user = $this->user($this->route('guard'));
        $twoFactor = $user->getTwoFactor();

        if ($email !== $user->email) {
            $data['email_verified_at'] = null;
        }

        if ($phone !== $user->phone || $country !== $user->country_code) {
            $data['phone_verified_at'] = null;
        }

        if ($twoFactor || is_null($data['phone_verified_at'])) {
            array_set($twoFactor, 'phone.enabled', false);
            $data['two_factor'] = $twoFactor;
        }

        $this->replace($data);
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
        $this->replace($this->escape($this->all()));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $user = $this->user($this->route('guard'));
        $user->updateRulesUniques();

        return $user->getRules();
    }
}
