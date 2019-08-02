<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Frontarea;

use Cortex\Foundation\Http\Controllers\AbstractController;

class RedirectionController extends AbstractController
{
    /**
     * Redirect to passwordreset.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function passwordreset()
    {
        return intend([
            'url' => route('frontarea.passwordreset.request'),
        ]);
    }

    /**
     * Redirect to member registration.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function registration()
    {
        return intend([
            'url' => route('frontarea.register.member'),
        ]);
    }

    /**
     * Redirect to homepage.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function verification()
    {
        return intend([
            'url' => route('frontarea.home'),
        ]);
    }
}
