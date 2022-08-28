<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Managerarea;

use Illuminate\Support\Str;

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

        // Prepend tenant name to the role name
        Str::startsWith(app('request.tenant')->name.'_', $data['name']) || $data['name'] = Str::start($data['name'], app('request.tenant')->name.'_');

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

        $this->replace($data);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $user = $this->route('role') ?? app('cortex.auth.role');
        $user->updateRulesUniques();
        $rules = $user->getRules();
        $rules['abilities'] = 'nullable|array';

        return $rules;
    }
}
