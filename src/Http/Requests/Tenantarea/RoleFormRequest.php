<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Tenantarea;

use Rinvex\Support\Http\Requests\FormRequest;

class RoleFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
        // Set abilities
        if ($this->user()->can('grant-abilities') && $data['abilities']) {
            $owner = optional(optional(config('rinvex.tenants.tenant.active'))->owner)->id;

            $data['abilities'] = $this->user()->id === $owner
                ? array_intersect(app('rinvex.fort.role')->forAllTenants()->where('slug', 'manager')->first()->abilities->pluck('id')->toArray(), $data['abilities'])
                : array_intersect($this->user()->allAbilities->pluck('id')->toArray(), $data['abilities']);
        } else {
            unset($data['abilities']);
        }

        // Prefix slug
        $prefix = optional(config('rinvex.tenants.tenant.active'))->slug.'-';
        starts_with($data['slug'], $prefix) || $data['slug'] = str_start($data['slug'], $prefix);

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->route('role') ?? app('rinvex.fort.role');
        $user->updateRulesUniques();

        return $user->getRules();
    }
}
