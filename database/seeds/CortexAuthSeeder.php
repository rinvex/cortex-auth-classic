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

        $abilities = [
            ['name' => 'list', 'title' => 'List abilities', 'entity_type' => 'ability'],
            ['name' => 'import', 'title' => 'Import abilities', 'entity_type' => 'ability'],
            ['name' => 'create', 'title' => 'Create abilities', 'entity_type' => 'ability'],
            ['name' => 'update', 'title' => 'Update abilities', 'entity_type' => 'ability'],
            ['name' => 'delete', 'title' => 'Delete abilities', 'entity_type' => 'ability'],
            ['name' => 'audit', 'title' => 'Audit abilities', 'entity_type' => 'ability'],
            ['name' => 'grant', 'title' => 'Grant abilities', 'entity_type' => 'ability'],

            ['name' => 'list', 'title' => 'List roles', 'entity_type' => 'role'],
            ['name' => 'import', 'title' => 'Import roles', 'entity_type' => 'role'],
            ['name' => 'create', 'title' => 'Create roles', 'entity_type' => 'role'],
            ['name' => 'update', 'title' => 'Update roles', 'entity_type' => 'role'],
            ['name' => 'delete', 'title' => 'Delete roles', 'entity_type' => 'role'],
            ['name' => 'audit', 'title' => 'Audit roles', 'entity_type' => 'role'],
            ['name' => 'assign', 'title' => 'Assign roles', 'entity_type' => 'role'],

            ['name' => 'list', 'title' => 'List admins', 'entity_type' => 'admin'],
            ['name' => 'import', 'title' => 'Import admins', 'entity_type' => 'admin'],
            ['name' => 'create', 'title' => 'Create admins', 'entity_type' => 'admin'],
            ['name' => 'update', 'title' => 'Update admins', 'entity_type' => 'admin'],
            ['name' => 'delete', 'title' => 'Delete admins', 'entity_type' => 'admin'],
            ['name' => 'audit', 'title' => 'Audit admins', 'entity_type' => 'admin'],

            ['name' => 'list', 'title' => 'List members', 'entity_type' => 'member'],
            ['name' => 'import', 'title' => 'Import members', 'entity_type' => 'member'],
            ['name' => 'create', 'title' => 'Create members', 'entity_type' => 'member'],
            ['name' => 'update', 'title' => 'Update members', 'entity_type' => 'member'],
            ['name' => 'delete', 'title' => 'Delete members', 'entity_type' => 'member'],
            ['name' => 'audit', 'title' => 'Audit members', 'entity_type' => 'member'],

            ['name' => 'list', 'title' => 'List managers', 'entity_type' => 'manager'],
            ['name' => 'import', 'title' => 'Import managers', 'entity_type' => 'manager'],
            ['name' => 'create', 'title' => 'Create managers', 'entity_type' => 'manager'],
            ['name' => 'update', 'title' => 'Update managers', 'entity_type' => 'manager'],
            ['name' => 'delete', 'title' => 'Delete managers', 'entity_type' => 'manager'],
            ['name' => 'audit', 'title' => 'Audit managers', 'entity_type' => 'manager'],

            ['name' => 'list', 'title' => 'List guardians', 'entity_type' => 'guardian'],
            ['name' => 'import', 'title' => 'Import guardians', 'entity_type' => 'guardian'],
            ['name' => 'create', 'title' => 'Create guardians', 'entity_type' => 'guardian'],
            ['name' => 'update', 'title' => 'Update guardians', 'entity_type' => 'guardian'],
            ['name' => 'delete', 'title' => 'Delete guardians', 'entity_type' => 'guardian'],
            ['name' => 'audit', 'title' => 'Audit guardians', 'entity_type' => 'guardian'],
        ];

        collect($abilities)->each(function (array $ability) {
            app('cortex.auth.ability')->create($ability);
        });
    }
}
