<?php

namespace Imovs\Repository\Eloquent;

use Suitmedia\Cacheable\Traits\Repository\CacheableTrait;
use Suitmedia\Cacheable\Contracts\CacheableRepository;

/**
 * Class Cacheable
 *
 * @package Imovs\Repository\Cacheable
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
abstract class Cacheable extends Repository implements CacheableRepository
{
    use CacheableTrait;

    /**
     * Return the cache tags which woulds
     * be used by the repository.
     *
     * @return mixed
     */
    public function cacheTags()
    {
        if (property_exists($this, 'cacheTags')) {
            return (array) static::$cacheTags;
        }

        $model = $this->model();

        if (is_string($model)) {
            $model = \App::make($model);
        }

        return $model->cacheTags();
    }

    /**
     * Get cacheable instance
     *
     * @return \Suitmedia\Cacheable\CacheableDecorator
     */
    public static function cacheable()
    {
        return \Cacheable::wrap(get_called_class());
    }
}
