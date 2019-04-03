<?php

namespace Imovs\Repository\Cacheable;

/**
 * Trait CacheableRepository
 *
 * @package Imovs\Repository\Cacheable
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
trait CacheableRepository
{
    /**
     * Cache skip
     *
     * @var bool
     */
    private $cacheSkip = false;

    /**
     * Get the class name
     *
     * @return string
     */
    protected function className()
    {
        return get_called_class();
    }

    /**
     * Generate cache key.
     *
     * @param string $method
     * @param mixed  $args
     *
     * @return string
     */
    public function cacheKey($method, $args)
    {
        $args     = serialize($args);
        $request  = request()->fullUrl() . request()->getContent();

        return sprintf(
            '%s@%s:%s',
            $this->className(),
            $method,
            sha1($args . $request)
        );
    }

    /**
     * Return the cache duration value in seconds,
     * which would be used by the repository.
     *
     * @return int
     */
    public function cacheDuration()
    {
        if (property_exists($this, 'cacheDuration')) {
            return (int) static::$cacheDuration;
        }

        return (int) config('repository.cacheable.duration');
    }

    /**
     * Return the cache tags which would
     * be used by the repository.
     *
     * @return mixed
     */
    public function cacheTags()
    {
        $result = (array) $this->makeModel()->cacheTags();

        if (property_exists($this, 'cacheTags')) {
            $result = array_unique(array_merge($result, (array) static::$cacheTags));
        }

        return $result;
    }

    /**
     * Return an array of method names which
     * you don't wish to be cached.
     *
     * @return array
     */
    public function cacheExcept()
    {
        $result = (array) config('cacheable.except');

        if (property_exists($this, 'cacheExcept')) {
            $result = array_unique(array_merge($result, (array) static::$cacheExcept));
        }

        return $result;
    }

    /**
     * Skip Cache
     *
     * @param bool $status
     * @return $this
     */
    public function skipCache($status = true)
    {
        $this->cacheSkip = $status;

        return $this;
    }

    /**
     * Cache is skipped
     *
     * @return bool
     */
    public function isSkippedCache()
    {
        return $this->cacheSkip;
    }
}
