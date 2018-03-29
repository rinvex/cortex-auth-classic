<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Rinvex\Country\Country;
use Rinvex\Language\Language;
use Rinvex\Tags\Traits\Taggable;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Rinvex\Auth\Traits\HasHashables;
use Rinvex\Auth\Traits\CanVerifyEmail;
use Rinvex\Auth\Traits\CanVerifyPhone;
use Cortex\Foundation\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Illuminate\Notifications\Notifiable;
use Rinvex\Auth\Traits\CanResetPassword;
use Rinvex\Attributes\Traits\Attributable;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\Activitylog\Traits\HasActivity;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Rinvex\Auth\Traits\AuthenticatableTwoFactor;
use Rinvex\Auth\Contracts\CanVerifyEmailContract;
use Rinvex\Auth\Contracts\CanVerifyPhoneContract;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Rinvex\Auth\Contracts\CanResetPasswordContract;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Rinvex\Auth\Contracts\AuthenticatableTwoFactorContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

abstract class User extends Model implements AuthenticatableContract, AuthenticatableTwoFactorContract, AuthorizableContract, CanResetPasswordContract, CanVerifyEmailContract, CanVerifyPhoneContract, HasMedia
{
    // @TODO: Strangely, this issue happens only here!!!
    // Duplicate trait usage to fire attached events for cache
    // flush before other events in other traits specially HasActivity,
    // otherwise old cached queries used and no changelog recorded on update.
    use CacheableEloquent;
    use Taggable;
    use Auditable;
    use Notifiable;
    use HasActivity;
    use Attributable;
    use Authorizable;
    use HasHashables;
    use HasMediaTrait;
    use CanVerifyEmail;
    use CanVerifyPhone;
    use Authenticatable;
    use ValidatingTrait;
    use CanResetPassword;
    use HasRolesAndAbilities;
    use AuthenticatableTwoFactor;

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
        'full_name',
        'title',
        'country_code',
        'language_code',
        'birthday',
        'gender',
        'is_active',
        'last_activity',
        'abilities',
        'roles',
        'tags',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'username' => 'string',
        'password' => 'string',
        'two_factor' => 'json',
        'email' => 'string',
        'email_verified' => 'boolean',
        'email_verified_at' => 'datetime',
        'phone' => 'string',
        'phone_verified' => 'boolean',
        'phone_verified_at' => 'datetime',
        'full_name' => 'string',
        'title' => 'string',
        'country_code' => 'string',
        'language_code' => 'string',
        'birthday' => 'string',
        'gender' => 'string',
        'is_active' => 'boolean',
        'last_activity' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'password',
        'two_factor',
        'remember_token',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = [
        'validating',
        'validated',
    ];

    /**
     * The attributes to be encrypted before saving.
     *
     * @var array
     */
    protected $hashables = [
        'password',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Whether the model should throw a
     * ValidationException if it fails validation.
     *
     * @var bool
     */
    protected $throwValidationExceptions = true;

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
    protected static $logFillable = true;

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
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return Hashids::encode($this->getAttribute($this->getRouteKeyName()));
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        $value = Hashids::decode($value)[0];

        return $this->where($this->getRouteKeyName(), $value)->first();
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
            $abilities = collect($abilities)->filter();

            $model->abilities->pluck('id')->similar($abilities)
            || activity()
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
            $roles = collect($roles)->filter();

            $model->roles->pluck('id')->similar($roles)
            || activity()
                ->performedOn($model)
                ->withProperties(['attributes' => ['roles' => $roles], 'old' => ['roles' => $model->roles->pluck('id')->toArray()]])
                ->log('updated');

            $model->roles()->sync($roles, true);
        });
    }

    /**
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $user) {
            foreach (array_intersect($user->getHashables(), array_keys($user->getAttributes())) as $hashable) {
                if ($user->isDirty($hashable) && Hash::needsRehash($user->$hashable)) {
                    $user->$hashable = Hash::make($user->$hashable);
                }
            }
        });
    }

    /**
     * The user may have many sessions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function sessions(): MorphMany
    {
        return $this->morphMany(config('cortex.auth.models.session'), 'user');
    }

    /**
     * The user may have many socialites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function socialites(): MorphMany
    {
        return $this->morphMany(config('cortex.auth.models.socialite'), 'user');
    }

    /**
     * Route notifications for the authy channel.
     *
     * @return int|null
     */
    public function routeNotificationForAuthy(): ?int
    {
        if (! ($authyId = array_get($this->getTwoFactor(), 'phone.authy_id')) && $this->getEmailForVerification() && $this->getPhoneForVerification() && $this->getCountryForVerification()) {
            $result = app('rinvex.authy.user')->register($this->getEmailForVerification(), preg_replace('/[^0-9]/', '', $this->getPhoneForVerification()), $this->getCountryForVerification());
            $authyId = $result->get('user')['id'];

            // Prepare required variables
            $twoFactor = $this->getTwoFactor();

            // Update user account
            array_set($twoFactor, 'phone.authy_id', $authyId);

            $this->fill(['two_factor' => $twoFactor])->forceSave();
        }

        return $authyId;
    }

    /**
     * Get the user's country.
     *
     * @return \Rinvex\Country\Country
     */
    public function getCountryAttribute(): Country
    {
        return country($this->country_code);
    }

    /**
     * Get the user's language.
     *
     * @return \Rinvex\Language\Language
     */
    public function getLanguageAttribute(): Language
    {
        return language($this->language_code);
    }

    /**
     * Activate the user.
     *
     * @return $this
     */
    public function activate()
    {
        $this->update(['is_active' => true]);

        return $this;
    }

    /**
     * Deactivate the user.
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);

        return $this;
    }
}
