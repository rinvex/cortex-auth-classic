<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Middleware;

use Closure;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (auth()->guard($guard)->check()) {
            return intend([
                'url' => route($request->get('accessarea').'.home'),
                'with' => ['success' => trans('cortex/foundation::messages.already_authenticated')],
            ]);
        }

        return $next($request);
    }
}
