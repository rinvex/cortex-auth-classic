<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Spatie\Activitylog\LogOptions;
use Cortex\Auth\Events\RoleCreated;
use Cortex\Auth\Events\RoleDeleted;
use Cortex\Auth\Events\RoleUpdated;
use Cortex\Auth\Events\RoleRestored;
use Cortex\Foundation\Traits\Auditable;
use Rinvex\Support\Traits\HashidsTrait;
use Rinvex\Support\Traits\HasTimezones;
use Rinvex\Support\Traits\HasTranslations;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Silber\Bouncer\Database\Role as BaseRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends BaseRole
{
    use Auditable;
    use HasFactory;
    use HashidsTrait;
    use HasTimezones;
    use LogsActivity;
    use ValidatingTrait;
    use HasTranslations;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'title',
        'scope',
        'abilities',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'name' => 'string',
        'title' => 'string',
        'scope' => 'integer',
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
        'created' => RoleCreated::class,
        'updated' => RoleUpdated::class,
        'deleted' => RoleDeleted::class,
        'restored' => RoleRestored::class,
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'title',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * {@inheritdoc}
     */
    protected $validationMessages = [
        'name.unique' => 'The combination of (name & scope) fields has already been taken.',
        'scope.unique' => 'The combination of (name & scope) fields has already been taken.',
    ];

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
        $this->mergeRules([
            'title' => 'nullable|string|strip_tags|max:150',
            'name' => 'required|string|strip_tags|max:150|unique_with:'.config('cortex.auth.models.role').',scope',
            'scope' => 'nullable|integer|unique_with:'.config('cortex.auth.models.role').',name',
        ]);

        parent::__construct($attributes);
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
}
