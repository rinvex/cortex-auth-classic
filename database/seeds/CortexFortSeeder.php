<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use Silber\Bouncer\Database\Models;

class CortexFortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bouncer::allow('superadmin')->everything();

        Bouncer::allow('admin')->to('list', Models::classname(\Silber\Bouncer\Database\Ability::class));
        Bouncer::allow('admin')->to('create', Models::classname(\Silber\Bouncer\Database\Ability::class));
        Bouncer::allow('admin')->to('update', Models::classname(\Silber\Bouncer\Database\Ability::class));
        Bouncer::allow('admin')->to('delete', Models::classname(\Silber\Bouncer\Database\Ability::class));
        Bouncer::allow('admin')->to('audit', Models::classname(\Silber\Bouncer\Database\Ability::class));
        Bouncer::allow('admin')->to('grant', Models::classname(\Silber\Bouncer\Database\Ability::class));

        Bouncer::allow('admin')->to('list', Models::classname(\Silber\Bouncer\Database\Role::class));
        Bouncer::allow('admin')->to('create', Models::classname(\Silber\Bouncer\Database\Role::class));
        Bouncer::allow('admin')->to('update', Models::classname(\Silber\Bouncer\Database\Role::class));
        Bouncer::allow('admin')->to('delete', Models::classname(\Silber\Bouncer\Database\Role::class));
        Bouncer::allow('admin')->to('audit', Models::classname(\Silber\Bouncer\Database\Role::class));
        Bouncer::allow('admin')->to('assign', Models::classname(\Silber\Bouncer\Database\Role::class));

        $userModel = config('auth.providers.'.config('auth.guards.'.config('auth.defaults.guard').'.provider').'.model');

        Bouncer::allow('admin')->to('list', $userModel);
        Bouncer::allow('admin')->to('create', $userModel);
        Bouncer::allow('admin')->to('update', $userModel);
        Bouncer::allow('admin')->to('delete', $userModel);
        Bouncer::allow('admin')->to('audit', $userModel);
    }
}
