<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Adminarea;

use Rinvex\Support\Http\Requests\FormRequest;

class AbilityFormRequest extends FormRequest
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
            $data['abilities'] = $this->user()->isSuperadmin() ? $data['abilities']
                : array_intersect($this->user()->allAbilities->pluck('id')->toArray(), $data['abilities']);
        } else {
            unset($data['abilities']);
        }

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->route('ability') ?? app('rinvex.fort.ability');
        $user->updateRulesUniques();

        return $user->getRules();
    }
}
