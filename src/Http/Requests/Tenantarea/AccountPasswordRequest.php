<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Tenantarea;

use Illuminate\Foundation\Http\FormRequest;
use Cortex\Foundation\Exceptions\GenericException;

class AccountPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Cortex\Foundation\Exceptions\GenericException
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (! auth()->guard()->getProvider()->validateCredentials($this->user(), ['password' => $this->get('old_password')])) {
            throw new GenericException(trans('cortex/fort::messages.account.wrong_password'), route('tenantarea.account.password'));
        }

        if (auth()->guard()->getProvider()->validateCredentials($this->user(), ['password' => $this->get('new_password')])) {
            throw new GenericException(trans('cortex/fort::messages.account.different_password'), route('tenantarea.account.password'));
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
            'old_password' => 'required|min:'.config('cortex.fort.password_min_chars'),
            'new_password' => 'required|confirmed|min:'.config('cortex.fort.password_min_chars'),
        ];
    }
}
