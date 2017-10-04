<?php

declare(strict_types=1);

namespace Cortex\Fort\Models;

use Rinvex\Fort\Models\User as BaseUser;
use Rinvex\Attributes\Traits\Attributable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\ActivitylogServiceProvider;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Cortex\Fort\Models\User.
 *
 * @property int                                                                                                            $id
 * @property string                                                                                                         $username
 * @property string                                                                                                         $password
 * @property string|null                                                                                                    $remember_token
 * @property string                                                                                                         $email
 * @property bool                                                                                                           $email_verified
 * @property \Carbon\Carbon                                                                                                 $email_verified_at
 * @property string                                                                                                         $phone
 * @property bool                                                                                                           $phone_verified
 * @property \Carbon\Carbon                                                                                                 $phone_verified_at
 * @property string                                                                                                         $name_prefix
 * @property string                                                                                                         $first_name
 * @property string                                                                                                         $middle_name
 * @property string                                                                                                         $last_name
 * @property string                                                                                                         $name_suffix
 * @property string                                                                                                         $job_title
 * @property string                                                                                                         $country_code
 * @property string                                                                                                         $language_code
 * @property array                                                                                                          $two_factor
 * @property string                                                                                                         $birthday
 * @property string                                                                                                         $gender
 * @property bool                                                                                                           $is_active
 * @property \Carbon\Carbon                                                                                                 $last_activity
 * @property \Carbon\Carbon                                                                                                 $created_at
 * @property \Carbon\Carbon                                                                                                 $updated_at
 * @property \Carbon\Carbon                                                                                                 $deleted_at
 * @property \Illuminate\Database\Eloquent\Collection|\Cortex\Fort\Models\Ability[]                                         $abilities
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cortex\Foundation\Models\Log[]                                  $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cortex\Foundation\Models\Log[]                                  $causedActivity
 * @property-read \Illuminate\Support\Collection                                                                            $all_abilities
 * @property-read \Rinvex\Country\Country                                                                                   $country
 * @property mixed                                                                                                          $entity
 * @property-read \Rinvex\Language\Language                                                                                 $language
 * @property-read string                                                                                                    $name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property \Illuminate\Database\Eloquent\Collection|\Cortex\Fort\Models\Role[]                                            $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Fort\Models\Session[]                                    $sessions
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Fort\Models\Socialite[]                                  $socialites
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Fort\Models\User active()
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User hasAttribute($key, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Fort\Models\User inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Fort\Models\User role($roles)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereEmailVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereLanguageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereLastActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereNamePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereNameSuffix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User wherePhoneVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User wherePhoneVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereTwoFactor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends BaseUser
{
    use Attributable;
    use LogsActivity;

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
        'job_title',
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
     * Get the caused activity relations for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function causedActivity(): MorphMany
    {
        return $this->morphMany(ActivitylogServiceProvider::determineActivityModel(), 'causer');
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
