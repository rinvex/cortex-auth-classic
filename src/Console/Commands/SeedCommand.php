<?php

declare(strict_types=1);

namespace Cortex\Fort\Console\Commands;

use Illuminate\Console\Command;

class SeedCommand extends Command
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

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->warn($this->description);

        $this->call('db:seed', ['--class' => 'CortexFortSeeder']);

        $user = [
            'username' => 'Fort',
            'email' => 'help@rinvex.com',
            'email_verified' => true,
            'is_active' => true,
        ];

        $user = tap(app('cortex.fort.user')->firstOrNew($user)->fill([
            'email_verified_at' => now(),
            'remember_token' => str_random(10),
            'password' => $password = str_random(),
        ]), function ($instance) {
            $instance->save();
        });

        // Assign roles
        $user->assign('superadmin');

        $this->table(['Username', 'Password'], [['username' => $user['username'], 'password' => $password]]);
    }
}
