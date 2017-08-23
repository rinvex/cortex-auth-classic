<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Frontend;

use Exception;
use Illuminate\Http\Request;
use Rinvex\Fort\Contracts\UserContract;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Database\Eloquent\Builder;

class SocialAuthenticationController extends AuthenticationController
{
    /**
     * Redirect to Github for authentication.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Handle Github authentication callback.
     *
     * @param \Illuminate\Http\Request            $request
     * @param \Rinvex\Fort\Contracts\UserContract $user
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGithubCallback(Request $request, UserContract $user)
    {
        try {
            $githubUser = Socialite::driver('github')->user();
        } catch (Exception $e) {
            return intend([
                'url' => route('frontend.auth.social.github'),
            ]);
        }

        $result = app('rinvex.fort.user')->whereHas(['socialites', function (Builder $builder) use ($githubUser) {
            $builder->where('provider', 'github');
            $builder->where('provider_uid', $githubUser->id);
        }])->first();

        if (! $result) {
            // Prepare registration data
            $data = [
                'email' => $githubUser->email,
                'username' => $githubUser->username,
                'password' => str_random(),
                'is_active' => ! config('rinvex.fort.registration.moderated'),
            ];

            // Fire the register start event
            event('rinvex.fort.register.social.start', [$data]);

            // Create user
            $result = $user->create($data);

            // Attach default role to the registered user
            if ($defaultRole = config('rinvex.fort.registration.default_role')) {
                if ($defaultRole = app('rinvex.fort.role')->where('slug', $defaultRole)->first()) {
                    $result->roles()->attach($defaultRole);
                }
            }

            // Fire the register success event
            event('rinvex.fort.register.social.success', [$result]);

            $result->socialites()->create([
                'user_id' => 'github',
                'provider' => 'github',
                'provider_uid' => $githubUser->id,
            ]);
        }

        $result = auth()->guard($this->getGuard())->login($result, true);

        return $this->getLoginResponse($request, $result);
    }
}
