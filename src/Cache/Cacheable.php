<?php

namespace Imovs\Repository\Cache;

use Exception;
use ReflectionObject;
use Illuminate\Http\Request;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Imovs\Repository\Criteria\Contracts\Criteria as CriteriaContract;

/**
 * Class CacheableRepository
 *
 * @package \Imovs\Repository\Cache
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
trait CacheableRepository
{
    /**
     * Cache minutes
     *
     * @var int
     */
    protected $cacheTime = null;

    /**
     * Skip cache
     *
     * @var bool
     */
    protected $cacheSkip = false;

    /**
     * Skip cache
     *
     * @var bool
     */
    protected $cacheClean = true;

    /**
     * Cache only
     *
     * @var array
     */
    protected $cacheOnly = [];

    /**
     * Cache except
     *
     * @var array
     */
    protected $cacheExcept = [];

    /**
     * Cache repository
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cacheRepository = null;

    /**
     * [Enter extra keys to mount the cache
     *
     * @return string
     */
    protected function extraKeys()
    {
        return '';
    }

    /**
     * Set cache repository
     *
     * @param \Illuminate\Contracts\Cache\Repository $repository
     *
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function setCacheRepository(CacheRepository $cacheRepository)
    {
        $this->cacheRepository = $cacheRepository;

        return $this;
    }

    /**
     * Get cache repository
     *
     * @return \Illuminate\Contracts\Cache\Repository
     */
    public function getCacheRepository()
    {
        if ($this->cacheRepository === null) {
            $this->cacheRepository = app(config('repository.cache.repository', 'cache'));
        }

        return $this->cacheRepository;
    }

    /**
     * Skip cache
     *
     * @param bool $status
     *
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function skipCache($status = true)
    {
        $this->cacheSkip = $status;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSkippedCache()
    {
        return $this->cacheSkip;
    }

    /**
     * Get cache clean
     *
     * @return bool
     */
    public function getCacheClean()
    {
        return $this->cacheClean ?? config('repository.cache.clean', true);
    }

    /**
     * Get cache time
     *
     * @return int
     */
    public function getCacheTime()
    {
        return $this->cacheTime ?? config('repository.cache.time', 30);
    }

    /**
     * Check allowed cache
     *
     * @param $method
     *
     * @return bool
     */
    protected function allowedCache($method)
    {
        $cacheEnabled = config('repository.cache.enabled', true);

        if (! $cacheEnabled) {
            return false;
        }

        if (count($this->cacheOnly)) {
            return in_array($method, $this->cacheOnly);
        }

        if (count($this->cacheExcept)) {
            return !in_array($method, $this->cacheExcept);
        }

        if (!count($this->cacheOnly) && !count($this->cacheExcept)) {
            return true;
        }

        return false;
    }

    /**
     * Get Cache key for the method
     *
     * @param $method
     * @param $args
     *
     * @return string
     */
    public function getCacheKey($method, $args = null)
    {
        $request = app(Request::class);
        $args     = serialize($args);
        $criteria = $this->serializeCriteria();

        return sprintf(
            '%s@%s:%s',
            get_called_class(),
            $method,
            md5($args . $criteria . $request->fullUrl() . $this->extraKeys())
        );
    }

    /**
     * Serialize the criteria making sure the Closures are taken care of.
     *
     * @return string
     */
    protected function serializeCriteria()
    {
        try {
            return serialize($this->getCriteria());
        } catch (Exception $e) {
            return serialize($this->getCriteria()->map(function ($criterion) {
                return $this->serializeCriterion($criterion);
            }));
        }
    }

    /**
     * Serialize single criterion with customized serialization of Closures.
     *
     * @param  \Imovs\Repository\Criteria\Contracts\Criteria $criterion
     * @return \Imovs\Repository\Criteria\Contracts\Criteria|array
     *
     * @throws \Exception
     */
    protected function serializeCriterion($criterion)
    {
        try {
            serialize($criterion);

            return $criterion;
        } catch (Exception $e) {

            if ($e->getMessage() !== "Serialization of 'Closure' is not allowed") {
                throw $e;
            }

            $r = new ReflectionObject($criterion);

            return [
                'hash' => md5((string) $r),
                'properties' => $r->getProperties(),
            ];
        }
    }

    /**
     * Retrieve first data
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function first($columns = ['*'])
    {
        if (!$this->allowedCache('first') || $this->isSkippedCache()) {
            return $this->model->first($columns);
        }

        $key      = $this->getCacheKey('first', func_get_args());
        $minutes  = $this->getCacheTime();

        $value = $this->getCacheRepository()
            ->tags($this->model->getTable())
            ->remember($key, $minutes, function () use ($columns) {
                return $this->model->first($columns);
            });

        $this->resetModel();
        $this->resetScope();

        return $value;
    }

    /**
     * Find data by id
     *
     * @param       $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        if (!$this->allowedCache('find') || $this->isSkippedCache()) {
            return $this->model->find($id, $columns);
        }

        $key      = $this->getCacheKey('find', func_get_args());
        $minutes  = $this->getCacheTime();

        $value = $this->getCacheRepository()
            ->tags($this->model->getTable())
            ->remember($key, $minutes, function () use ($id, $columns) {
                return $this->model->find($id, $columns);
            });

        $this->resetModel();
        $this->resetScope();

        return $value;
    }

    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        if (!$this->allowedCache('all') || $this->isSkippedCache()) {
            return $this->model->all($columns);
        }

        $key      = $this->getCacheKey('all', func_get_args());
        $minutes  = $this->getCacheTime();

        $value = $this->getCacheRepository()
            ->tags($this->model->getTable())
            ->remember($key, $minutes, function () use ($columns) {
                return $this->model->all($columns);
            });

        $this->resetModel();
        $this->resetScope();

        return $value;
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param null  $limit
     * @param array $columns
     * @param string $method
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'], $method = 'paginate')
    {
        if (!$this->allowedCache('paginate') || $this->isSkippedCache()) {
            return $this->model->paginate($limit, $columns, $method);
        }

        $key = $this->getCacheKey('paginate', func_get_args());
        $minutes = $this->getCacheTime();

        $value = $this->getCacheRepository()
            ->tags($this->model->getTable())
            ->remember($key, $minutes, function () use ($limit, $columns, $method) {
                return $this->model->paginate($limit, $columns, $method);
            });

        $this->resetModel();
        $this->resetScope();

        return $value;
    }

    /**
     * Find data by field and value
     *
     * @param       $field
     * @param       $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findByField($field, $value = null, $columns = ['*'])
    {
        if (!$this->allowedCache('findByField') || $this->isSkippedCache()) {
            return $this->model->findByField($field, $value, $columns);
        }

        $key      = $this->getCacheKey('findByField', func_get_args());
        $minutes  = $this->getCacheTime();

        $value = $this->getCacheRepository()
            ->tags($this->model->getTable())
            ->remember($key, $minutes, function () use ($field, $value, $columns) {
                return $this->model->findByField($field, $value, $columns);
            });

        $this->resetModel();
        $this->resetScope();

        return $value;
    }

    /**
     * Find data by field and value and get first
     *
     * @param string$field
     * @param string $value
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findByFieldFirst($field, $value, $columns = ['*'])
    {
        if (!$this->allowedCache('findByFieldFirst') || $this->isSkippedCache()) {
            return $this->model->findByFieldFirst($field, $value, $columns);
        }

        $key      = $this->getCacheKey('findByFieldFirst', func_get_args());
        $minutes  = $this->getCacheTime();

        $value = $this->getCacheRepository()
            ->tags($this->model->getTable())
            ->remember($key, $minutes, function () use ($field, $value, $columns) {
                return $this->model->findByFieldFirst($field, $value, $columns);
            });

        $this->applyCriterias();
        $this->applyScope();

        return $value;
    }

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhere(array $where, $columns = ['*'])
    {
        if (!$this->allowedCache('findWhere') || $this->isSkippedCache()) {
            return $this->model->findWhere($where, $columns);
        }

        $key      = $this->getCacheKey('findWhere', func_get_args());
        $minutes  = $this->getCacheTime();

        $value = $this->getCacheRepository()
            ->tags($this->model->getTable())
            ->remember($key, $minutes, function () use ($where, $columns) {
                return $this->model->findWhere($where, $columns);
            });

        $this->resetModel();
        $this->resetScope();

        return $value;
    }

    /**
     * Find data by multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findWhereIn($field, array $values, $columns = ['*'])
    {
        if (!$this->allowedCache('findWhereIn') || $this->isSkippedCache()) {
            return $this->model->findWhereIn($field, $values, $columns);
        }

        $key      = $this->getCacheKey('findWhereIn', func_get_args());
        $minutes  = $this->getCacheTime();

        $value = $this->getCacheRepository()
            ->tags($this->model->getTable())
            ->remember($key, $minutes, function () use ($field, $values, $columns) {
                return $this->model->findWhereIn($field, $values, $columns);
            });

        $this->resetModel();
        $this->resetScope();

        return $value;
    }

    /**
     * Find data by excluding multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findWhereNotIn($field, array $values, $columns = ['*'])
    {
        if (!$this->allowedCache('findWhereNotIn') || $this->isSkippedCache()) {
            return $this->model->findWhereNotIn($field, $values, $columns);
        }

        $key      = $this->getCacheKey('findWhereNotIn', func_get_args());
        $minutes  = $this->getCacheTime();

        $value = $this->getCacheRepository()
            ->tags($this->model->getTable())
            ->remember($key, $minutes, function () use ($field, $values, $columns) {
                return $this->model->findWhereNotIn($field, $values, $columns);
            });

        $this->resetModel();
        $this->resetScope();

        return $value;
    }

    /**
     * Find data by Criteria
     *
     * @param \Imovs\Repository\Criteria\Contracts\Criteria $criteria
     *
     * @return mixed
     */
    public function getByCriteria(CriteriaContract $criteria)
    {
        if (!$this->allowedCache('getByCriteria') || $this->isSkippedCache()) {
            return $this->model->getByCriteria($criteria);
        }

        $key      = $this->getCacheKey('getByCriteria', func_get_args());
        $minutes  = $this->getCacheTime();

        $value = $this->getCacheRepository()
            ->tags($this->model->getTable())
            ->remember($key, $minutes, function () use ($criteria) {
                return $this->model->getByCriteria($criteria);
            });

        $this->resetModel();
        $this->resetScope();

        return $value;
    }

    /**
     * Save a new entity.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes)
    {
        $model = $this->model->newInstance($attributes);
        $model->save();
        $this->resetModel();

        if ($this->getCacheClean()) {
            $this->getCacheRepository()
                ->tags($model->getTable())
                ->flush();
        }

        return $model;
    }

    /**
     * Retrieve first data or create new entity.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $attributes)
    {
        $this->applyCriterias();
        $this->applyScope();

        $model = $this->model->firstOrCreate($attributes);
        $this->resetModel();

        if ($model->wasRecentlyCreated && $this->getCacheClean()) {
            $this->getCacheRepository()
                ->tags($model->getTable())
                ->flush();
        }

        return $model;
    }/**
     * Update a entity in repository by id
     *
     * @param array $attributes
     * @param       $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        $this->applyScope();

        $model = $this->model->findOrFail($id);
        $model->fill($attributes);
        $model->save();

        $this->resetModel();

        if ($this->getCacheClean()) {
            $this->getCacheRepository()
                ->tags($model->getTable())
                ->flush();
        }

        return $model;
    }

    /**
     * Update or Create an entity in repository
     *
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        $this->applyScope();

        $model = $this->model->updateOrCreate($attributes, $values);

        $this->resetModel();

        if ($model->wasRecentlyCreated && $this->getCacheClean()) {
            $this->getCacheRepository()
                ->tags($model->getTable())
                ->flush();
        }

        return $model;
    }
}
