<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Cortex\Auth\Events\AdminCreated;
use Cortex\Auth\Events\AdminDeleted;
use Cortex\Auth\Events\AdminUpdated;
use Cortex\Auth\Events\AdminRestored;
use Cortex\Auth\Notifications\PhoneVerificationNotification;
use Cortex\Auth\Notifications\AdminPasswordResetNotification;
use Cortex\Auth\Notifications\AdminEmailVerificationNotification;

class Admin extends User
{
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => AdminCreated::class,
        'updated' => AdminUpdated::class,
        'deleted' => AdminDeleted::class,
        'restored' => AdminRestored::class,
    ];

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
        $this->setTable(config('cortex.auth.tables.admins'));
        $this->mergeRules([
            'username' => 'required|alpha_dash|min:3|max:64|unique:'.config('cortex.auth.models.admin').',username',
            'password' => 'sometimes|required|min:'.config('cortex.auth.password_min_chars').'|max:'.config('cortex.auth.password_max_chars'),
            'two_factor' => 'nullable|array',
            'email' => 'required|email:rfc,dns|min:3|max:128|unique:'.config('cortex.auth.models.admin').',email',
            'email_verified_at' => 'nullable|date',
            'phone' => 'nullable|phone:AUTO',
            'phone_verified_at' => 'nullable|date',
            'given_name' => 'required|string|strip_tags|max:150',
            'family_name' => 'nullable|string|strip_tags|max:150',
            'title' => 'nullable|string|strip_tags|max:150',
            'organization' => 'nullable|string|strip_tags|max:150',
            'country_code' => 'nullable|alpha|size:2|country',
            'language_code' => 'nullable|alpha|size:2|language',
            'timezone' => 'nullable|string|max:64|timezone',
            'birthday' => 'nullable|date_format:Y-m-d',
            'gender' => 'nullable|in:male,female',
            'social' => 'nullable',
            'is_active' => 'sometimes|boolean',
            'last_activity' => 'nullable|date',
            'tags' => 'nullable|array',
        ]);

        parent::__construct($attributes);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'username';
    }
}
