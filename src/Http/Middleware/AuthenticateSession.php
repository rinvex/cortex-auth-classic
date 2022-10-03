<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\Middleware\AuthenticateSession as BaseAuthenticateSession;

class AuthenticateSession extends BaseAuthenticateSession
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws \Illuminate\Auth\AuthenticationException
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $passwordHashKey = 'password_hash';

        if (! $request->hasSession() || ! $request->user()) {
            return $next($request);
        }

        if ($this->auth->viaRemember()) {
            $passwordHash = explode('|', $request->cookies->get($this->auth->getRecallerName()))[2] ?? null;

            if (! $passwordHash || $passwordHash !== $request->user()->getAuthPassword()) {
                $this->logout($request);
            }
        }

        if (! $request->session()->has($passwordHashKey)) {
            $this->storePasswordHashInSessionPerGuard($request, $passwordHashKey);
        }

        if ($request->session()->get($passwordHashKey) !== $request->user()->getAuthPassword()) {
            $this->logout($request);
        }

        return tap($next($request), function () use ($request, $passwordHashKey) {
            if (! is_null($this->auth->user())) {
                $this->storePasswordHashInSessionPerGuard($request, $passwordHashKey);
            }
        });
    }

    /**
     * Store the user's current password hash in the session.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $passwordHashKey
     *
     * @return void
     */
    protected function storePasswordHashInSessionPerGuard($request, $passwordHashKey)
    {
        if (! $request->user()) {
            return;
        }

        $request->session()->put([
            $passwordHashKey => $request->user()->getAuthPassword(),
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Illuminate\Auth\AuthenticationException
     *
     * @return void
     */
    protected function logout($request)
    {
        $this->auth->logoutCurrentGuard();

        throw new AuthenticationException('Unauthenticated.', [$this->auth->getDefaultDriver()]);
    }
}
