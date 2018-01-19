<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Managerarea;

use Illuminate\Foundation\Http\FormRequest;

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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        // Set abilities
        if ($this->user()->can('grant-abilities') && $data['abilities']) {
            $owner = optional(optional(config('rinvex.tenants.active'))->owner)->getKey();

            $data['abilities'] = $this->user()->getKey() === $owner
                ? array_intersect(app('rinvex.fort.role')->forAllTenants()->where('slug', 'manager')->first()->abilities->pluck('id')->toArray(), $data['abilities'])
                : array_intersect($this->user()->allAbilities->pluck('id')->toArray(), $data['abilities']);
        } else {
            unset($data['abilities']);
        }

        // Prefix slug
        $prefix = optional(config('rinvex.tenants.active'))->slug.'-';
        starts_with($data['slug'], $prefix) || $data['slug'] = str_start($data['slug'], $prefix);

        $this->replace($data);
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
        $rules = $user->getRules();
        $rules['abilities'] = 'nullable|array';

        return $rules;
    }
}
