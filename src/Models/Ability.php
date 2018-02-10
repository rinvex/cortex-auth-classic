<?php

declare(strict_types=1);

namespace Cortex\Fort\Models;

use Cortex\Foundation\Traits\Auditable;
use Rinvex\Support\Traits\HasTranslations;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Silber\Bouncer\Database\Ability as BaseAbility;

class Ability extends BaseAbility
{
    use Auditable;
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
        'name' => 'nullable|string|max:150',
        'title' => 'nullable|string',
        'entity_id' => 'nullable|integer',
        'entity_type' => 'nullable|string',
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
            activity()
                ->performedOn($model)
                ->withProperties(['attributes' => ['roles' => $roles], 'old' => ['roles' => $model->roles->pluck('id')->toArray()]])
                ->log('updated');

            $model->roles()->sync($roles, true);
        });
    }
}
