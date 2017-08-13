<?php

declare(strict_types=1);

namespace Cortex\Fort\Console\Commands;

use Rinvex\Fort\Console\Commands\MigrateCommand as BaseMigrateCommand;

class MigrateCommand extends BaseMigrateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:migrate:fort';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Cortex Fort Tables.';
}
