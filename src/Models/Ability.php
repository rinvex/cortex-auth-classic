<?php

declare(strict_types=1);

namespace Cortex\Fort\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Rinvex\Fort\Models\Ability as BaseAbility;

/**
 * Cortex\Fort\Models\Ability.
 *
 * @property int                                                                           $id
 * @property string                                                                        $action
 * @property string                                                                        $resource
 * @property string                                                                        $policy
 * @property array                                                                         $name
 * @property array                                                                         $description
 * @property \Carbon\Carbon                                                                $created_at
 * @property \Carbon\Carbon                                                                $updated_at
 * @property \Carbon\Carbon                                                                $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cortex\Foundation\Models\Log[] $activity
 * @property-read string                                                                   $slug
 * @property \Illuminate\Database\Eloquent\Collection|\Cortex\Fort\Models\Role[]           $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cortex\Fort\Models\User[]      $users
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Ability whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Ability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Ability whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Ability whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Ability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Ability whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Ability wherePolicy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Ability whereResource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Fort\Models\Ability whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ability extends BaseAbility
{
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
        'action',
        'resource',
        'policy',
        'name',
        'description',
        'roles',
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
