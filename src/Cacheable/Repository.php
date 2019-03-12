<?php

namespace Imovs\Repository\Cacheable;

use Closure;
use Imovs\Repository\Cacheable\Concerns;
use Illuminate\Container\Container as App;
use Imovs\Repository\Cacheable\CacheableService;
use Imovs\Repository\Eloquent\Repository as BaseRepository;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Imovs\Repository\Cacheable\Contracts\Cacheable as CacheableContract;

/**
 * Class Repository
 *
 * @package Imovs\Repository\Eloquent
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
abstract class Repository extends BaseRepository implements CacheableContract
{
    use  Concerns\Collections,
         Concerns\Finds,
         CacheableRepository;

     /**
      * Cache service
      *
      * @var \Imovs\Repository\Cacheable\Cacheable
      */
     private $cacheable;

     /**
      * Repository constructor
      *
      * @param \Illuminate\Container\Container $app
      */
     public function __construct(App $app, CacheableService $cacheableService)
     {
         parent::__construct($app);

         $this->cacheable = $cacheableService;
     }

    /**
     * Finds whether the method is cacheable.
     *
     * @param string $method
     *
     * @return bool
     */
    private function methodIsCacheable($method)
    {
        $cacheEnabled = config('repository.cacheable.enabled', true);

        if (!$cacheEnabled) {
            return false;
        }

        return !in_array($method, $this->cacheExcept(), true);
    }

    /**
     * Retrieve data
     *
     * @param  string $method
     * @param  array $args
     * @return mixed
     */
    public function retrieve($method, $args)
    {
        if (!$this->methodIsCacheable($method)) {
            return call_user_func_array("parent::$method", $args);
        }

        $tags     = $this->cacheTags();
        $key      = $this->cacheKey($method, $args);
        $duration = $this->cacheDuration();
        $callable = function () use ($method, $args) {
            return call_user_func_array("parent::$method", $args);
        };

        return $this->cacheable->retrieve($tags, $key, $duration, $callable);
    }
}
