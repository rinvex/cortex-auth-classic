<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

use Rinvex\Support\Traits\Escaper;
use Illuminate\Foundation\Http\FormRequest;

class GuardianFormRequest extends FormRequest
{
    use Escaper;

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

        $guardian = $this->route('guardian') ?? app('cortex.auth.guardian');

        if ($guardian->exists && empty($data['password'])) {
            unset($data['password'], $data['password_confirmation']);
        }

        $this->replace($data);
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator): void
    {
        // Sanitize input data before submission
        $this->replace($this->escape($this->all()));
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
