<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Support\Traits\HasTimezones;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Cortex\Auth\Models\Socialite.
 *
 * @property int                 $id
 * @property int                 $user_id
 * @property string              $user_type
 * @property string              $provider
 * @property string              $provider_uid
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Cortex\Auth\Models\User|\Illuminate\Database\Eloquent\Model|\Eloquent $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Auth\Models\Socialite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Auth\Models\Socialite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Auth\Models\Socialite whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Auth\Models\Socialite whereProviderUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Auth\Models\Socialite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Auth\Models\Socialite whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Auth\Models\Socialite whereUserType($value)
 * @mixin \Eloquent
 */
class Socialite extends Model
{
    use HasTimezones;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'user_id',
        'user_type',
        'provider',
        'provider_uid',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'user_id' => 'integer',
        'user_type' => 'string',
        'provider' => 'string',
        'provider_uid' => 'string',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('cortex.auth.tables.socialites'));

        parent::__construct($attributes);
    }

    /**
     * Get the owning user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user(): MorphTo
    {
        return $this->morphTo('user', 'user_type', 'user_id', 'id');
    }

    /**
     * Get socialites of the given user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfUser(Builder $builder, Model $user): Builder
    {
        return $builder->where('user_type', $user->getMorphClass())->where('user_id', $user->getKey());
    }
}
