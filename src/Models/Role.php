<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Cortex\Foundation\Traits\Auditable;
use Rinvex\Support\Traits\HashidsTrait;
use Rinvex\Support\Traits\HasTimezones;
use Cortex\Foundation\Events\ModelCreated;
use Cortex\Foundation\Events\ModelDeleted;
use Cortex\Foundation\Events\ModelUpdated;
use Rinvex\Support\Traits\HasTranslations;
use Rinvex\Support\Traits\ValidatingTrait;
use Cortex\Foundation\Events\ModelRestored;
use Spatie\Activitylog\Traits\LogsActivity;
use Silber\Bouncer\Database\Role as BaseRole;
use Cortex\Foundation\Traits\FiresCustomModelEvent;

class Role extends BaseRole
{
    use Auditable;
    use HashidsTrait;
    use HasTimezones;
    use LogsActivity;
    use ValidatingTrait;
    use HasTranslations;
    use FiresCustomModelEvent;

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
        'created' => ModelCreated::class,
        'deleted' => ModelDeleted::class,
        'restored' => ModelRestored::class,
        'updated' => ModelUpdated::class,
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
        parent::__construct($attributes);

        $this->setRules([
            'title' => 'nullable|string|strip_tags|max:150',
            'name' => 'required|string|strip_tags|max:150|unique:'.config('cortex.auth.tables.roles').',name,NULL,id,scope,'.($this->scope ?? 'null'),
            'scope' => 'nullable|integer|unique:'.config('cortex.auth.tables.roles').',scope,NULL,id,name,'.($this->name ?? 'null'),
        ]);
    }

    /**
     * Attach the given abilities to the model.
     *
     * @param mixed $abilities
     *
     * @return void
     */
    public function setAbilitiesAttribute($abilities): void
    {
        static::saved(function (self $model) use ($abilities) {
            $abilities = collect($abilities)->filter();

            $model->abilities->pluck('id')->similar($abilities)
            || activity()
                ->performedOn($model)
                ->withProperties(['attributes' => ['abilities' => $abilities], 'old' => ['abilities' => $model->abilities->pluck('id')->toArray()]])
                ->log('updated');

            $model->abilities()->sync($abilities, true);
        });
    }
}
