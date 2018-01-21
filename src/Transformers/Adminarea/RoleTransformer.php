<?php

declare(strict_types=1);

namespace Cortex\Fort\Transformers\Adminarea;

use Rinvex\Fort\Models\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(Role $role): array
    {
        return [
            'id' => (int) $role->getKey(),
            'slug' => (string) $role->slug,
            'name' => (string) $role->name,
            'created_at' => (string) $role->created_at,
            'updated_at' => (string) $role->updated_at,
        ];
    }
}
