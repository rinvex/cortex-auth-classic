<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Frontarea;

use Illuminate\Foundation\Http\FormRequest;
use Rinvex\Fort\Exceptions\GenericException;

class AccountPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Rinvex\Fort\Exceptions\GenericException
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (! auth()->guard()->getProvider()->validateCredentials($this->user(), ['password' => $this->get('old_password')])) {
            throw new GenericException(trans('cortex/fort::messages.account.wrong_password'), route('frontarea.account.password'));
        }

        if (auth()->guard()->getProvider()->validateCredentials($this->user(), ['password' => $this->get('new_password')])) {
            throw new GenericException(trans('cortex/fort::messages.account.different_password'), route('frontarea.account.password'));
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
        return [
            'old_password' => 'required|min:'.config('rinvex.fort.password_min_chars'),
            'new_password' => 'required|confirmed|min:'.config('rinvex.fort.password_min_chars'),
        ];
    }
}
