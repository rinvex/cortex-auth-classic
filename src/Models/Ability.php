<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Spatie\Activitylog\LogOptions;
use Silber\Bouncer\Database\Models;
use Cortex\Auth\Events\AbilityCreated;
use Cortex\Auth\Events\AbilityDeleted;
use Cortex\Auth\Events\AbilityUpdated;
use Cortex\Auth\Events\AbilityRestored;
use Cortex\Foundation\Traits\Auditable;
use Rinvex\Support\Traits\HashidsTrait;
use Rinvex\Support\Traits\HasTimezones;
use Rinvex\Support\Traits\HasTranslations;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Silber\Bouncer\Database\Ability as BaseAbility;

class Ability extends BaseAbility
{
    use Auditable;
    use HashidsTrait;
    use HasTimezones;
    use LogsActivity;
    use HasTranslations;
    use ValidatingTrait;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'title',
        'entity_id',
        'entity_type',
        'only_owned',
        'scope',
        'roles',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'name' => 'string',
        'title' => 'string',
        'entity_id' => 'integer',
        'entity_type' => 'string',
        'only_owned' => 'boolean',
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
        'created' => AbilityCreated::class,
        'updated' => AbilityUpdated::class,
        'deleted' => AbilityDeleted::class,
        'restored' => AbilityRestored::class,
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
     * Whether the model should throw a
     * ValidationException if it fails validation.
     *
     * @var bool
     */
    protected $throwValidationExceptions = true;

    /**
     * Constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = Models::table('abilities');
        $this->mergeRules([
            'title' => 'nullable|string|strip_tags|max:150',
            'name' => 'required|string|strip_tags|max:150',
            'entity_id' => 'nullable|integer',
            'entity_type' => 'nullable|string|strip_tags|max:150',
            'only_owned' => 'sometimes|boolean',
            'scope' => 'nullable|integer',
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
