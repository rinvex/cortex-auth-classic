<?php

declare(strict_types=1);

namespace Cortex\Auth\Transformers\Managerarea;

use Cortex\Auth\Models\Manager;
use Rinvex\Support\Traits\Escaper;
use League\Fractal\TransformerAbstract;

class ManagerTransformer extends TransformerAbstract
{
    use Escaper;

    /**
     * @return array
     */
    public function transform(Manager $manager): array
    {
        $country = $manager->country_code ? country($manager->country_code) : null;
        $language = $manager->language_code ? language($manager->language_code) : null;

        return $this->escape([
            'id' => (string) $manager->getRouteKey(),
            'is_active' => (bool) $manager->is_active,
            'given_name' => (string) $manager->given_name,
            'family_name' => (string) $manager->family_name,
            'username' => (string) $manager->username,
            'email' => (string) $manager->email,
            'phone' => (string) $manager->phone,
            'country_code' => (string) optional($country)->getName(),
            'country_emoji' => (string) optional($country)->getEmoji(),
            'language_code' => (string) optional($language)->getName(),
            'title' => (string) $manager->title,
            'organization' => (string) $manager->organization,
            'birthday' => (string) $manager->birthday,
            'gender' => (string) $manager->gender,
            'social' => (array) $manager->social,
            'last_activity' => (string) $manager->last_activity,
            'created_at' => (string) $manager->created_at,
            'updated_at' => (string) $manager->updated_at,
        ]);
    }
}
