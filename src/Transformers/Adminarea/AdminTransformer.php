<?php

declare(strict_types=1);

namespace Cortex\Auth\Transformers\Adminarea;

use Cortex\Auth\Models\Admin;
use Rinvex\Support\Traits\Escaper;
use League\Fractal\TransformerAbstract;

class AdminTransformer extends TransformerAbstract
{
    use Escaper;

    /**
     * @return array
     */
    public function transform(Admin $admin): array
    {
        return $this->escape([
            'id' => (string) $admin->getRouteKey(),
            'is_active' => (bool) $admin->is_active,
            'full_name' => (string) ($admin->full_name ?? $admin->username),
            'username' => (string) $admin->username,
            'email' => (string) $admin->email,
            'phone' => (string) $admin->phone,
            'country_code' => (string) $admin->country_code ? country($admin->country_code)->getName() : null,
            'language_code' => (string) $admin->language_code ? language($admin->language_code)->getName() : null,
            'title' => (string) $admin->title,
            'birthday' => (string) $admin->birthday,
            'gender' => (string) $admin->gender,
            'last_activity' => (string) $admin->last_activity,
            'created_at' => (string) $admin->created_at,
            'updated_at' => (string) $admin->updated_at,
        ]);
    }
}
