<?php

declare(strict_types=1);

namespace Cortex\Fort\Models;

use Rinvex\Tenants\Traits\Tenantable;
use Cortex\Foundation\Traits\Auditable;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Fort\Models\User as BaseUser;
use Rinvex\Attributes\Traits\Attributable;
use Spatie\Activitylog\Traits\HasActivity;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Cortex\Fort\Notifications\PasswordResetNotification;
use Cortex\Fort\Notifications\EmailVerificationNotification;
use Cortex\Fort\Notifications\PhoneVerificationNotification;

class User extends BaseUser implements HasMedia
{
    // @TODO: Strangely, this issue happens only here!!!
    // Duplicate trait usage to fire attached events for cache
    // flush before other events in other traits specially LogsActivity,
    // otherwise old cached queries used and no changelog recorded on update.
    use CacheableEloquent;
    use Auditable;
    use Tenantable;
    use HasActivity;
    use Attributable;
    use HasMediaTrait;

    /**
     * Indicates whether to log only dirty attributes or all.
     *
     * @var bool
     */
    protected static $logOnlyDirty = true;

    /**
     * The attributes that are logged on change.
     *
     * @var array
     */
    protected static $logAttributes = [
        'username',
        'email',
        'email_verified',
        'phone',
        'phone_verified',
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
        'abilities',
        'roles',
    ];

    /**
     * The attributes that are ignored on change.
     *
     * @var array
     */
    protected static $ignoreChangedAttributes = [
        'password',
        'two_factor',
        'email_verified_at',
        'phone_verified_at',
        'last_activity',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $passwordResetNotificationClass = PasswordResetNotification::class;

    /**
     * {@inheritdoc}
     */
    protected $emailVerificationNotificationClass = EmailVerificationNotification::class;

    /**
     * {@inheritdoc}
     */
    protected $phoneVerificationNotificationClass = PhoneVerificationNotification::class;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'username';
    }

    /**
     * Register media collections.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_picture')->singleFile();
        $this->addMediaCollection('cover_photo')->singleFile();
    }

    /**
     * Attach the given abilities to the model.
     *
     * @param mixed $abilities
     *
     * @return void
     */
    public function setAbilitiesAttribute($abilities): void
    {
        static::saved(function (self $model) use ($abilities) {
            activity()
                ->performedOn($model)
                ->withProperties(['attributes' => ['abilities' => $abilities], 'old' => ['abilities' => $model->abilities->pluck('id')->toArray()]])
                ->log('updated');

            $model->abilities()->sync($abilities, true);
        });
    }

    /**
     * Attach the given roles to the model.
     *
     * @param mixed $roles
     *
     * @return void
     */
    public function setRolesAttribute($roles): void
    {
        static::saved(function (self $model) use ($roles) {
            activity()
                ->performedOn($model)
                ->withProperties(['attributes' => ['roles' => $roles], 'old' => ['roles' => $model->roles->pluck('id')->toArray()]])
                ->log('updated');

            $model->roles()->sync($roles, true);
        });
    }
}
