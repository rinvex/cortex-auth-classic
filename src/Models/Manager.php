<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Rinvex\Tenants\Traits\Tenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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
        'given_name',
        'family_name',
        'title',
        'organization',
        'country_code',
        'language_code',
        'birthday',
        'gender',
        'social',
        'is_active',
        'last_activity',
        'abilities',
        'roles',
        'tags',
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
            'phone' => 'nullable|phone:AUTO',
            'phone_verified' => 'sometimes|boolean',
            'phone_verified_at' => 'nullable|date',
            'given_name' => 'required|string|max:150',
            'family_name' => 'nullable|string|max:150',
            'title' => 'nullable|string|max:150',
            'organization' => 'nullable|string|max:150',
            'country_code' => 'nullable|alpha|size:2|country',
            'language_code' => 'nullable|alpha|size:2|language',
            'birthday' => 'nullable|date_format:Y-m-d',
            'gender' => 'nullable|in:male,female',
            'social' => 'nullable|array',
            'is_active' => 'sometimes|boolean',
            'last_activity' => 'nullable|date',
            'tags' => 'nullable|array',
            'tenants' => 'nullable|array',
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
            $tenants = collect($tenants)->filter();

            $model->tenants->pluck('id')->similar($tenants)
            || activity()
                ->performedOn($model)
                ->withProperties(['attributes' => ['tenants' => $tenants], 'old' => ['tenants' => $model->tenants->pluck('id')->toArray()]])
                ->log('updated');

            $model->syncTenants($tenants);
        });
    }

    /**
     * Get all attached tenants to the manager.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tenants(): MorphToMany
    {
        return $this->morphToMany(config('rinvex.tenants.models.tenant'), 'tenantable', config('rinvex.tenants.tables.tenantables'), 'tenantable_id', 'tenant_id')
                    ->withTimestamps();
    }

    /**
     * Determine if manager is owner of the given tenant.
     *
     * @param \Illuminate\Database\Eloquent\Model $tenant
     *
     * @return bool
     */
    public function isOwner(Model $tenant): bool
    {
        return $this->getKey() === $tenant->owner->getKey();
    }

    /**
     * Determine if manager is staff of the given tenant.
     *
     * @param \Illuminate\Database\Eloquent\Model $tenant
     *
     * @return bool
     */
    public function isStaff(Model $tenant): bool
    {
        return $this->tenants->contains($tenant);
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
