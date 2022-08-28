<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Rinvex\Tenants\Traits\Tenantable;
use Cortex\Auth\Events\ManagerCreated;
use Cortex\Auth\Events\ManagerDeleted;
use Cortex\Auth\Events\ManagerUpdated;
use Cortex\Auth\Events\ManagerRestored;
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
        'email_verified_at',
        'phone',
        'phone_verified_at',
        'given_name',
        'family_name',
        'title',
        'organization',
        'country_code',
        'language_code',
        'timezone',
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
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => ManagerCreated::class,
        'updated' => ManagerUpdated::class,
        'deleted' => ManagerDeleted::class,
        'restored' => ManagerRestored::class,
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
        $this->setTable(config('cortex.auth.tables.managers'));
        $this->mergeRules([
            'username' => 'required|alpha_dash|min:3|max:64|unique:'.config('cortex.auth.models.manager').',username',
            'password' => 'sometimes|required|min:'.config('cortex.auth.password_min_chars').'|max:'.config('cortex.auth.password_max_chars'),
            'two_factor' => 'nullable|array',
            'email' => 'required|email:rfc,dns|min:3|max:128|unique:'.config('cortex.auth.models.manager').',email',
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
            'tenants' => 'nullable|array',
        ]);

        parent::__construct($attributes);
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
     * Determine if this is supermanager of the given tenant.
     *
     * @param \Illuminate\Database\Eloquent\Model $tenant
     *
     * @return bool
     */
    public function isSupermanager(Model $tenant): bool
    {
        return $this->isManager($tenant) && $this->isA('supermanager');
    }

    /**
     * Determine if this is manager of the given tenant.
     *
     * @param \Illuminate\Database\Eloquent\Model $tenant
     *
     * @return bool
     */
    public function isManager(Model $tenant): bool
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
