<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

use Illuminate\Foundation\Http\FormRequest;

class GuardianFormRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $guardian = $this->route('guardian') ?? app('cortex.auth.guardian');
        $guardian->updateRulesUniques();
        $rules = $guardian->getRules();

        $rules['password'] = $guardian->exists
            ? 'confirmed|min:'.config('cortex.auth.password_min_chars')
            : 'required|confirmed|min:'.config('cortex.auth.password_min_chars');

        return $rules;
    }
}
