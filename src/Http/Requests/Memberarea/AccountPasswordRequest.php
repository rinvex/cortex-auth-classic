<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Memberarea;

use Rinvex\Support\Http\Requests\FormRequest;

class AccountPasswordRequest extends FormRequest
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
        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['password'] = 'sometimes|required|confirmed|min:'.config('rinvex.fort.password_min_chars');
        return $rules;
    }
}
