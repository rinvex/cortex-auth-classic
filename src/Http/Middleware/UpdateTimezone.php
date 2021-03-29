<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Middleware;

use Closure;

class UpdateTimezone
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
        if (! $request->ajax() && $user = $request->user()) {
            $timezone = geoip($request->getClientIp())->timezone;

            if (! $user->timezone) {
                app("cortex.auth.{$user->getMorphClass()}")->where('id', $user->getKey())->update(['timezone' => $timezone]);
                $request->session()->flash('success', trans('cortex/auth::messages.account.updated_timezone', ['timezone' => $timezone]));
            }
        }

        return $next($request);
    }
}
