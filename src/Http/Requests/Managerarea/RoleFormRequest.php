<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Managerarea;

use Cortex\Fort\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class RoleFormRequest extends FormRequest
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

        // Set abilities
        if ($this->user()->can('grant', \Cortex\Fort\Models\Ability::class)) {
            $data['abilities'] = $this->user()->can('superadmin') ? $this->get('abilities', [])
                : $this->user()->abilities->pluck('id')->intersect($this->get('abilities', []))->toArray();
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
        $user = $this->route('role') ?? new Role;
        $user->updateRulesUniques();
        $rules = $user->getRules();
        $rules['abilities'] = 'nullable|array';

        return $rules;
    }
}
