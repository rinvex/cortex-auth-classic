<?php

declare(strict_types=1);

namespace Cortex\Fort\Models;

use Rinvex\Fort\Models\Role as BaseRole;
use Spatie\Activitylog\Traits\LogsActivity;
use Rinvex\Attributable\Traits\Attributable;

/**
 * Cortex\Fort\Models\Role.
 *
 * @property int                                                                                 $id
 * @property string                                                                              $slug
 * @property string                                                                              $name
 * @property string                                                                              $description
 * @property \Carbon\Carbon|null                                                                 $created_at
 * @property \Carbon\Carbon|null                                                                 $updated_at
 * @property \Carbon\Carbon|null                                                                 $deleted_at
 * @property \Illuminate\Database\Eloquent\Collection|\Rinvex\Fort\Models\Ability[]              $abilities
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property mixed                                                                               $entity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cortex\Fort\Models\User[]            $users
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Role hasAttribute($key, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Role whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Role whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends BaseRole
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
        'slug',
        'name',
        'description',
        'abilities',
    ];

    /**
     * The attributes that are ignored on change.
     *
     * @var array
     */
    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
