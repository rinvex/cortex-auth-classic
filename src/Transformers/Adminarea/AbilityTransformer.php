<?php

declare(strict_types=1);

namespace Cortex\Auth\Transformers\Adminarea;

use Cortex\Auth\Models\Ability;
use League\Fractal\TransformerAbstract;

class AbilityTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(Ability $ability): array
    {
        return [
            'id' => (string) $ability->getRouteKey(),
            'title' => (string) $ability->title,
            'name' => (string) $ability->name,
            'created_at' => (string) $ability->created_at,
            'updated_at' => (string) $ability->updated_at,
        ];
    }
}
