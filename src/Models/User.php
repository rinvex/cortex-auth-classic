<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Illuminate\Support\Arr;
use Rinvex\Country\Country;
use Rinvex\Language\Language;
use Rinvex\Tags\Traits\Taggable;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Collection;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Rinvex\Auth\Traits\HasHashables;
use Rinvex\Support\Traits\Macroable;
use Rinvex\OAuth\Traits\HasApiTokens;
use Rinvex\Auth\Traits\CanVerifyEmail;
use Rinvex\Auth\Traits\CanVerifyPhone;
use Cortex\Foundation\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Support\Traits\HashidsTrait;
use Rinvex\Support\Traits\HasTimezones;
use Illuminate\Notifications\Notifiable;
use Rinvex\Auth\Traits\CanResetPassword;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\CausesActivity;
use Rinvex\Support\Traits\HasSocialAttributes;
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
    use Taggable;
    use Auditable;
    use Macroable;
    use Notifiable;
    use HasApiTokens;
    use HasTimezones;
    use HashidsTrait;
    use Authorizable;
    use HasHashables;
    use LogsActivity;
    use InteractsWithMedia;
    use CanVerifyEmail;
    use CausesActivity;
    use CanVerifyPhone;
    use Authenticatable;
    use ValidatingTrait;
    use CanResetPassword;
    use HasSocialAttributes;
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
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'username' => 'string',
        'password' => 'string',
        'two_factor' => 'array',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'phone' => 'string',
        'phone_verified_at' => 'datetime',
        'given_name' => 'string',
        'family_name' => 'string',
        'title' => 'string',
        'organization' => 'string',
        'country_code' => 'string',
        'language_code' => 'string',
        'timezone' => 'string',
        'birthday' => 'string',
        'gender' => 'string',
        'social' => 'array',
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
                if ($user->isDirty($hashable) && Hash::needsRehash($user->{$hashable})) {
                    $user->{$hashable} = Hash::make($user->{$hashable});
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
        if (! ($authyId = Arr::get($this->getTwoFactor(), 'phone.authy_id')) && $this->getEmailForVerification() && $this->getPhoneForVerification() && $this->getCountryForVerification()) {
            $result = app('rinvex.authy.user')->register($this->getEmailForVerification(), preg_replace('/[^0-9]/', '', $this->getPhoneForVerification()), $this->getCountryForVerification());
            $authyId = $result->get('user')['id'];

            // Prepare required variables
            $twoFactor = $this->getTwoFactor();

            // Update user account
            Arr::set($twoFactor, 'phone.authy_id', $authyId);

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
     * Get full name attribute.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return implode(' ', [$this->given_name, $this->family_name]);
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

    /**
     * Get managed roles.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getManagedRoles(): Collection
    {
        if ($this->isA('superadmin')) {
            $roles = app('cortex.auth.role')->all();
        } elseif ($this->isA('supermanager')) {
            $roles = $this->roles->merge(app('request.tenant') ? app('cortex.auth.role')->where('scope', app('request.tenant')->getKey())->get() : collect());
        } else {
            $roles = $this->roles;
        }

        return $roles->pluck('title', 'id')->sort();
    }

    /**
     * Get managed abilites.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getManagedAbilities(): Collection
    {
        $abilities = $this->isA('superadmin') ? app('cortex.auth.ability')->all() : $this->getAbilities();

        return $abilities->groupBy('entity_type')->map->pluck('title', 'id')->sortKeys();
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
