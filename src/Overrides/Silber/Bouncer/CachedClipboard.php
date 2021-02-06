<?php

declare(strict_types=1);

namespace Cortex\Auth\Overrides\Silber\Bouncer;

use Illuminate\Database\Eloquent\Model;
use Cortex\Auth\Overrides\Silber\Bouncer\Database\Models;
use Silber\Bouncer\CachedClipboard as BaseCachedClipboard;

class CachedClipboard extends BaseCachedClipboard
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
