<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

use Rinvex\Support\Traits\Escaper;
use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\Exceptions\GenericException;

class AbilityFormRequest extends FormRequest
{
    use Escaper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Cortex\Foundation\Exceptions\GenericException
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $currentUser = $this->user($this->route('guard'));

        if (optional($this->route('ability'))->exists && ! $currentUser->can('superadmin') && ! $currentUser->getAbilities()->contains($this->route('ability'))) {
            throw new GenericException(trans('cortex/auth::messages.action_unauthorized'), route('adminarea.abilities.index'));
        }

        return true;
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
        return [];
    }
}
