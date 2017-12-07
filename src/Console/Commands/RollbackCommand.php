<?php

declare(strict_types=1);

namespace Cortex\Fort\Console\Commands;

use Rinvex\Fort\Console\Commands\RollbackCommand as BaseRollbackCommand;

class RollbackCommand extends BaseRollbackCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:rollback:fort';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback Cortex Fort Tables.';
}
