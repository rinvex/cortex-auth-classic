<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Frontend;

use Rinvex\Support\Http\Requests\FormRequest;

class UserRegistrationRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->isMethod('post') ? [
            'email' => 'required|email|max:255|unique:'.config('rinvex.fort.tables.users').',email',
            'username' => 'required|alpha_dash|max:255|unique:'.config('rinvex.fort.tables.users').',username',
            'password' => 'required|confirmed|min:'.config('rinvex.fort.passwordreset.minimum_characters'),
        ] : [];
    }
}
