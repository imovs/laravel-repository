<?php

namespace LaravelBundle\Repository\Eloquent;

use Closure;
use Illuminate\Database\Eloquent\Model;
use LaravelBundle\Repository\Eloquent\Concerns;
use Illuminate\Container\Container as Application;
use LaravelBundle\Repository\Exceptions\RepositoryException;
use LaravelBundle\Repository\Eloquent\Contracts\Repository as RepositoryContract;


/**
 * Class Repository
 *
 * @package LaravelBundle\Repository\Eloquent
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
abstract class Repository implements RepositoryContract
{
    use  Concerns\Collections,
         Concerns\Creates,
         Concerns\Deletes,
         Concerns\Finds,
         Concerns\Queries,
         Concerns\Updates;


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
        $this->makeModel();
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

        return $this->model = $model;
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
        $this->makeModel();
    }

    /**
     * Query scope
     *
     * @param \Closure $scope
     *
     * @return \LaravelBundle\Repository\Eloquent\Repository
     */
    public function scopeQuery(Closure $scope)
    {
        $this->scopeQuery = $scope;

        return $this;
    }

    /**
     * Reset Query Scope
     *
     * @return \LaravelBundle\Repository\Eloquent\Repository
     */
    protected function resetScope()
    {
        $this->scopeQuery = null;

        return $this;
    }

    /**
     * Apply scope in current Query
     *
     * @return \LaravelBundle\Repository\Eloquent\Repository
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
}
