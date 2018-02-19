<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

use Cortex\Auth\Models\Role;
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
        if ($this->user($this->get('guard'))->can('grant', \Cortex\Auth\Models\Ability::class)) {
            $abilities = array_map('intval', $this->get('abilities', []));
            $data['abilities'] = $this->user($this->get('guard'))->can('superadmin') ? $abilities
                : $this->user($this->get('guard'))->abilities->pluck('id')->intersect($abilities)->toArray();
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
