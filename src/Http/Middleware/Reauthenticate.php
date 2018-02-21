<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Middleware;

use Closure;

class Reauthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string                   $type
     * @param string                   $sessionName
     * @param int                      $timeout
     * @param bool                     $renew
     *
     * @return mixed
     */
    public function handle($request, Closure $next, string $type = 'password', string $sessionName = null, int $timeout = null, bool $renew = false)
    {
        $timeout = $timeout ?? config('cortex.auth.reauthentication.timeout');
        $sessionName = $sessionName ? 'cortex.auth.reauthentication.'.$sessionName : 'cortex.auth.reauthentication.'.$request->route()->getName();

        if (is_null(session($sessionName)) || time() - session($sessionName) >= $timeout) {
            session()->forget($sessionName);
            session()->put('cortex.auth.reauthentication.intended', $request->url());
            session()->put('cortex.auth.reauthentication.session_name', $sessionName);

            return view('cortex/auth::'.$request->route('accessarea').'.pages.reauthentication-'.$type);
        }

        ! $renew || session()->put($sessionName, time());

        return $next($request);
    }
}
