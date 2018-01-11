<?php

declare(strict_types=1);

namespace Cortex\Fort\Transformers\Managerarea;

use League\Fractal\TransformerAbstract;
use Rinvex\Fort\Contracts\RoleContract;

class RoleTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(RoleContract $role)
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
