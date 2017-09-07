<?php

declare(strict_types=1);

namespace Cortex\Fort\Transformers\Adminarea;

use League\Fractal\TransformerAbstract;
use Rinvex\Fort\Contracts\AbilityContract;

class AbilityTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(AbilityContract $ability)
    {
        return [
            'id' => (int) $ability->id,
            'action' => (string) $ability->action,
            'resource' => (string) $ability->resource,
            'name' => (string) $ability->name,
            'policy' => (string) $ability->policy,
            'created_at' => (string) $ability->created_at,
            'updated_at' => (string) $ability->updated_at,
        ];
    }
}
