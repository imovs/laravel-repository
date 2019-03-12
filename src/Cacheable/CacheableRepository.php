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
     * Get the class name
     *
     * @return string
     */
    protected function className()
    {
        return get_called_class();
    }

    /**
     * Serialize the criteria making sure the Closures are taken care of.
     *
     * @return string
     */
    protected function serializeCriteria()
    {
        try {
            return serialize($this->getCriterias());
        } catch (Exception $e) {
            return serialize($this->getCriterias()->map(function ($criterion) {
                return $this->serializeCriterion($criterion);
            }));
        }
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
        $args     = sha1(serialize($args));
        $criteria = $this->serializeCriteria();

        return sprintf(
            '%s@%s:%s',
            $this->className(),
            $method,
            sha1(serialize($args) . $this->serializeCriteria() . request()->fullUrl())
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
}
