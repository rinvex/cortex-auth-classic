<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Requests\Tenantarea;

use Illuminate\Foundation\Http\FormRequest;
use Rinvex\Fort\Exceptions\GenericException;

class EmailVerificationRequest extends FormRequest
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
        $userVerified = $this->user() && $this->user()->email_verified;
        $guestVerified = empty($userVerified) && ($email = $this->get('email')) && ($user = app('rinvex.fort.user')->where('email', $email)->first()) && $user->email_verified;

        if ($userVerified || $guestVerified) {
            // Redirect users if their email already verified, no need to process their request
            throw new GenericException(trans('cortex/fort::messages.verification.email.already_verified'), $userVerified ? route('tenantarea.account.settings') : route('tenantarea.login'));
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
