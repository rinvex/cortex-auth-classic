<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Middleware;

use Closure;

class Reauthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Closure                  $next
     * @param string                    $type
     * @param string|null               $session_name
     * @param int|null                  $timeout
     * @param bool                      $renew
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $type='password', $session_name=null, $timeout=null, $renew=false)
    {

        $timeout = $timeout ?? config('cortex.fort.reauthentication.timeout');

        if( is_null($session_name) || empty($session_name) ) {
            $session_name = 'cortex.fort.reauthentication.'.$request->route()->getName();
        } else {
            $session_name = 'cortex.fort.reauthentication.'.$session_name;
        }

        if( is_null( session( $session_name ) ) || time() - session( $session_name ) >= $timeout ) {

            session()->forget( $session_name );
            session()->put('rinvex.fort.twofactor.totp', true);
            session()->put('cortex.fort.reauthentication.intended', $request->url());
            session()->put('cortex.fort.reauthentication.session_name', $session_name);

            return view('cortex/fort::frontarea.common.reauthentication.'.$type);
        }

        if( $renew ) {
            session()->put($session_name, time());
        }

        return $next($request);
    }
}