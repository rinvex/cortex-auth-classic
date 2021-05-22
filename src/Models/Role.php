<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

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

class Role extends BaseRole
{
    use Auditable;
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
        'created_at',
        'updated_at',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->mergeRules([
            'title' => 'nullable|string|strip_tags|max:150',
            'name' => 'required|string|strip_tags|max:150|unique:'.config('cortex.auth.tables.roles').',name,NULL,id,scope,'.($this->scope ?? 'null'),
            'scope' => 'nullable|integer|unique:'.config('cortex.auth.tables.roles').',scope,NULL,id,name,'.($this->name ?? 'null'),
        ]);

        parent::__construct($attributes);
    }
}
