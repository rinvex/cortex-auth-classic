<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Database\Eloquent\Builder;
use Cortex\Foundation\Http\Controllers\AbstractController;

class SocialAuthenticationController extends AbstractController
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
     * @param \Illuminate\Http\Request $request
     * @param string                   $provider
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback(Request $request, string $provider)
    {
        $providerUser = Socialite::driver($provider)->user();
        $fullName = explode(' ', $providerUser->name);

        $attributes = [
            'id' => $providerUser->id,
            'email' => $providerUser->email,
            'username' => $providerUser->nickname ?? trim(mb_strstr($providerUser->email, '@', true)),
            'given_name' => current($fullName),
            'family_name' => end($fullName),
        ];

        switch ($provider) {
            case 'twitter':
                $attributes['title'] = $providerUser->user['description'];
                $attributes['profile_picture'] = $providerUser->avatar_original;
                break;
            case 'github':
                $attributes['title'] = $providerUser->user['bio'];
                $attributes['profile_picture'] = $providerUser->avatar;
                break;
            case 'facebook':
                $attributes['profile_picture'] = $providerUser->avatar_original;
                break;
            case 'linkedin':
                $attributes['title'] = $providerUser->headline;
                $attributes['profile_picture'] = $providerUser->avatar_original;
                break;
            case 'google':
                $attributes['title'] = $providerUser->tagline;
                $attributes['profile_picture'] = $providerUser->avatar_original;
                break;
        }

        if (! ($localUser = $this->getLocalUser($provider, $providerUser->id))) {
            $localUser = $this->createLocalUser($provider, $attributes);
        }

        auth()->guard($this->getGuard())->login($localUser, true);

        return intend([
            'intended' => route('adminarea.home'),
            'with' => ['success' => trans('cortex/auth::messages.auth.login')],
        ]);
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
        return app('cortex.auth.admin')->whereHas('socialites', function (Builder $builder) use ($provider, $providerUserId) {
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
        $localUser = app('cortex.auth.admin');

        $attributes['password'] = str_random();
        $attributes['email_verified_at'] = now();
        $attributes['is_active'] = ! config('cortex.auth.registration.moderated');

        $localUser->fill($attributes)->save();

        // Fire the register success event
        event(new Registered($localUser));

        $localUser->socialites()->create([
            'provider' => $provider,
            'provider_uid' => $attributes['id'],
        ]);

        return $localUser;
    }
}
