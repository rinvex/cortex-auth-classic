<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Managerarea;

use Rinvex\Support\Traits\Escaper;
use Illuminate\Foundation\Http\FormRequest;

class AccountAttributesRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $user = $this->user(app('request.guard'));

        // Attach attribute rules
        $user->getEntityAttributes()->each(function ($attribute, $attributeName) use (&$rules) {
            switch ($attribute->type) {
                case 'datetime':
                    $type = 'date';
                    break;
                case 'text':
                case 'check':
                case 'select':
                case 'varchar':
                    $type = 'string';
                    break;
                default:
                    $type = $attribute->type;
                    break;
            }

            $rule = ($attribute->is_required ? 'required|' : 'nullable|').$type;
            $rules[$attributeName.($attribute->is_collection ? '.*' : '')] = $rule;
        });

        return $rules ?? [];
    }
}
