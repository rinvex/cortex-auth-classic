<?php

declare(strict_types=1);

namespace Cortex\Auth\Transformers\Adminarea;

use Cortex\Auth\Models\Ability;
use Rinvex\Support\Traits\Escaper;
use League\Fractal\TransformerAbstract;

class AbilityTransformer extends TransformerAbstract
{
    use Escaper;

    /**
     * Transform ability model.
     *
     * @param \Cortex\Auth\Models\Ability $ability
     *
     * @throws \Exception
     *
     * @return array
     */
    public function transform(Ability $ability): array
    {
        return $this->escape([
            'id' => (string) $ability->getRouteKey(),
            'DT_RowId' => 'row_'.$ability->getRouteKey(),
            'title' => (string) $ability->title,
            'name' => (string) $ability->name,
            'created_at' => (string) $ability->created_at,
            'updated_at' => (string) $ability->updated_at,
        ]);
    }
}
