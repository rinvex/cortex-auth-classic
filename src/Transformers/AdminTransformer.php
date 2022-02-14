<?php

declare(strict_types=1);

namespace Cortex\Auth\Transformers;

use Cortex\Auth\Models\Admin;
use Rinvex\Support\Traits\Escaper;
use League\Fractal\TransformerAbstract;

class AdminTransformer extends TransformerAbstract
{
    use Escaper;

    /**
     * Transform admin model.
     *
     * @param \Cortex\Auth\Models\Admin $admin
     *
     * @throws \Exception
     *
     * @return array
     */
    public function transform(Admin $admin): array
    {
        $country = $admin->country_code ? country($admin->country_code) : null;
        $language = $admin->language_code ? language($admin->language_code) : null;

        return $this->escape([
            'id' => (string) $admin->getRouteKey(),
            'is_active' => (bool) $admin->is_active,
            'given_name' => (string) $admin->given_name,
            'family_name' => (string) $admin->family_name,
            'username' => (string) $admin->username,
            'email' => (string) $admin->email,
            'email_verified_at' => (string) $admin->email_verified_at,
            'phone' => (string) $admin->phone,
            'phone_verified_at' => (string) $admin->phone_verified_at,
            'country_code' => (string) $country?->getName(),
            'country_emoji' => (string) $country?->getEmoji(),
            'language_code' => (string) $language?->getName(),
            'title' => (string) $admin->title,
            'organization' => (string) $admin->organization,
            'birthday' => (string) $admin->birthday,
            'gender' => (string) $admin->gender,
            'social' => (array) $admin->social,
            'last_activity' => (string) $admin->last_activity,
            'created_at' => (string) $admin->created_at,
            'updated_at' => (string) $admin->updated_at,
        ]);
    }
}
