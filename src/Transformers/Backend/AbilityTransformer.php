<?php

namespace Cortex\Fort\Transformers\Backend;

use Cortex\Fort\Models\Ability;
use League\Fractal\TransformerAbstract;

class AbilityTransformer extends TransformerAbstract
{
    /**
     * @return  array
     */
    public function transform(Ability $ability)
    {
        return [
            'id' => (int) $ability->id,
            'slug' => (string) $ability->slug,
            'name' => (string) $ability->name,
            'policy' => (string) $ability->policy,
            'created_at' => (string) $ability->created_at,
            'updated_at' => (string) $ability->updated_at,
        ];
    }
}
