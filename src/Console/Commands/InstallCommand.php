<?php

declare(strict_types=1);

namespace Cortex\Auth\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:install:auth {--force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Cortex Auth Module.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->warn($this->description);

        $this->call('cortex:migrate:auth', ['--force' => $this->option('force')]);
        $this->call('cortex:publish:auth', ['--force' => $this->option('force')]);
        $this->call('cortex:seed:auth');
    }
}
