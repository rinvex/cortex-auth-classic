<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Vinkla\Hashids\Facades\Hashids;
use Rinvex\Tenants\Traits\Tenantable;
use Cortex\Foundation\Traits\Auditable;
use Rinvex\Support\Traits\HasTranslations;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Silber\Bouncer\Database\Role as BaseRole;

class Role extends BaseRole
{
    use Auditable;
    use Tenantable;
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
            'title' => 'nullable|string|max:150',
            'name' => 'required|string|max:150|unique:'.config('cortex.auth.tables.roles').',name,NULL,id,scope,'.($this->scope ?? 'null'),
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

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return Hashids::encode($this->getAttribute($this->getRouteKeyName()));
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        $value = Hashids::decode($value)[0];

        return $this->where($this->getRouteKeyName(), $value)->first();
    }
}
