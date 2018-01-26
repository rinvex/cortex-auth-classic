<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Frontarea;

use Illuminate\Foundation\Http\FormRequest;

class AccountSettingsRequest extends FormRequest
{
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
        $user = $this->user();
        $twoFactor = $user->getTwoFactor();

        if ($email !== $user->email) {
            $data['email_verified'] = false;
            $data['email_verified_at'] = null;
        }

        if ($phone !== $user->phone) {
            $data['phone_verified'] = false;
            $data['phone_verified_at'] = null;
        }

        if ($twoFactor && (isset($data['phone_verified']) || $country !== $user->country_code)) {
            array_set($twoFactor, 'phone.enabled', false);
            $data['two_factor'] = $twoFactor;
        }

        $this->replace($data);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $user = $this->user();
        $user->updateRulesUniques();

        return $user->getRules();
    }
}
