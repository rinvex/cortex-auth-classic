<?php

declare(strict_types=1);

namespace Cortex\Fort\Traits;

use Illuminate\Support\Facades\Schema;

trait BaseFortSeeder
{
    /**
     * Ensure existing fort tables.
     *
     * @return bool
     */
    protected function ensureExistingFortTables()
    {
        if (! $this->hasFortTables()) {
            $this->call('cortex:migrate:fort');
        }

        return true;
    }

    /**
     * Check if all required fort tables exists.
     *
     * @return bool
     */
    protected function hasFortTables()
    {
        return Schema::hasTable(config('rinvex.fort.tables.abilities'))
               && Schema::hasTable(config('rinvex.fort.tables.roles'))
               && Schema::hasTable(config('rinvex.fort.tables.users'))
               && Schema::hasTable(config('rinvex.fort.tables.ability_user'))
               && Schema::hasTable(config('rinvex.fort.tables.role_user'))
               && Schema::hasTable(config('rinvex.fort.tables.ability_role'))
               && Schema::hasTable(config('rinvex.fort.tables.socialites'));
    }
}
