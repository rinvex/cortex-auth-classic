<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Middleware;

use Closure;

class Reauthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string                   $type
     * @param int                      $timeout
     * @param bool                     $renew
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $type = 'password', $timeout = null, $renew = false)
    {
        $timeout = $timeout ?? config('cortex.fort.reauthentication.timeout');
        $session_name = 'cortex.fort.reauthentication.'.$request->route()->getName();
       
        if(is_null(session($session_name)) || time() - session($session_name) >= $timeout) {
            session()->forget($session_name);
            session()->put('cortex.fort.reauthentication.intended', $request->url());
            session()->put('cortex.fort.reauthentication.session_name', $session_name);

            return view('cortex/fort::frontarea.common.reauthentication.'.$type);
        }

        ! $renew || session()->put($session_name, time());

        return $next($request);
    }
}
