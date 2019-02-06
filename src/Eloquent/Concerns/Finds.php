<?php

namespace Imovs\Repository\Eloquent\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Finds
 *
 * @package Imovs\Repository\Eloquent\Concerns
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
trait Finds
{
    /**
     * Get limit
     *
     * @param  int $limit
     * @return int
     */
    protected function getLimit($limit = null)
    {
        return request()->limit
            ?? $limit
            ?? config('repository.pagination.limit', 15);
    }

    /**
     * Retrieve all data
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = ['*'])
    {
        $this->applyCriterias();
        $this->applyScope();

        if ($this->model instanceof Builder) {
            $data = $this->model->get($columns);
        } else {
            $data = $this->model->all($columns);
        }

        $this->resetModel();
        $this->resetScope();

        return $data;
    }

    /**
     * Alias of all method
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get($columns = ['*'])
    {
        return $this->all($columns);
    }

    /**
     * Retrieve first data
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function first($columns = ['*'])
    {
        $this->applyCriterias();
        $this->applyScope();

        $data = $this->model->first($columns);
        $this->resetModel();

        return $data;
    }

    /**
     * Retrieve all data paginated
     *
     * @param null $limit
     * @param array $columns
     * @param string $method
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function paginate($limit = null, $columns = ['*'], $method = 'paginate')
    {
        $limit = $this->getLimit($limit);

        $this->applyCriterias();
        $this->applyScope();

        $data = $this->model->{$method}($limit, $columns);
        $this->resetModel();

        return $data;
    }

    /**
     * Retrieve all data simple paginated
     *
     * @param null $limit
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
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
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id, $columns = ['*'])
    {
        $this->applyCriterias();
        $this->applyScope();

        $model = $this->model->find($id, $columns);
        $this->resetModel();

        return $model;
    }

    /**
     * Find or fail data by id
     *
     * @param       $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id, $columns = ['*'])
    {
        $this->applyCriterias();
        $this->applyScope();

        $model = $this->model->findOrFail($id, $columns);
        $this->resetModel();

        return $model;
    }

    /**
     * Find data by field and value
     *
     * @param       $field
     * @param       $value
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByField($field, $value, $columns = ['*'])
    {
        $this->applyCriterias();
        $this->applyScope();

        $model = $this->model->where($field, '=', $value)->get($columns);
        $this->resetModel();

        return $model;
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
        $this->applyCriterias();
        $this->applyScope();

        $model = $this->model
            ->where($field, '=', $value)
            ->get($columns)
            ->first();

        $this->resetModel();

        return $model;
    }

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findWhere(array $where, $columns = ['*'])
    {
        $this->applyCriterias();
        $this->applyScope();
        $this->applyConditions($where);

        $model = $this->model->get($columns);

        $this->resetModel();

        return $model;
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
        $this->applyCriterias();
        $this->applyScope();

        $model = $this->model->whereIn($field, $values)->get($columns);

        $this->resetModel();

        return $model;
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
        $this->applyCriterias();
        $this->applyScope();

        $model = $this->model->whereNotIn($field, $values)->get($columns);

        $this->resetModel();

        return $model;
    }
}
