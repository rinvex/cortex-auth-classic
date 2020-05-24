<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Cortex\Foundation\Traits\Auditable;
use Rinvex\Support\Traits\HashidsTrait;
use Rinvex\Support\Traits\HasTranslations;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Cortex\Foundation\Events\CrudPerformed;
use Cortex\Foundation\Traits\FiresCustomModelEvent;
use Silber\Bouncer\Database\Ability as BaseAbility;

class Ability extends BaseAbility
{
    use Auditable;
    use HashidsTrait;
    use LogsActivity;
    use HasTranslations;
    use ValidatingTrait;
    use FiresCustomModelEvent;

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
        'created' => CrudPerformed::class,
        'deleted' => CrudPerformed::class,
        'restored' => CrudPerformed::class,
        'updated' => CrudPerformed::class,
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
    protected $rules = [
        'title' => 'nullable|string|strip_tags|max:150',
        'name' => 'required|string|strip_tags|max:150',
        'entity_id' => 'nullable|integer',
        'entity_type' => 'nullable|string|strip_tags|max:150',
        'only_owned' => 'sometimes|boolean',
        'scope' => 'nullable|integer',
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
     * Attach the given roles to the model.
     *
     * @param mixed $roles
     *
     * @return void
     */
    public function setRolesAttribute($roles): void
    {
        static::saved(function (self $model) use ($roles) {
            $roles = collect($roles)->filter();

            $model->roles->pluck('id')->similar($roles)
            || activity()
                ->performedOn($model)
                ->withProperties(['attributes' => ['roles' => $roles], 'old' => ['roles' => $model->roles->pluck('id')->toArray()]])
                ->log('updated');

            $model->roles()->sync($roles, true);
        });
    }
}
