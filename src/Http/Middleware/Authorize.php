<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authorize as BaseAuthorize;

class Authorize extends BaseAuthorize
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string                   $ability
     * @param array|null               ...$models
     *
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $ability, ...$models)
    {
        if ($request->isApi() && $request->user()) {
            $this->gate->forUser($request->user()->token())->authorize($ability, $this->getGateArguments($request, $models));
        } else {
            $this->gate->authorize($ability, $this->getGateArguments($request, $models));
        }

        return $next($request);
    }
}
