<?php

declare(strict_types=1);

namespace Cortex\Fort\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Rinvex\Support\Traits\SeederHelper;

class SeedCommand extends Command
{
    use SeederHelper;

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
    public function handle()
    {
        $this->warn('Seed cortex/fort:');

        if ($this->ensureExistingDatabaseTables('rinvex/fort')) {
            $this->seedResources(app('rinvex.fort.ability'), realpath(__DIR__.'/../../../resources/data/abilities.json'), ['name', 'description', 'policy']);
            $this->seedResources(app('rinvex.fort.role'), realpath(__DIR__.'/../../../resources/data/roles.json'), ['name', 'description'], function () {
                // Grant abilities to roles
                app('rinvex.fort.role')->where('slug', 'operator')->first()->grantAbilities('superadmin', 'global');
            });
            $this->seedUsers();
        }
    }

    /**
     * Seed default users.
     *
     * @return void
     */
    protected function seedUsers()
    {
        $this->warn('Seeding Users:');

        $user = [
            'username' => 'Cortex',
            'email' => 'help@rinvex.com',
            'email_verified' => true,
            'is_active' => true,
        ];

        $user = tap(app('rinvex.fort.user')->firstOrNew($user)->fill([
            'email_verified_at' => Carbon::now(),
            'remember_token' => str_random(10),
            'password' => $password = str_random(),
        ]), function ($instance) {
            $instance->save();
        });

        // Assign roles to users
        $user->assignRoles('operator');

        $this->table(['Username', 'Password'], [['username' => $user['username'], 'password' => $password]]);
    }
}
