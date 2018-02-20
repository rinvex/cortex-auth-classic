<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

class CortexAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bouncer::allow('superadmin')->everything();

        Bouncer::allow('admin')->to('list', config('cortex.auth.models.ability'));
        Bouncer::allow('admin')->to('create', config('cortex.auth.models.ability'));
        Bouncer::allow('admin')->to('update', config('cortex.auth.models.ability'));
        Bouncer::allow('admin')->to('delete', config('cortex.auth.models.ability'));
        Bouncer::allow('admin')->to('audit', config('cortex.auth.models.ability'));
        Bouncer::allow('admin')->to('grant', config('cortex.auth.models.ability'));

        Bouncer::allow('admin')->to('list', config('cortex.auth.models.role'));
        Bouncer::allow('admin')->to('create', config('cortex.auth.models.role'));
        Bouncer::allow('admin')->to('update', config('cortex.auth.models.role'));
        Bouncer::allow('admin')->to('delete', config('cortex.auth.models.role'));
        Bouncer::allow('admin')->to('audit', config('cortex.auth.models.role'));
        Bouncer::allow('admin')->to('assign', config('cortex.auth.models.role'));

        Bouncer::allow('admin')->to('list', config('cortex.auth.models.admin'));
        Bouncer::allow('admin')->to('create', config('cortex.auth.models.admin'));
        Bouncer::allow('admin')->to('update', config('cortex.auth.models.admin'));
        Bouncer::allow('admin')->to('delete', config('cortex.auth.models.admin'));
        Bouncer::allow('admin')->to('audit', config('cortex.auth.models.admin'));

        Bouncer::allow('admin')->to('list', config('cortex.auth.models.member'));
        Bouncer::allow('admin')->to('create', config('cortex.auth.models.member'));
        Bouncer::allow('admin')->to('update', config('cortex.auth.models.member'));
        Bouncer::allow('admin')->to('delete', config('cortex.auth.models.member'));
        Bouncer::allow('admin')->to('audit', config('cortex.auth.models.member'));

        Bouncer::allow('admin')->to('list', config('cortex.auth.models.manager'));
        Bouncer::allow('admin')->to('create', config('cortex.auth.models.manager'));
        Bouncer::allow('admin')->to('update', config('cortex.auth.models.manager'));
        Bouncer::allow('admin')->to('delete', config('cortex.auth.models.manager'));
        Bouncer::allow('admin')->to('audit', config('cortex.auth.models.manager'));

        Bouncer::allow('admin')->to('list', config('cortex.auth.models.guardian'));
        Bouncer::allow('admin')->to('create', config('cortex.auth.models.guardian'));
        Bouncer::allow('admin')->to('update', config('cortex.auth.models.guardian'));
        Bouncer::allow('admin')->to('delete', config('cortex.auth.models.guardian'));
        Bouncer::allow('admin')->to('audit', config('cortex.auth.models.guardian'));
    }
}
