<?php

declare(strict_types=1);

namespace Cortex\Fort\Console\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:publish:fort';

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
        $this->warn('Publish cortex/fort:');
        $this->call('vendor:publish', ['--tag' => 'rinvex-fort-config']);
        $this->call('vendor:publish', ['--tag' => 'cortex-fort-views']);
        $this->call('vendor:publish', ['--tag' => 'cortex-fort-lang']);
    }
}
