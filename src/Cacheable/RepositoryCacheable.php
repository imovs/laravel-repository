<?php

namespace Imovs\Repository\Cacheable;

use Imovs\Repository\Eloquent\Repository as BaseRepository;
use Suitmedia\Cacheable\Traits\Repository\CacheableTrait;
use Suitmedia\Cacheable\Contracts\CacheableRepository;

/**
 * Abstract calss Repository.
 *
 * @package \Imovs\Support\Database
 */
abstract class Repository extends BaseRepository implements CacheableRepository
{
    use CacheableTrait;

    /**
     * Return the cache tags which would
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
}
