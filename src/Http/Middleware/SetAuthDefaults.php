<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class SetAuthDefaults
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($guard = $request->guard()) {
            // It's better to set auth defaults config globally,
            // instead of using `auth()->shouldUse($guard);`
            config()->set('auth.defaults.guard', $guard);
            config()->set('auth.defaults.apiguard', $guard);
            config()->set('auth.defaults.provider', Str::afterLast(Str::plural($guard), ':'));
            config()->set('auth.defaults.passwords', $guard);
            config()->set('auth.defaults.emails', $guard);
        }

        return $next($request);
    }
}
