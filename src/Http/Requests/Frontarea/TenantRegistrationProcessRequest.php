<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Frontarea;

class TenantRegistrationProcessRequest extends TenantRegistrationRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        $data['manager']['is_active'] = ! config('cortex.auth.registration.moderated');

        $this->replace($data);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $managerRules = app('cortex.auth.manager')->getRules();
        $managerRules['password'] = 'required|confirmed|min:'.config('cortex.auth.password_min_chars').'|max:'.config('cortex.auth.password_max_chars');
        $managerRules = array_combine(
            array_map(function ($key) {
                return 'manager.'.$key;
            }, array_keys($managerRules)),
            $managerRules
        );

        $tenantRules = app('rinvex.tenants.tenant')->getRules();
        $tenantRules = array_combine(
            array_map(function ($key) {
                return 'tenant.'.$key;
            }, array_keys($tenantRules)),
            $tenantRules
        );

        return array_merge($managerRules, $tenantRules);
    }
}
