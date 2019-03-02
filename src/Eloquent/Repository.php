<?php

namespace Imovs\Repository\Eloquent;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Imovs\Repository\Eloquent\Concerns;
use Illuminate\Container\Container as Application;
use Imovs\Repository\Exceptions\RepositoryException;
use Imovs\Repository\Criteria\RepositoryCriteria;
use Imovs\Repository\Eloquent\Contracts\Repository as RepositoryContract;
use Suitmedia\Cacheable\Traits\Repository\CacheableTrait;
use Suitmedia\Cacheable\Contracts\CacheableRepository;

/**
 * Class Repository
 *
 * @package Imovs\Repository\Eloquent
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
abstract class Repository implements RepositoryContract, CacheableRepository
{
    use  Concerns\Collections,
         Concerns\Creates,
         Concerns\Deletes,
         Concerns\Finds,
         Concerns\Queries,
         Concerns\Updates,
         RepositoryCriteria,
         CacheableTrait;

    /**
     * Laravel container
     *
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * Eloquent model
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Scope query
     *
     * @var \Closure
     */
    protected $scopeQuery = null;

    /**
     * Repository constructor
     *
     * @param \Illuminate\Container\Container $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->criterias = new Collection();
        $this->model = $this->makeModel();

        $this->boot();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Specify model class name
     *
     * @return string
     */
    abstract public function model();

    /**
     * Make model instance
     *
     * @throws RepositoryException
     *
     * @return Model
     */
    protected function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $model;
    }

    /**
     * Reset model instance
     *
     * @throws RepositoryException
     *
     * @return void
     */
    public function resetModel()
    {
        $this->model = $this->makeModel();
    }

    /**
     * Query scope
     *
     * @param \Closure $scope
     *
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function scopeQuery(Closure $scope)
    {
        $this->scopeQuery = $scope;

        return $this;
    }

    /**
     * Reset Query Scope
     *
     * @return \Imovs\Repository\Eloquent\Repository
     */
    protected function resetScope()
    {
        $this->scopeQuery = null;

        return $this;
    }

    /**
     * Apply scope in current Query
     *
     * @return \Imovs\Repository\Eloquent\Repository
     */
    protected function applyScope()
    {
        if (isset($this->scopeQuery) && is_callable($this->scopeQuery)) {
            $callback = $this->scopeQuery;
            $this->model = $callback($this->model);
        }

        return $this;
    }

    /**
     * Applies the given where conditions to the model.
     *
     * @param array $where
     * @return void
     */
    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->model = $this->model->where($field, $condition, $val);
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }

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
