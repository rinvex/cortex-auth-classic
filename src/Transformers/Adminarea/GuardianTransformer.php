<?php

declare(strict_types=1);

namespace Cortex\Auth\Transformers\Adminarea;

use Cortex\Auth\Models\Guardian;
use Rinvex\Support\Traits\Escaper;
use League\Fractal\TransformerAbstract;

class GuardianTransformer extends TransformerAbstract
{
    use Escaper;

    /**
     * Transform guardian model.
     *
     * @param \Cortex\Auth\Models\Guardian $guardian
     *
     * @throws \Exception
     *
     * @return array
     */
    public function transform(Guardian $guardian): array
    {
        return $this->escape([
            'id' => (string) $guardian->getRouteKey(),
            'is_active' => (bool) $guardian->is_active,
            'username' => (string) $guardian->username,
            'email' => (string) $guardian->email,
            'created_at' => (string) $guardian->created_at,
            'updated_at' => (string) $guardian->updated_at,
        ]);
    }
}
