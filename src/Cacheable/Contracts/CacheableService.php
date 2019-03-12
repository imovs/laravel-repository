<?php

namespace Imovs\Repository\Cacheable\Contracts;

use Closure;
use Illuminate\Contracts\Cache\Repository;

interface CacheableService
{
    /**
     * Flush cache.
     *
     * @param mixed $tags
     *
     * @return void
     */
    public function flush($tags);

    /**
     * Retrieve cached items.
     *
     * @param mixed   $tags
     * @param string  $key
     * @param int     $duration
     * @param Closure $callable
     *
     * @return mixed
     */
    public function retrieve($tags, $key, $duration, Closure $callable);
}
