<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Frontarea;

class RegistrationProcessRequest extends RegistrationRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        $data['is_active'] = ! config('cortex.fort.registration.moderated');

        $this->replace($data);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = app('cortex.fort.user')->getRules();
        $rules['password'] = 'required|confirmed|min:'.config('cortex.fort.password_min_chars');
        $rules['roles'] = 'nullable|array';

        return $rules;
    }
}
