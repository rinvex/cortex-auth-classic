<?php

declare(strict_types=1);

namespace Cortex\Auth\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Cortex\Auth\Models\Admin;
use Illuminate\Console\Command;
use Cortex\Auth\Models\Guardian;

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
        $this->alert($this->description);

        $this->call('db:seed', ['--class' => 'CortexAuthSeeder']);

        // Create models
        $admin = $this->createAdmin($adminPassword = Str::random());
        $guardian = $this->createGuardian($guardianPassword = Str::random());

        // Assign roles
        $admin->assign('superadmin');

        $this->table(['Username', 'Password'], [
            ['username' => $admin['username'], 'password' => $adminPassword],
            ['username' => $guardian['username'], 'password' => $guardianPassword],
        ]);

        $this->line('');
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
            'given_name' => 'Admin',
            'family_name' => 'User',
            'email' => 'admin@example.com',
        ];

        return tap(app('cortex.auth.admin')->firstOrNew($admin)->fill([
            'remember_token' => Str::random(10),
            'email_verified_at' => Carbon::now(),
            'password' => $password,
        ]), function ($instance) {
            $instance->save();
        });
    }

    /**
     * Create guardian model.
     *
     * @param string $password
     *
     * @return \Cortex\Auth\Models\Guardian
     */
    protected function createGuardian(string $password): Guardian
    {
        $guardian = [
            'is_active' => true,
            'username' => 'Guardian',
            'email' => 'guardian@example.com',
        ];

        return tap(app('cortex.auth.guardian')->firstOrNew($guardian)->fill([
            'remember_token' => Str::random(10),
            'password' => $password,
        ]), function ($instance) {
            $instance->save();
        });
    }
}
