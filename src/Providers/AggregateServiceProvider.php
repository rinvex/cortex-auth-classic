<?php

declare(strict_types=1);

namespace Cortex\Fort\Providers;

use Illuminate\Support\AggregateServiceProvider as BaseAggregateServiceProvider;

class AggregateServiceProvider extends BaseAggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var array
     */
    protected $providers = [
        FortServiceProvider::class,
    ];
}
