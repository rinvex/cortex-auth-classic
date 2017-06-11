<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Frontend;

use Cortex\Fort\Models\User;
use Rinvex\Support\Http\Requests\FormRequest;
use Cortex\Foundation\Exceptions\GenericException;

class RegistrationProcessRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @throws \Cortex\Foundation\Exceptions\GenericException
     *
     * @return bool
     */
    public function authorize()
    {
        if (! config('rinvex.fort.registration.enabled')) {
            throw new GenericException(trans('cortex/fort::messages.register.disabled'));
        }

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
        $data['active'] = ! config('rinvex.fort.registration.moderated');
        $data['roles'] = [config('rinvex.fort.registration.default_role')];

        return $data;
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();
            $password = $data['password'] ?? null;

            if ($password && $password !== $data['password_confirmation']) {
                $validator->errors()->add('password', trans('validation.confirmed', ['attribute' => 'password']));
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return (new User())->getRules();
    }
}
