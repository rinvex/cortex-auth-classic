<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

use Illuminate\Foundation\Http\FormRequest;

class ManagerFormRequest extends FormRequest
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

        $manager = $this->route('manager') ?? app('cortex.auth.manager');
        $country = $data['country_code'] ?? null;
        $twoFactor = $manager->getTwoFactor();

        $data['email_verified'] = $this->get('email_verified', false);
        $data['phone_verified'] = $this->get('phone_verified', false);

        if ($manager->exists && empty($data['password'])) {
            unset($data['password'], $data['password_confirmation']);
        }

        // Update email verification date
        if ($data['email_verified'] && $manager->email_verified !== $data['email_verified']) {
            $data['email_verified_at'] = now();
        }

        // Update phone verification date
        if ($data['phone_verified'] && $manager->phone_verified !== $data['phone_verified']) {
            $data['phone_verified_at'] = now();
        }

        // Set abilities
        if ($this->user($this->get('guard'))->can('grant', \Cortex\Auth\Models\Ability::class)) {
            $data['abilities'] = $this->user($this->get('guard'))->can('superadmin') ? $this->get('abilities', [])
                : $this->user($this->get('guard'))->abilities->pluck('id')->intersect($this->get('abilities', []))->toArray();
        } else {
            unset($data['abilities']);
        }

        // Set roles
        if ($this->user($this->get('guard'))->can('assign', \Cortex\Auth\Models\Role::class) && $data['roles']) {
            $data['roles'] = $this->user($this->get('guard'))->can('superadmin') ? $this->get('roles', [])
                : $this->user($this->get('guard'))->roles->pluck('id')->intersect($this->get('roles', []))->toArray();
        } else {
            unset($data['roles']);
        }

        if ($twoFactor && (isset($data['phone_verified_at']) || $country !== $manager->country_code)) {
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
        $manager = $this->route('manager') ?? app('cortex.auth.manager');
        $manager->updateRulesUniques();
        $rules = $manager->getRules();

        $rules['roles'] = 'nullable|array';
        $rules['abilities'] = 'nullable|array';
        $rules['password'] = $manager->exists
            ? 'confirmed|min:'.config('cortex.auth.password_min_chars')
            : 'required|confirmed|min:'.config('cortex.auth.password_min_chars');

        return $rules;
    }
}
