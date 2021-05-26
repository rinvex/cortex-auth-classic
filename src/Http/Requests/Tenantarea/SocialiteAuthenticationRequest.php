<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Requests\Tenantarea;

use Rinvex\Support\Traits\Escaper;
use Cortex\Foundation\Http\FormRequest;
use Cortex\Auth\Exceptions\AccountException;

class SocialiteAuthenticationRequest extends FormRequest
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
        $providers = collect(config('cortex.auth.socialite'));

        if ($providers->isEmpty()) {
            throw new AccountException(trans('cortex/auth::messages.socialite.disabled'));
        } elseif ($providers->contains($provider = $this->route('provider'))) {
            throw new AccountException(trans('cortex/auth::messages.socialite.not_supported', ['provider' => $provider]));
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
