<?php

declare(strict_types=1);

namespace Cortex\Auth\Console\Commands;

use Cortex\Auth\Models\Admin;
use Illuminate\Console\Command;
use Cortex\Auth\Models\Sentinel;

class SeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:seed:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Cortex Auth Data.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->warn($this->description);

        $this->call('db:seed', ['--class' => 'CortexAuthSeeder']);

        // Create models
        $admin = $this->createAdmin($adminPassword = str_random());
        $sentinel = $this->createSentinel($sentinelPassword = str_random());

        // Assign roles
        $admin->assign('superadmin');

        $this->table(['Username', 'Password'], [
            ['username' => $admin['username'], 'password' => $adminPassword],
            ['username' => $sentinel['username'], 'password' => $sentinelPassword],
        ]);
    }

    /**
     * Create admin model.
     *
     * @param string $password
     *
     * @return \Cortex\Auth\Models\Admin
     */
    protected function createAdmin(string $password): Admin
    {
        $admin = [
            'is_active' => true,
            'username' => 'Admin',
            'email_verified' => true,
            'email' => 'admin@example.com',
        ];

        return tap(app('cortex.auth.admin')->firstOrNew($admin)->fill([
            'remember_token' => str_random(10),
            'email_verified_at' => now(),
            'password' => $password,
        ]), function ($instance) {
            $instance->save();
        });
    }

    /**
     * Create sentinel model.
     *
     * @param string $password
     *
     * @return \Cortex\Auth\Models\Sentinel
     */
    protected function createSentinel(string $password): Sentinel
    {
        $sentinel = [
            'is_active' => true,
            'username' => 'Sentinel',
            'email' => 'sentinel@example.com',
        ];

        return tap(app('cortex.auth.sentinel')->firstOrNew($sentinel)->fill([
            'remember_token' => str_random(10),
            'password' => $password,
        ]), function ($instance) {
            $instance->save();
        });
    }
}
