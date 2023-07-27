<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Frontarea;

class MemberRegistrationProcessRequest extends MemberRegistrationRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        $data['is_active'] = ! config('cortex.auth.registration.moderated');

        $this->replace($data);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = app('cortex.auth.member')->getRules();

        $rules['password'][] = 'confirmed';

        return $rules;
    }
}
