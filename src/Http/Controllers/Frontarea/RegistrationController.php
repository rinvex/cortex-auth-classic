<?php

declare(strict_types=1);

namespace Cortex\Auth\Http\Controllers\Frontarea;

use Cortex\Foundation\Http\Controllers\AbstractController;

class RegistrationController extends AbstractController
{
    /**
     * Create a new registration controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware(($guard = app('request.guard')) ? 'guest:'.$guard : 'guest')->except($this->middlewareWhitelist);
    }
}
