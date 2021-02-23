<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

use Illuminate\Support\Arr;
use Rinvex\Support\Traits\Escaper;
use Cortex\Foundation\Http\FormRequest;

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

        $resetTwoFactor = false;
        $country = $data['country_code'] ?? null;
        $email = $data['email'] ?? null;
        $phone = $data['phone'] ?? null;
        $user = $this->user();
        $twoFactor = $user->getTwoFactor();

        if ($email !== $user->email) {
            $resetTwoFactor = true;
        }

        if ($phone !== $user->phone || $country !== $user->country_code) {
            $resetTwoFactor = true;
        }

        if ($twoFactor || $resetTwoFactor) {
            $data['phone_verified_at'] = null;
            Arr::set($twoFactor, 'phone.enabled', false);
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

        $user = $this->user();
        $user->updateRulesUniques();
        $rules = $user->getRules();

        $rules['profile_picture'] = 'nullable|mimetypes:'.$mediaMimetypes.'|max:'.$mediaSize;
        $rules['cover_photo'] = 'nullable|mimetypes:'.$mediaMimetypes.'|max:'.$mediaSize;

        return $rules;
    }
}
