<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Adminarea;

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
        if ($this->user()->can('grant-abilities') && $data['abilities']) {
            $data['abilities'] = array_map('intval', $data['abilities']);
            $data['abilities'] = $this->user()->isSuperadmin() ? $data['abilities']
                : array_intersect($this->user()->allAbilities->pluck('id')->toArray(), $data['abilities']);
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
        $user = $this->route('role') ?? app('rinvex.fort.role');
        $user->updateRulesUniques();
        $rules = $user->getRules();
        $rules['abilities'] = 'nullable|array';

        return $rules;
    }
}
