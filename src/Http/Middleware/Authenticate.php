<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Middleware;

use Closure;

class Authenticate
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
        if (auth()->guard($guard)->guest()) {
            return intend([
                'url' => route('frontarea.login'),
                'with' => ['warning' => trans('cortex/foundation::messages.session_required')],
            ]);
        }

        return $next($request);
    }
}
