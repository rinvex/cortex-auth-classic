<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Rinvex\Tenants\Traits\Tenantable;
use Cortex\Auth\Notifications\PhoneVerificationNotification;
use Cortex\Auth\Notifications\ManagerPasswordResetNotification;
use Cortex\Auth\Notifications\ManagerEmailVerificationNotification;

class Manager extends User
{
    use Tenantable;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'username',
        'password',
        'two_factor',
        'email',
        'email_verified',
        'email_verified_at',
        'phone',
        'phone_verified',
        'phone_verified_at',
        'name_prefix',
        'first_name',
        'middle_name',
        'last_name',
        'name_suffix',
        'title',
        'country_code',
        'language_code',
        'birthday',
        'gender',
        'is_active',
        'last_activity',
        'abilities',
        'roles',
        'tenants',
    ];

    /**
     * {@inheritdoc}
     */
    protected $passwordResetNotificationClass = ManagerPasswordResetNotification::class;

    /**
     * {@inheritdoc}
     */
    protected $emailVerificationNotificationClass = ManagerEmailVerificationNotification::class;

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

        $this->setTable(config('cortex.auth.tables.managers'));
        $this->setRules([
            'username' => 'required|alpha_dash|min:3|max:150|unique:'.config('cortex.auth.tables.managers').',username',
            'password' => 'sometimes|required|min:'.config('cortex.auth.password_min_chars'),
            'two_factor' => 'nullable|array',
            'email' => 'required|email|min:3|max:150|unique:'.config('cortex.auth.tables.managers').',email',
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

    /**
     * Attach the given tenants to the model.
     *
     * @param mixed $tenants
     *
     * @return void
     */
    public function setTenantsAttribute($tenants): void
    {
        static::saved(function (self $model) use ($tenants) {
            $tenants === $model->tenants->pluck('id')->toArray()
            || activity()
                ->performedOn($model)
                ->withProperties(['attributes' => ['tenants' => $tenants], 'old' => ['tenants' => $model->tenants->pluck('id')->toArray()]])
                ->log('updated');

            $model->syncTenants($tenants);
        });
    }
}
