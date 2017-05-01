<?php

declare(strict_types=1);

namespace Cortex\Fort\Http\Controllers\Frontend;

use Cortex\Foundation\Http\Controllers\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Show index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cortex/fort::frontend.pages.home');
    }
}
