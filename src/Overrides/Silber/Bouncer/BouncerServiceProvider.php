<?php

declare(strict_types=1);

namespace Cortex\Fort\Overrides\Silber\Bouncer;

use Silber\Bouncer\BouncerServiceProvider as BaseBouncerServiceProvider;

class BouncerServiceProvider extends BaseBouncerServiceProvider
{
    /**
     * Register Bouncer's models in the relation morph map.
     *
     * @return void
     */
    protected function registerMorphs()
    {
        // Do nothing!
    }
}
