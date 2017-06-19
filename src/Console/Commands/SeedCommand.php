<?php

declare(strict_types=1);

namespace Cortex\Fort\Console\Commands;

use Carbon\Carbon;
use Cortex\Fort\Models\Role;
use Cortex\Fort\Models\User;
use Cortex\Fort\Models\Ability;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class SeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed default Cortex data.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        Model::unguard();

        // WARNING: This action will delete all users/roles/abilities data (Can NOT be UNDONE!)
        if ($this->isFirstRun() || $this->confirm('WARNING! Your database already have data, this action will delete all users/roles/abilities (Can NOT be UNDONE!). Are you sure you want to continue?')) {
            Schema::disableForeignKeyConstraints();
            DB::table(config('rinvex.fort.tables.abilities'))->truncate();
            DB::table(config('rinvex.fort.tables.roles'))->truncate();
            DB::table(config('rinvex.fort.tables.users'))->truncate();
            DB::table(config('rinvex.fort.tables.ability_user'))->truncate();
            DB::table(config('rinvex.fort.tables.role_user'))->truncate();
            DB::table(config('rinvex.fort.tables.ability_role'))->truncate();
            DB::table(config('rinvex.fort.tables.email_verifications'))->truncate();
            DB::table(config('auth.passwords.'.config('auth.defaults.passwords').'.table'))->truncate();
            DB::table(config('rinvex.fort.tables.socialites'))->truncate();
            DB::table(config('session.table'))->truncate();
            Schema::enableForeignKeyConstraints();

            // Seed data
            $this->seedAbilities();
            $this->seedRoles();
            $this->seedUsers();
        }

        Model::reguard();
    }

    /**
     * Check if this is first seed run.
     *
     * @return bool
     */
    protected function isFirstRun()
    {
        $userCount = User::count();
        $roleCount = Role::count();
        $abilityCount = Ability::count();

        return ! $userCount && ! $roleCount && ! $abilityCount;
    }

    /**
     * Seed default abilities.
     *
     * @return void
     */
    protected function seedAbilities()
    {
        // Get abilities data
        $abilities = json_decode(file_get_contents(__DIR__.'/../../../resources/data/abilities.json'), true);

        // Create new abilities
        foreach ($abilities as $ability) {
            Ability::create($ability);
        }

        $this->info('Default abilities seeded successfully!');
    }

    /**
     * Seed default roles.
     *
     * @return void
     */
    protected function seedRoles()
    {
        // Get roles data
        $roles = json_decode(file_get_contents(__DIR__.'/../../../resources/data/roles.json'), true);

        // Create new roles
        foreach ($roles as $role) {
            Role::create($role);
        }

        // Grant abilities to roles
        Role::where('slug', 'operator')->first()->grantAbilities('superadmin', 'global');

        $this->info('Default roles seeded successfully!');
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
            'email_verified_at' => Carbon::now(),
            'remember_token' => str_random(10),
            'password' => $password = str_random(),
            'active' => true,
        ];

        $user = User::create($user);

        // Assign roles to users
        $user->assignRoles('operator');

        $this->info('Default users seeded successfully!');
        $this->getOutput()->writeln("<comment>Username</comment>: {$user['username']} / <comment>Password</comment>: {$password}");
    }
}
