<?php

declare(strict_types=1);

namespace Cortex\Auth\Guards;

use Illuminate\Auth\SessionGuard as BaseSessionGuard;

class SessionGuard extends BaseSessionGuard
{
    /**
     * Get a unique identifier for the auth session value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name.'_'.$this->getSession()->getSha1().'.login';
    }

    /**
     * Get the name of the cookie used to store the "recaller".
     *
     * @return string
     */
    public function getRecallerName()
    {
        return $this->name.'_'.$this->getSession()->getSha1().'.remember';
    }
}
