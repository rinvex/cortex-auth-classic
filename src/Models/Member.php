<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Cortex\Auth\Events\MemberCreated;
use Cortex\Auth\Events\MemberDeleted;
use Cortex\Auth\Events\MemberUpdated;
use Cortex\Auth\Events\MemberRestored;
use Cortex\Auth\Notifications\PhoneVerificationNotification;
use Cortex\Auth\Notifications\MemberPasswordResetNotification;
use Cortex\Auth\Notifications\MemberEmailVerificationNotification;

class Member extends User
{
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => MemberCreated::class,
        'updated' => MemberUpdated::class,
        'deleted' => MemberDeleted::class,
        'restored' => MemberRestored::class,
    ];

    /**
     * {@inheritdoc}
     */
    protected $passwordResetNotificationClass = MemberPasswordResetNotification::class;

    /**
     * {@inheritdoc}
     */
    protected $emailVerificationNotificationClass = MemberEmailVerificationNotification::class;

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
        $this->setTable(config('cortex.auth.tables.members'));
        $this->mergeRules([
            'username' => ['required', ...config('validation.rules.username'), 'unique:'.config('cortex.auth.models.member').',username'],
            'password' => ['sometimes', 'required', config('validation.rules.password')],
            'two_factor' => 'nullable|array',
            'email' => ['required', ...config('validation.rules.email'), 'unique:'.config('cortex.auth.models.member').',email'],
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
            'birthday' => 'nullable|date_format:Y-m-d|before:today',
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
