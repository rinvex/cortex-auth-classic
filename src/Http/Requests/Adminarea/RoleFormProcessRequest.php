<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

use Cortex\Auth\Models\Role;

class RoleFormProcessRequest extends RoleFormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        // Set abilities
        if ($data['abilities'] && $this->user($this->get('guard'))->can('grant', \Cortex\Auth\Models\Ability::class)) {
            $abilities = array_map('intval', $this->get('abilities', []));
            $data['abilities'] = $this->user($this->get('guard'))->can('superadmin') ? $abilities
                : $this->user($this->get('guard'))->getAbilities()->pluck('id')->intersect($abilities)->toArray();
        } else {
            unset($data['abilities']);
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
        $user = $this->route('role') ?? new Role();
        $user->updateRulesUniques();
        $rules = $user->getRules();
        $rules['abilities'] = 'nullable|array';

        return $rules;
    }
}
