<?php

declare(strict_types=1);

namespace Cortex\Fort\Models;

use Cortex\Fort\Notifications\PhoneVerificationNotification;
use Cortex\Fort\Notifications\AdminPasswordResetNotification;
use Cortex\Fort\Notifications\AdminEmailVerificationNotification;

class Admin extends User
{
    /**
     * {@inheritdoc}
     */
    protected $passwordResetNotificationClass = AdminPasswordResetNotification::class;

    /**
     * {@inheritdoc}
     */
    protected $emailVerificationNotificationClass = AdminEmailVerificationNotification::class;

    /**
     * {@inheritdoc}
     */
    protected $phoneVerificationNotificationClass = PhoneVerificationNotification::class;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('cortex.fort.tables.admins'));
        $this->setRules([
            'username' => 'required|alpha_dash|min:3|max:150|unique:'.config('cortex.fort.tables.admins').',username',
            'password' => 'sometimes|required|min:'.config('cortex.fort.password_min_chars'),
            'two_factor' => 'nullable|array',
            'email' => 'required|email|min:3|max:150|unique:'.config('cortex.fort.tables.admins').',email',
            'email_verified' => 'sometimes|boolean',
            'email_verified_at' => 'nullable|date',
            'phone' => 'nullable|numeric|min:4',
            'phone_verified' => 'sometimes|boolean',
            'phone_verified_at' => 'nullable|date',
            'name_prefix' => 'nullable|string|max:150',
            'first_name' => 'nullable|string|max:150',
            'middle_name' => 'nullable|string|max:150',
            'last_name' => 'nullable|string|max:150',
            'name_suffix' => 'nullable|string|max:150',
            'title' => 'nullable|string|max:150',
            'country_code' => 'nullable|alpha|size:2|country',
            'language_code' => 'nullable|alpha|size:2|language',
            'birthday' => 'nullable|date_format:Y-m-d',
            'gender' => 'nullable|string|in:male,female',
            'is_active' => 'sometimes|boolean',
            'last_activity' => 'nullable|date',
        ]);
    }
}
