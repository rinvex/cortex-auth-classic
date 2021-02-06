<?php

declare(strict_types=1);

namespace Cortex\Auth\Overrides\Silber\Bouncer;

use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Clipboard as BaseClipboard;
use Cortex\Auth\Overrides\Silber\Bouncer\Database\Models;

class Clipboard extends BaseClipboard
{
    /**
     * Determine whether the authority owns the given model.
     *
     * @return bool
     */
    public function isOwnedBy($authority, $model)
    {
        return $model instanceof Model && Models::isOwnedBy($authority, $model);
    }
}
