<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

use Illuminate\Foundation\Http\FormRequest;

class AdminFormRequest extends FormRequest
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

        $admin = $this->route('admin') ?? app('cortex.auth.admin');
        $country = $data['country_code'] ?? null;
        $twoFactor = $admin->getTwoFactor();

        $data['email_verified'] = $this->get('email_verified', false);
        $data['phone_verified'] = $this->get('phone_verified', false);

        if ($admin->exists && empty($data['password'])) {
            unset($data['password'], $data['password_confirmation']);
        }

        // Update email verification date
        if ($data['email_verified'] && $admin->email_verified !== $data['email_verified']) {
            $data['email_verified_at'] = now();
        }

        // Update phone verification date
        if ($data['phone_verified'] && $admin->phone_verified !== $data['phone_verified']) {
            $data['phone_verified_at'] = now();
        }

        // Set abilities
        if ($data['abilities'] && $this->user($this->get('guard'))->can('grant', \Cortex\Auth\Models\Ability::class)) {
            $abilities = array_map('intval', $this->get('abilities', []));
            $data['abilities'] = $this->user($this->get('guard'))->can('superadmin') ? $abilities
                : $this->user($this->get('guard'))->getAbilities()->pluck('id')->intersect($abilities)->toArray();
        } else {
            unset($data['abilities']);
        }

        // Set roles
        if ($data['roles'] && $this->user($this->get('guard'))->can('assign', \Cortex\Auth\Models\Role::class)) {
            $roles = array_map('intval', $this->get('roles', []));
            $data['roles'] = $this->user($this->get('guard'))->can('superadmin') ? $roles
                : $this->user($this->get('guard'))->roles->pluck('id')->intersect($roles)->toArray();
        } else {
            unset($data['roles']);
        }

        if ($twoFactor && (isset($data['phone_verified_at']) || $country !== $admin->country_code)) {
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
        $admin = $this->route('admin') ?? app('cortex.auth.admin');
        $admin->updateRulesUniques();
        $rules = $admin->getRules();

        $rules['roles'] = 'nullable|array';
        $rules['abilities'] = 'nullable|array';
        $rules['password'] = $admin->exists
            ? 'confirmed|min:'.config('cortex.auth.password_min_chars')
            : 'required|confirmed|min:'.config('cortex.auth.password_min_chars');

        return $rules;
    }
}
