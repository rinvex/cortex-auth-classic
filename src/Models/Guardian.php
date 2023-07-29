<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Rinvex\Auth\Traits\HasHashables;
use Cortex\Auth\Events\GuardianCreated;
use Cortex\Auth\Events\GuardianDeleted;
use Cortex\Auth\Events\GuardianUpdated;
use Cortex\Foundation\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Support\Traits\HashidsTrait;
use Rinvex\Support\Traits\HasTimezones;
use Cortex\Auth\Events\GuardianRestored;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Guardian extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Auditable;
    use HasFactory;
    use SoftDeletes;
    use HashidsTrait;
    use HasTimezones;
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
        'created' => GuardianCreated::class,
        'updated' => GuardianUpdated::class,
        'deleted' => GuardianDeleted::class,
        'restored' => GuardianRestored::class,
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
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('cortex.auth.tables.guardians'));
        $this->mergeRules([
            'username' => 'required|alpha_dash|min:3|max:64|unique:'.config('cortex.auth.models.guardian').',username',
            'password' => ['sometimes', 'required', config('validation.rules.password')],
            'email' => 'required|email:rfc,dns|min:3|max:128|unique:'.config('cortex.auth.models.guardian').',email',
            'is_active' => 'sometimes|boolean',
            'tags' => 'nullable|array',
        ]);

        parent::__construct($attributes);
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
     * Set sensible Activity Log Options.
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                         ->logFillable()
                         ->logOnlyDirty()
                         ->dontSubmitEmptyLogs();
    }

    /**
     * The user may have many sessions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function sessions(): MorphMany
    {
        return $this->morphMany(config('cortex.auth.models.session'), 'user', 'user_type', 'user_id');
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
