<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Backend;

use Carbon\Carbon;
use Cortex\Fort\Models\User;
use Rinvex\Support\Http\Requests\FormRequest;

class UserFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Cortex\Foundation\Exceptions\GenericException
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Process given request data before validation.
     *
     * @param array $data
     *
     * @return array
     */
    public function process($data)
    {
        $user = $this->route('user') ?? new User();
        $country = $data['country_code'] ?? null;
        $password = $data['password'] ?? null;
        $twoFactor = $user->getTwoFactor();

        $data['email_verified'] = $this->get('email_verified', false);
        $data['phone_verified'] = $this->get('phone_verified', false);

        if (! $password && $user->exists) {
            unset($data['password'], $data['password_confirmation']);
        }

        // Update email verification date
        if ($data['email_verified'] && $user->email_verified !== $data['email_verified']) {
            $data['email_verified_at'] = Carbon::now();
        }

        // Update phone verification date
        if ($data['phone_verified'] && $user->phone_verified !== $data['phone_verified']) {
            $data['phone_verified_at'] = Carbon::now();
        }

        if ($twoFactor && (isset($data['phone_verified_at']) || $country !== $user->country_code)) {
            array_set($twoFactor, 'phone.enabled', false);
            $data['two_factor'] = $twoFactor;
        }

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->route('user') ?? new User();
        $user->updateRulesUniques();
        $rules = $user->getRules();
        $rules['password'] = 'sometimes|required|confirmed|min:'.config('rinvex.fort.password_min_chars');

        return $rules;
    }
}
