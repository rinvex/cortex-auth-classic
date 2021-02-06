<?php

declare(strict_types=1);

namespace Cortex\Auth\Overrides\Silber\Bouncer\Database;

use Closure;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\Models as BaseModels;

class Models extends BaseModels
{
    /**
     * Determines whether the given model is owned by the given authority.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $authority
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return bool
     */
    public static function isOwnedBy(Model $authority, Model $model)
    {
        $type = get_class($model);

        if (isset(static::$ownership[$type])) {
            $attribute = static::$ownership[$type];
        } elseif (isset(static::$ownership['*'])) {
            $attribute = static::$ownership['*'];
        } else {
            $attribute = strtolower(static::basename($authority));
        }

        return static::isOwnedVia($attribute, $authority, $model);
    }

    /**
     * Determines ownership via the given attribute.
     *
     * @param  string|\Closure  $attribute
     * @param  \Illuminate\Database\Eloquent\Model  $authority
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return bool
     */
    protected static function isOwnedVia($attribute, Model $authority, Model $model)
    {

        if ($attribute instanceof Closure) {
            return $attribute($model, $authority);
        }

        return $authority->getMorphClass() === $model->{$attribute.'_type'} && $authority->getKey() === $model->{$attribute.'_id'};
    }
}
