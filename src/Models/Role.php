<?php

declare(strict_types=1);

namespace Cortex\Fort\Models;

use Rinvex\Fort\Models\Role as BaseRole;
use Rinvex\Attributable\Traits\Attributable;

/**
 * Cortex\Fort\Models\Role.
 *
 * @property int                                                                      $id
 * @property string                                                                   $slug
 * @property array                                                                    $name
 * @property array                                                                    $description
 * @property \Carbon\Carbon|null                                                      $created_at
 * @property \Carbon\Carbon|null                                                      $updated_at
 * @property \Carbon\Carbon|null                                                      $deleted_at
 * @property \Illuminate\Database\Eloquent\Collection|\Rinvex\Fort\Models\Ability[]   $abilities
 * @property-read array                                                               $ability_list
 * @property mixed                                                                    $entity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cortex\Fort\Models\User[] $users
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
}
