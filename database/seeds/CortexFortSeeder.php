<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;

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

        Bouncer::allow('admin')->to('list', config('cortex.fort.models.ability'));
        Bouncer::allow('admin')->to('create', config('cortex.fort.models.ability'));
        Bouncer::allow('admin')->to('update', config('cortex.fort.models.ability'));
        Bouncer::allow('admin')->to('delete', config('cortex.fort.models.ability'));
        Bouncer::allow('admin')->to('audit', config('cortex.fort.models.ability'));
        Bouncer::allow('admin')->to('grant', config('cortex.fort.models.ability'));

        Bouncer::allow('admin')->to('list', config('cortex.fort.models.role'));
        Bouncer::allow('admin')->to('create', config('cortex.fort.models.role'));
        Bouncer::allow('admin')->to('update', config('cortex.fort.models.role'));
        Bouncer::allow('admin')->to('delete', config('cortex.fort.models.role'));
        Bouncer::allow('admin')->to('audit', config('cortex.fort.models.role'));
        Bouncer::allow('admin')->to('assign', config('cortex.fort.models.role'));

        Bouncer::allow('admin')->to('list', config('cortex.fort.models.user'));
        Bouncer::allow('admin')->to('create', config('cortex.fort.models.user'));
        Bouncer::allow('admin')->to('update', config('cortex.fort.models.user'));
        Bouncer::allow('admin')->to('delete', config('cortex.fort.models.user'));
        Bouncer::allow('admin')->to('audit', config('cortex.fort.models.user'));
    }
}
