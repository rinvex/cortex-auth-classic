<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Frontarea;

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $mediaSize = config('cortex.foundation.media.size');
        $mediaMimetypes = config('cortex.foundation.media.mimetypes');

        $user = $this->user($this->route('guard'));
        $user->updateRulesUniques();
        $rules = $user->getRules();

        $rules['profile_picture'] = 'nullable|mimetypes:'.$mediaMimetypes.'|size'.$mediaSize;
        $rules['cover_photo'] = 'nullable|mimetypes:'.$mediaMimetypes.'|size'.$mediaSize;

        return $rules;
    }
}
