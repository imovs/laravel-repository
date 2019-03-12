<?php

namespace Imovs\Repository\Cacheable;

use Closure;
use Illuminate\Contracts\Cache\Repository;

class CacheableService
{
    /**
     * Cache manager object.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Class constructor.
     *
     * @param \Illuminate\Contracts\Cache\Repository $cache
     */
    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Flush cache.
     *
     * @param mixed $tags
     *
     * @return void
     */
    public function flush($tags)
    {
        $this->cache->tags($tags)->flush();
    }

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
    public function retrieve($tags, $key, $duration, Closure $callable)
    {
        return ($duration > 0) ?
            $this->cache->tags($tags)->remember($key, $duration, $callable) :
            $this->cache->tags($tags)->rememberForever($key, $callable);
    }
}
