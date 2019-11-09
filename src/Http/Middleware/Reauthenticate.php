<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;

class Reauthenticate
{
    /**
     * The Redirector instance.
     *
     * @var \Illuminate\Routing\Redirector
     */
    protected $redirector;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Routing\Redirector  $redirector
     * @return void
     */
    public function __construct(Redirector $redirector)
    {
        $this->redirector = $redirector;
    }

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
    public function handle($request, Closure $next, string $type = 'password', int $timeout = null, bool $renew = false)
    {
        if ($this->shouldConfirmSession($request, $type, $timeout)) {
            return $this->redirector->guest(
                $this->redirector->getUrlGenerator()->route($request->route('accessarea').'.reauthentication.'.$type)
            );
        }

        ! $renew || $request->session()->put("auth.{$type}_confirmed_at", time());

        return $next($request);
    }

    /**
     * Determine if the confirmation timeout has expired.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $type
     * @param int|null                 $timeout
     *
     * @return bool
     */
    protected function shouldConfirmSession($request, string $type = 'password', int $timeout = null)
    {
        $confirmedAt = time() - $request->session()->get("auth.{$type}_confirmed_at", 0);

        return $confirmedAt > config("auth.{$type}_timeout", $timeout ?? 10800);
    }
}
