<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Adminarea;

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
            'url' => route('adminarea.cortex.auth.account.passwordreset.request'),
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
            'url' => route('adminarea.home'),
        ]);
    }
}
