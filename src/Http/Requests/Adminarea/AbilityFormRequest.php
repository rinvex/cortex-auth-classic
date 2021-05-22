<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Adminarea;

use Rinvex\Support\Traits\Escaper;
use Cortex\Foundation\Http\FormRequest;
use Cortex\Auth\Exceptions\AccountException;

class AbilityFormRequest extends FormRequest
{
    use Escaper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Cortex\Auth\Exceptions\AccountException
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (! $this->user()->isA('superadmin') && ! $this->user()->getAbilities()->contains($this->route('ability'))) {
            throw new AccountException(trans('cortex/auth::messages.unauthorized'), route('adminarea.cortex.auth.abilities.index'), null, 403);
        }

        return true;
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
