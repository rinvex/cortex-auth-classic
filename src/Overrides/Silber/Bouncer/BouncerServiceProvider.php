<?php

declare(strict_types=1);

namespace Cortex\Auth\Overrides\Silber\Bouncer;

use Illuminate\Cache\ArrayStore;
use Illuminate\Contracts\Auth\Access\Gate;
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

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerBouncer()
    {
        $this->app->singleton(Bouncer::class, function () {
            return Bouncer::make()
                          ->withClipboard(new CachedClipboard(new ArrayStore))
                          ->withGate($this->app->make(Gate::class))
                          ->create();
        });
    }
}
