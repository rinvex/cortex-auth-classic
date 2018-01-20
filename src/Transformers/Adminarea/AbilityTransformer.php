<?php

declare(strict_types=1);

namespace Cortex\Fort\Transformers\Adminarea;

use League\Fractal\TransformerAbstract;
use Rinvex\Fort\Models\Ability;

class AbilityTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(Ability $ability): array
    {
        return [
            'id' => (int) $ability->getKey(),
            'action' => (string) $ability->action,
            'resource' => (string) $ability->resource,
            'name' => (string) $ability->name,
            'policy' => (string) $ability->policy,
            'created_at' => (string) $ability->created_at,
            'updated_at' => (string) $ability->updated_at,
        ];
    }
}
