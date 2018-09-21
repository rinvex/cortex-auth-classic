<?php

declare(strict_types=1);

namespace Cortex\Auth\Overrides\Silber\Bouncer;

use Cortex\Auth\Overrides\Silber\Bouncer\Database\Models;
use Silber\Bouncer\BouncerServiceProvider as BaseBouncerServiceProvider;

class BouncerServiceProvider extends BaseBouncerServiceProvider
{
    /**
     * Register Bouncer's models in the relation morph map.
     *
     * @return void
     */
    protected function registerMorphs()
    {
        // Do nothing!
    }

    /**
     * Set the classname of the user model to be used by Bouncer.
     *
     * @return void
     */
    protected function setUserModel()
    {
        Models::setUsersModel($this->getUserModel());
    }
}
