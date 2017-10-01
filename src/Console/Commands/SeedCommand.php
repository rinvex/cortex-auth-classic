<?php

declare(strict_types=1);

namespace Cortex\Fort\Console\Commands;

use Rinvex\Fort\Console\Commands\SeedCommand as BaseSeedCommand;

class SeedCommand extends BaseSeedCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:seed:fort';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Cortex Fort Data.';
}
