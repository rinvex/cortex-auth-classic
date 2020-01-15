<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Rinvex\Auth\Traits\HasHashables;
use Cortex\Auth\Events\GuardianSaved;
use Cortex\Auth\Events\GuardianDeleted;
use Cortex\Foundation\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Support\Traits\HashidsTrait;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Guardian extends Model implements AuthenticatableContract, AuthorizableContract
{
    // @TODO: Strangely, this issue happens only here!!!
    // Duplicate trait usage to fire attached events for cache
    // flush before other events in other traits specially LogsActivity,
    // otherwise old cached queries used and no changelog recorded on update.
    use CacheableEloquent;
    use Auditable;
    use HashidsTrait;
    use LogsActivity;
    use Authorizable;
    use HasHashables;
    use Authenticatable;
    use ValidatingTrait;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'username',
        'password',
        'email',
        'is_active',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'username' => 'string',
        'password' => 'string',
        'email' => 'string',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'password',
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
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => GuardianSaved::class,
        'deleted' => GuardianDeleted::class,
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
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('cortex.auth.tables.guardians'));
        $this->setRules([
            'username' => 'required|alpha_dash|min:3|max:150|unique:'.config('cortex.auth.tables.guardians').',username',
            'password' => 'sometimes|required|min:'.config('cortex.auth.password_min_chars'),
            'email' => 'required|email|min:3|max:150|unique:'.config('cortex.auth.tables.guardians').',email',
            'is_active' => 'sometimes|boolean',
            'tags' => 'nullable|array',
        ]);
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
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'username';
    }
}
