<?php

declare(strict_types=1);

namespace Cortex\Fort\Console\Commands;

use Rinvex\Fort\Console\Commands\PublishCommand as BasePublishCommand;

class PublishCommand extends BasePublishCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:publish:fort {--force : Overwrite any existing files.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Cortex Fort Resources.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        $this->call('vendor:publish', ['--tag' => 'cortex-fort-lang', '--force' => $this->option('force')]);
        $this->call('vendor:publish', ['--tag' => 'cortex-fort-views', '--force' => $this->option('force')]);
        $this->call('vendor:publish', ['--tag' => 'cortex-fort-config', '--force' => $this->option('force')]);
    }
}
