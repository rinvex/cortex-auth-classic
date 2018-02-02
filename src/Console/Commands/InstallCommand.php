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
    protected $signature = 'cortex:install:fort {--force : Force the operation to run when in production.}';

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
    public function handle(): void
    {
        $this->warn($this->description);

        $this->call('cortex:migrate:fort', ['--force' => $this->option('force')]);
        $this->call('cortex:seed:fort');
        $this->call('cortex:publish:fort', ['--force' => $this->option('force')]);
    }
}
