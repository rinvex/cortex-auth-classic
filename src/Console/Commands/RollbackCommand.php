<?php

declare(strict_types=1);

namespace Cortex\Auth\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'cortex:rollback:auth')]
class RollbackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:rollback:auth {--f|force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback Cortex Auth Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert($this->description);

        $path = config('cortex.auth.autoload_migrations') ?
            realpath(__DIR__.'/../../../database/migrations') :
            $this->laravel->databasePath('migrations/cortex/auth');

        if (file_exists($path)) {
            $this->call('migrate:reset', [
                '--path' => $path,
                '--realpath' => true,
                '--force' => $this->option('force'),
            ]);
        } else {
            $this->warn('No migrations found! Consider publish them first: <fg=green>php artisan cortex:publish:auth</>');
        }
    }
}
