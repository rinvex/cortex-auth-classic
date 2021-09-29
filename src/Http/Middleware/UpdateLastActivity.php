<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UpdateLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Perform any final actions for the request lifecycle.
     *
     * @param \Illuminate\Http\Request                   $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return void
     */
    public function terminate($request, $response): void
    {
        if ($user = $request->user()) {
            // Skip timestamps update
            $user->timestamps = false;

            // Update last activity with a fresh timestamp
            $user->last_activity = $user->freshTimestamp();

            // Skip events trigger & model validation
            $user->saveQuietly();
        }
    }
}
