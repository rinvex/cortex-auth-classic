<?php

declare(strict_types=1);

namespace Cortex\Auth\Overrides\Silber\Bouncer;

use Illuminate\Contracts\Cache\Store;
use Silber\Bouncer\Bouncer as BaseBouncer;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class Bouncer extends BaseBouncer
{
    /**
     * Use a cached clipboard with the given cache instance.
     *
     * @param  \Illuminate\Contracts\Cache\Store  $cache
     * @return $this
     */
    public function cache(Store $cache = null)
    {
        $cache = $cache ?: $this->resolve(CacheRepository::class)->getStore();

        if ($this->usesCachedClipboard()) {
            $this->guard->getClipboard()->setCache($cache);

            return $this;
        }

        return $this->setClipboard(new CachedClipboard($cache));
    }

    /**
     * Fully disable all query caching.
     *
     * @return $this
     */
    public function dontCache()
    {
        return $this->setClipboard(new Clipboard);
    }
}
