<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Tenantarea;

class PasswordResetSendRequest extends PasswordResetRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email:rfc,dns|min:3|max:128|exists:'.config('cortex.auth.tables.members').',email',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getRedirectUrl()
    {
        return $this->redirector->getUrlGenerator()->route('tenantarea.cortex.auth.account.passwordreset.request');
    }
}
