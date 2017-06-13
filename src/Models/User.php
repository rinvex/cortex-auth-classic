<?php

declare(strict_types=1);

namespace Cortex\Fort\Models;

use Rinvex\Fort\Models\User as BaseUser;
use Rinvex\Attributable\Traits\Attributable;

/**
 * Cortex\Fort\Models\User.
 *
 * @property int                                                                                                            $id
 * @property string                                                                                                         $username
 * @property string                                                                                                         $password
 * @property string                                                                                                         $remember_token
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
 * @property \Carbon\Carbon                                                                                                 $birthday
 * @property string                                                                                                         $gender
 * @property string                                                                                                         $website
 * @property string                                                                                                         $twitter
 * @property string                                                                                                         $facebook
 * @property string                                                                                                         $linkedin
 * @property string                                                                                                         $google_plus
 * @property string                                                                                                         $skype
 * @property bool                                                                                                           $active
 * @property \Carbon\Carbon                                                                                                 $login_at
 * @property \Carbon\Carbon                                                                                                 $created_at
 * @property \Carbon\Carbon                                                                                                 $updated_at
 * @property \Carbon\Carbon                                                                                                 $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cortex\Fort\Models\Ability[]                                    $abilities
 * @property-read array                                                                                                     $ability_list
 * @property-read \Illuminate\Support\Collection                                                                            $all_abilities
 * @property-read \Rinvex\Country\Country                                                                                   $country
 * @property mixed                                                                                                          $entity
 * @property-read \Rinvex\Language\Language                                                                                 $language
 * @property-read string                                                                                                    $name
 * @property-read array                                                                                                     $role_list
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Fort\Models\Persistence[]                                $persistences
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cortex\Fort\Models\Role[]                                       $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rinvex\Fort\Models\Socialite[]                                  $socialites
 *
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User hasAttribute($key, $value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User role($roles)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereBirthday($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereCountryCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereEmailVerified($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereFacebook($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereGooglePlus($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereJobTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereLanguageCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereLinkedin($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereLoginAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereMiddleName($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereNamePrefix($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereNameSuffix($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User wherePhoneVerified($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User wherePhoneVerifiedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereSkype($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereTwitter($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereTwoFactor($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\User whereWebsite($value)
 * @mixin \Eloquent
 */
class User extends BaseUser
{
    use Attributable;
}
