<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Managerarea;

use Rinvex\Support\Traits\Escaper;
use Cortex\Foundation\Http\FormRequest;
use Cortex\Foundation\Exceptions\GenericException;

class RoleFormRequest extends FormRequest
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
        if (! $this->user()->isA('supermanager') && ! $this->user()->roles->contains($this->route('role'))) {
            throw new GenericException(trans('cortex/auth::messages.unauthorized'), route('managerarea.cortex.auth.roles.index'), null, 403);
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
