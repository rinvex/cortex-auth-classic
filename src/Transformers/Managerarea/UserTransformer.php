<?php

declare(strict_types=1);

namespace Cortex\Fort\Transformers\Managerarea;

use League\Fractal\TransformerAbstract;
use Rinvex\Fort\Contracts\UserContract;

class UserTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(UserContract $user)
    {
        return [
            'id' => (int) $user->id,
            'username' => (string) $user->username,
            'first_name' => (string) $user->first_name,
            'middle_name' => (string) $user->middle_name,
            'last_name' => (string) $user->last_name,
            'is_active' => (bool) $user->is_active,
            'email' => (string) $user->email,
            'email_verified' => (bool) $user->email_verified,
            'email_verified_at' => (string) $user->email_verified_at,
            'phone' => (string) $user->phone,
            'phone_verified' => (bool) $user->phone_verified,
            'phone_verified_at' => (string) $user->phone_verified_at,
            'country_code' => (string) $user->country_code ? $user->country->getEmoji().'&nbsp;&nbsp;'.$user->country->getName() : '',
            'language_code' => (string) $user->language_code,
            'job_title' => (string) $user->job_title,
            'birthday' => (string) $user->birthday,
            'gender' => (string) $user->gender,
            'last_activity' => (string) $user->last_activity,
            'created_at' => (string) $user->created_at,
            'updated_at' => (string) $user->updated_at,
        ];
    }
}
