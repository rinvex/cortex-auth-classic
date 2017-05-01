<?php

declare(strict_types=1);

namespace Cortex\Fort\Models;

use Rinvex\Fort\Models\Ability as BaseAbility;

/**
 * Cortex\Fort\Models\Ability.
 *
 * @property int                                                                      $id
 * @property string                                                                   $action
 * @property string                                                                   $resource
 * @property string                                                                   $policy
 * @property string                                                                   $name
 * @property string                                                                   $description
 * @property \Carbon\Carbon                                                           $created_at
 * @property \Carbon\Carbon                                                           $updated_at
 * @property \Carbon\Carbon                                                           $deleted_at
 * @property-read string                                                              $slug
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cortex\Fort\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cortex\Fort\Models\User[] $users
 *
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\Ability whereAction($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\Ability whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\Ability whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\Ability whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\Ability whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\Ability whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\Ability wherePolicy($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\Ability whereResource($value)
 * @method static \Illuminate\Database\Query\Builder|\Cortex\Fort\Models\Ability whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ability extends BaseAbility
{
    //
}
