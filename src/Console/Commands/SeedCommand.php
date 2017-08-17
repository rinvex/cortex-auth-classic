<?php

declare(strict_types=1);

namespace Cortex\Fort\Console\Commands;

use Exception;
use Carbon\Carbon;
use Cortex\Fort\Models\Role;
use Cortex\Fort\Models\User;
use Illuminate\Console\Command;
use Rinvex\Fort\Traits\AbilitySeeder;
use Rinvex\Fort\Traits\ArtisanHelper;

class SeedCommand extends Command
{
    use AbilitySeeder;
    use ArtisanHelper;

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
    protected $description = 'Seed Default Cortex Fort data.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->ensureExistingFortTables()) {
            $this->seedAbilities(realpath(__DIR__.'/../../../resources/data/abilities.json'));
            $this->seedRoles();
            $this->seedUsers();
        }
    }

    /**
     * Seed default roles.
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function seedRoles()
    {
        if (! file_exists($seeder = realpath(__DIR__.'/../../../resources/data/roles.json'))) {
            throw new Exception("Abilities seeder file '{$seeder}' does NOT exist!");
        }

        // Get roles data
        $roles = json_decode(file_get_contents($seeder), true);

        // Create new roles
        foreach ($roles as $role) {
            Role::firstOrCreate(array_except($role, ['name', 'description']), array_only($role, ['name', 'description']));
        }

        // Grant abilities to roles
        Role::where('slug', 'operator')->first()->grantAbilities('superadmin', 'global');

        $this->info("Roles seeder file '{$seeder}' seeded successfully!");
    }

    /**
     * Seed default users.
     *
     * @return void
     */
    protected function seedUsers()
    {
        $user = [
            'username' => 'Cortex',
            'email' => 'help@rinvex.com',
            'email_verified' => true,
            'is_active' => true,
        ];

        $user = tap(User::firstOrNew($user)->fill([
            'email_verified_at' => Carbon::now(),
            'remember_token' => str_random(10),
            'password' => $password = str_random(),
        ]), function ($instance) {
            $instance->save();
        });

        // Assign roles to users
        $user->assignRoles('operator');

        $this->info('Default users seeded successfully!');
        $this->getOutput()->writeln("<comment>Username</comment>: {$user['username']} / <comment>Password</comment>: {$password}");
    }
}
