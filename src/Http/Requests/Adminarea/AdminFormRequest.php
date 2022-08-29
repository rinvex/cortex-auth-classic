<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

use Illuminate\Support\Arr;
use Rinvex\Support\Traits\Escaper;
use Cortex\Foundation\Http\FormRequest;
use Cortex\Foundation\Exceptions\GenericException;

class AdminFormRequest extends FormRequest
{
    use Escaper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Cortex\Foundation\Exceptions\GenericException
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (! $this->user()->isA('superadmin') && $this->user() !== $this->route('admin')) {
            throw new GenericException(trans('cortex/auth::messages.unauthorized'), route('adminarea.cortex.auth.admins.index'), null, 403);
        }

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

        if ($admin->exists && empty($data['password'])) {
            unset($data['password'], $data['password_confirmation']);
        }

        // Set abilities
        if (! empty($data['abilities'])) {
            if ($this->user()->can('grant', app('cortex.auth.ability'))) {
                $abilities = array_map('intval', $this->get('abilities', []));
                $data['abilities'] = $this->user()->isA('superadmin') ? $abilities
                    : $this->user()->getAbilities()->pluck('id')->intersect($abilities)->toArray();
            } else {
                unset($data['abilities']);
            }
        }

        // Set roles
        if (! empty($data['roles'])) {
            if ($data['roles'] && $this->user()->can('assign', app('cortex.auth.role'))) {
                $roles = array_map('intval', $this->get('roles', []));
                $data['roles'] = $this->user()->isA('superadmin') ? $roles
                    : $this->user()->roles->pluck('id')->intersect($roles)->toArray();
            } else {
                unset($data['roles']);
            }
        }

        if ($twoFactor && (isset($data['phone_verified_at']) || $country !== $admin->country_code)) {
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
        $admin = $this->route('admin') ?? app('cortex.auth.admin');
        $admin->updateRulesUniques();
        $rules = $admin->getRules();

        $rules['roles'] = 'nullable|array';
        $rules['abilities'] = 'nullable|array';
        $rules['password'] = $admin->exists
            ? 'confirmed|min:'.config('cortex.auth.password_min_chars').'|max:'.config('cortex.auth.password_max_chars')
            : 'required|confirmed|min:'.config('cortex.auth.password_min_chars').'|max:'.config('cortex.auth.password_max_chars');

        return in_array($this->getRealMethod(), ['PUT', 'POST', 'PATCH']) ? $rules : [];
    }
}
