<?php

declare(strict_types=1);

namespace Cortex\Auth\Transformers\Rolearea\Adminarea;

use Cortex\Auth\Models\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(Role $role): array
    {
        return [
            'id' => (string) $role->getRouteKey(),
            'title' => (string) $role->title,
            'name' => (string) $role->name,
            'created_at' => (string) $role->created_at,
            'updated_at' => (string) $role->updated_at,
        ];
    }
}
