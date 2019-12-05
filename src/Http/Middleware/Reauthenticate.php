<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Routing\ResponseFactory;

class Reauthenticate
{
    /**
     * The response factory instance.
     *
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $responseFactory;

    /**
     * The URL generator instance.
     *
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $urlGenerator;

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Routing\ResponseFactory $responseFactory
     * @param \Illuminate\Contracts\Routing\UrlGenerator    $urlGenerator
     *
     * @return void
     */
    public function __construct(ResponseFactory $responseFactory, UrlGenerator $urlGenerator)
    {
        $this->responseFactory = $responseFactory;
        $this->urlGenerator = $urlGenerator;
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
            if ($request->expectsJson()) {
                return $this->responseFactory->json([
                    'message' => trans('cortex/auth::common.password_required'),
                ], 423);
            }

            return $this->responseFactory->redirectGuest(
                $this->urlGenerator->route($request->route('accessarea').'.reauthentication.'.$type)
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
