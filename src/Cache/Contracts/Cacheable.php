<?php

namespace Imovs\Repository\Cache\Contracts;

use Illuminate\Contracts\Cache\Repository as CacheRepository;

/**
 * Interface Cacheable
 *
 * @package \Imovs\Repository\Cache\Contracts
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
interface Cacheable
{
    /**
     * Get tag to cache
     *
     * @return mixed|\Illuminate\Database\Eloquent\Model
     */
    public function getCacheTag();

    /**
     * Set Cache Repository
     *
     * @param \Illuminate\Contracts\Cache\Repository $cacheRepository
     *
     * @return $this
     */
    public function setCacheRepository(CacheRepository $cacheRepository);

    /**
     * Return instance of Cache Repository
     *
     * @return \Illuminate\Contracts\Cache\Repository
     */
    public function getCacheRepository();

    /**
     * Get Cache key for the method
     *
     * @param $method
     * @param $args
     *
     * @return string
     */
    public function getCacheKey($method, $args = null);
}
