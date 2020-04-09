<?php

declare(strict_types=1);

namespace Cortex\Auth\Console\Commands;

use Rinvex\Auth\Console\Commands\PublishCommand as BasePublishCommand;

class PublishCommand extends BasePublishCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:publish:auth {--f|force : Overwrite any existing files.} {--r|resource=* : Specify which resources to publish.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Cortex Auth Resources.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        parent::handle();

        collect($this->option('resource'))->each(function ($resource) {
            $this->call('vendor:publish', ['--tag' => "cortex/auth::{$resource}", '--force' => $this->option('force')]);
        });

        $this->line('');
    }
}
