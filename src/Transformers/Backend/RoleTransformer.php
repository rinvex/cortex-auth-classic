<?php

declare(strict_types=1);

namespace Cortex\Fort\Transformers\Backend;

use Cortex\Fort\Models\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(Role $role)
    {
        return [
            'id' => (int) $role->id,
            'slug' => (string) $role->slug,
            'name' => (string) $role->name,
            'created_at' => (string) $role->created_at,
            'updated_at' => (string) $role->updated_at,
        ];
    }
}
