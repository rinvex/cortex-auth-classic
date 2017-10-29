<?php

declare(strict_types=1);

namespace Cortex\Fort\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:install:fort';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Cortex Fort Module.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->warn($this->description);
        $this->call('cortex:migrate:fort');
        $this->call('cortex:seed:fort');
        $this->call('cortex:publish:fort');
    }
}
