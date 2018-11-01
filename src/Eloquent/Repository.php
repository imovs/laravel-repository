<?php

namespace LaravelBundle\Repository\Eloquent;

use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
     * Closure
     *
     * @var \Closure
     */
    protected $scopeQuery = null;

    /**
     * Data
     *
     * @var mixed
     */
    private $data = null;

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
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Query scope
     *
     * @param \Closure $scope
     *
     * @return $this
     */
    public function scopeQuery(\Closure $scope)
    {
        $this->scopeQuery = $scope;

        return $this;
    }

    /**
     * Retrieve data array for populate field select
     *
     * @param string $column
     * @param string|null $key
     *
     * @return array
     */
    public function lists($column, $key = null)
    {
        return $this->pluck($column, $key)->toArray();
    }

    /**
     * Retrieve data array for populate field select
     *
     * @param string $column
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection
     */
    public function pluck($column, $key = null)
    {
        return $this->model->pluck($column, $key);
    }

    /**
     * Sync relations
     *
     * @param $id
     * @param $relation
     * @param $attributes
     *
     * @return mixed
     */
    public function sync($id, $relation, $attributes)
    {
        return $this->find($id)->{$relation}()->sync($attributes);
    }

    /**
     * Sync relations without detaching
     *
     * @param $id
     * @param $relation
     * @param $attributes
     * @return mixed
     */
    public function syncWithoutDetaching($id, $relation, $attributes)
    {
        return $this->find($id)->{$relation}()->syncWithoutDetaching($attributes);
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
        $this->applyScope();

        if ($this->model instanceof Builder) {
            $data = $this->model->get($columns);
        } else {
            $data = $this->model->all($columns);
        }

        $this->resetModel();
        $this->resetScope();

        $this->data = $data;

        return $this;
    }

    /**
     * Alias of all method
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function get($columns = ['*'])
    {
        return $this->all($columns);
    }


    /**
     * Retrieve first data of repository
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function first($columns = ['*'])
    {
        $this->applyScope();

        $data = $this->model->first($columns);

        $this->resetModel();

        $this->data = $data;

        return $this;
    }

    /**
     * Retrieve first data of repository, or return new Entity
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function firstOrNew(array $attributes = [])
    {
        $this->applyScope();

        $model = $this->model->firstOrNew($attributes);

        $this->resetModel();

        $this->data = $model;

        return $this;
    }

    /**
     * Retrieve first data of repository, or create new Entity
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function firstOrCreate(array $attributes = [])
    {
        $this->applyScope();

        $model = $this->model->firstOrCreate($attributes);

        $this->resetModel();

        $this->data = $model;

        return $this;
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param null $limit
     * @param array $columns
     * @param string $method
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'], $method = 'paginate')
    {
        $this->applyScope();

        $limit = $limit ?? config('repository.pagination.limit', 15);

        $data = $this->model->{$method}($limit, $columns);

        $this->resetModel();

        $this->data = $data;

        return $this;
    }

    /**
     * Retrieve all data of repository, simple paginated
     *
     * @param null $limit
     * @param array $columns
     *
     * @return mixed
     */
    public function simplePaginate($limit = null, $columns = ['*'])
    {
        return $this->paginate($limit, $columns, 'simplePaginate');
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
        $this->applyScope();

        $model = $this->model->find($id, $columns);

        $this->resetModel();

        $this->data = $model;

        return $this;
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
        $this->applyScope();

        $model = $this->model->where($field, '=', $value)->get($columns);

        $this->resetModel();

        $this->data = $model;

        return $this;
    }

    /**
     * Find data by field and value and get first
     *
     * @param       $field
     * @param       $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findByFieldFirst($field, $value = null, $columns = ['*'])
    {
        $this->applyScope();

        $model = $this->model->where($field, '=', $value)->get($columns);

        $this->resetModel();

        $this->data = $model;

        return $this;
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
        $this->applyScope();
        $this->applyConditions($where);

        $model = $this->model->get($columns);

        $this->resetModel();

        $this->data = $model;

        return $this;
    }

    /**
     * Find data by multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereIn($field, array $values, $columns = ['*'])
    {
        $this->applyScope();

        $model = $this->model->whereIn($field, $values)->get($columns);

        $this->resetModel();

        $this->data = $model;

        return $this;
    }

    /**
     * Find data by excluding multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereNotIn($field, array $values, $columns = ['*'])
    {
        $this->applyScope();

        $model = $this->model->whereNotIn($field, $values)->get($columns);

        $this->resetModel();

        $this->data = $model;

        return $this;
    }

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        $model = $this->model->newInstance($attributes);
        $model->save();
        $this->resetModel();

        $this->data = $model;

        return $this;
    }

    /**
     * Update a entity in repository by id
     *
     * @param array $attributes
     * @param       $id
     *
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        $this->applyScope();

        $model = $this->model->findOrFail($id);
        $model->fill($attributes);
        $model->save();

        $this->resetModel();

        $this->data = $model;

        return $this;
    }

    /**
     * Update or Create an entity in repository
     *
     * @param array $attributes
     * @param array $values
     *
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        $this->applyScope();

        $model = $this->model->updateOrCreate($attributes, $values);

        $this->resetModel();

        $this->data = $model;

        return $this;
    }

    /**
     * Delete a entity in repository by id
     *
     * @param $id
     *
     * @return bool
     */
    public function delete($id)
    {
        $this->applyScope();

        $model = $this->find($id);

        $this->resetModel();

        return $model->delete();
    }

    /**
     * Delete multiple entities by given criteria.
     *
     * @param array $where
     *
     * @return bool
     */
    public function deleteWhere(array $where)
    {
        $this->applyScope();
        $this->applyConditions($where);

        $deleted = $this->model->delete();

        $this->resetModel();

        return $deleted;
    }

    /**
     * Check if entity has relation
     *
     * @param string $relation
     *
     * @return $this
     */
    public function has($relation)
    {
        $this->model = $this->model->has($relation);

        return $this;
    }

    /**
     * Load relations
     *
     * @param array|string $relations
     *
     * @return $this
     */
    public function with($relations)
    {
        $this->model = $this->model->with($relations);

        return $this;
    }

    /**
     * Add subselect queries to count the relations.
     *
     * @param  mixed $relations
     *
     * @return $this
     */
    public function withCount($relations)
    {
        $this->model = $this->model->withCount($relations);

        return $this;
    }

    /**
     * Load relation with closure
     *
     * @param string $relation
     * @param closure $closure
     *
     * @return $this
     */
    public function whereHas($relation, $closure)
    {
        $this->model = $this->model->whereHas($relation, $closure);

        return $this;
    }

    /**
     * Set hidden fields
     *
     * @param array $fields
     *
     * @return $this
     */
    public function hidden(array $fields)
    {
        $this->model->setHidden($fields);

        return $this;
    }

    public function orderBy($column, $direction = 'asc')
    {
        $this->model = $this->model->orderBy($column, $direction);

        return $this;
    }

    /**
     * Set visible fields
     *
     * @param array $fields
     *
     * @return $this
     */
    public function visible(array $fields)
    {
        $this->model->setVisible($fields);

        return $this;
    }

    /**
     * With trashed
     *
     * @return $this
     */
    public function withTrashed()
    {
        $this->model = $this->model->withTrashed();

        return $this;
    }

    /**
     * Reset Query Scope
     *
     * @return $this
     */
    public function resetScope()
    {
        $this->scopeQuery = null;

        return $this;
    }

    /**
     * Apply scope in current Query
     *
     * @return $this
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
     * Get result data
     *
     * @return mixed
     */
    public function result()
    {
        $data = $this->data;
        $this->data = null;

        return $data;
    }
}
