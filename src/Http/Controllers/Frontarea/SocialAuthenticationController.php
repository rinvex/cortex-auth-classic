<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Frontarea;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Database\Eloquent\Builder;

class SocialAuthenticationController extends AuthenticationController
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param string $provider
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Provider.
     *
     * @param string $provider
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback(string $provider)
    {
        $providerUser = Socialite::driver($provider)->user();
        dd($providerUser);
        // @TODO
        switch ($provider) {
            case 'github':
                $asd = 'asd';
                break;
        }

        $attributes = [
            'id' => $providerUser->id,
            'email' => $providerUser->email,
            'username' => $providerUser->nickname,
            'last_token' => $providerUser->token,
        ];

        if (! ($localUser = $this->getLocalUser($provider, $providerUser->id))) {
            $localUser = $this->createLocalUser($provider, $attributes);
        }

        $loginResult = auth()->guard($this->getGuard())->attempt([
            'is_active' => $localUser->is_active,
            'email' => $localUser->email,
            'social' => true,
        ], true);

        return $this->getLoginResponse(request(), $loginResult);
    }

    /**
     * Get local user for the given provider.
     *
     * @param string $provider
     * @param int    $providerUserId
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function getLocalUser(string $provider, int $providerUserId)
    {
        return app('rinvex.fort.user')->whereHas('socialites', function (Builder $builder) use ($provider, $providerUserId) {
            $builder->where('provider', $provider)->where('provider_uid', $providerUserId);
        })->first();
    }

    /**
     * Create local user for the given provider.
     *
     * @param string $provider
     * @param array  $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function createLocalUser(string $provider, array $attributes)
    {
        $defaultRole = app('rinvex.fort.role')->where('slug', config('rinvex.fort.registration.default_role'))->first();

        $localUser = app('rinvex.fort.user')->fill([
            'password' => str_random(),
            'username' => $attributes['username'],
            'email' => $attributes['email'],
            'email_verified' => true,
            'email_verified_at' => now(),
            'is_active' => ! config('rinvex.fort.registration.moderated'),
            'roles' => $defaultRole ? [$defaultRole->id] : null,
        ])->save();

        // Fire the register success event
        event('rinvex.fort.register.success', [$localUser]);

        $localUser->socialites()->create([
            'provider' => $provider,
            'provider_uid' => $attributes['id'],
            'last_token' => $attributes['last_token'],
        ]);

        return $localUser;
    }
}
