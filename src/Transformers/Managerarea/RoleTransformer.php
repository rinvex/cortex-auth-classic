<?php

declare(strict_types=1);

namespace Cortex\Auth\Transformers\Rolearea\Managerarea;

use Cortex\Auth\Models\Role;
use Rinvex\Support\Traits\Escaper;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    use Escaper;

    /**
     * Transform role model.
     *
     * @param \Cortex\Auth\Models\Role $role
     *
     * @throws \Exception
     *
     * @return array
     */
    public function transform(Role $role): array
    {
        return $this->escape([
            'id' => (string) $role->getRouteKey(),
            'DT_RowId' => 'row_'.$role->getRouteKey(),
            'title' => (string) $role->title,
            'name' => (string) $role->name,
            'created_at' => (string) $role->created_at,
            'updated_at' => (string) $role->updated_at,
        ]);
    }
}
