<?php

declare(strict_types=1);

namespace Cortex\Auth\Overrides\Silber\Bouncer\Database;

use App\User;
use Silber\Bouncer\Database\Models as BaseModels;

class Models extends BaseModels
{
    /**
     * Set the model to be used for users.
     *
     * @param string $model
     *
     * @return void
     */
    public static function setUsersModel($model)
    {
        static::$models[User::class] = $model;
    }
}
