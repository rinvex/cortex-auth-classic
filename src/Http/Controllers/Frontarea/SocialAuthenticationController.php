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

        $attributes = [
            'id' => $providerUser->id,
            'email' => $providerUser->email,
            'username' => $providerUser->nickname ?? trim(strstr($providerUser->email, '@', true)),
            'first_name' => trim(strstr($providerUser->name, ' ', true)),
            'last_name' => trim(strstr($providerUser->name, ' ')),
        ];

        //switch ($provider) {
        //    case 'twitter':
        //        $attributes['bio'] = $providerUser->user['description'];
        //        $attributes['profile_picture'] = $providerUser->avatar_original;
        //        break;
        //    case 'github':
        //        $attributes['bio'] = $providerUser->user['bio'];
        //        $attributes['profile_picture'] = $providerUser->avatar;
        //        break;
        //    case 'facebook':
        //        $attributes['profile_picture'] = $providerUser->avatar_original;
        //        break;
        //    case 'linkedin':
        //        $attributes['bio'] = $providerUser->headline;
        //        $attributes['profile_picture'] = $providerUser->avatar_original;
        //        break;
        //    case 'google':
        //        $attributes['bio'] = $providerUser->tagline;
        //        $attributes['profile_picture'] = $providerUser->avatar_original;
        //        break;
        //}

        //dd($providerUser, $attributes);

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
     * @param string $providerUserId
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function getLocalUser(string $provider, string $providerUserId)
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

        $attributes['password'] = str_random();
        $attributes['email_verified'] = true;
        $attributes['email_verified_at'] = now();
        $attributes['is_active'] = ! config('rinvex.fort.registration.moderated');
        $attributes['roles'] = $defaultRole ? [$defaultRole->id] : null;

        $localUser = app('rinvex.fort.user')->fill($attributes)->save();

        // Fire the register success event
        event('rinvex.fort.register.success', [$localUser]);

        $localUser->socialites()->create([
            'provider' => $provider,
            'provider_uid' => $attributes['id'],
        ]);

        return $localUser;
    }
}
