<?php

namespace Imovs\Repository\Cacheable\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Finds
 *
 * @package Imovs\Repository\Cacheable\Concerns
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
trait Finds
{
    /**
     * Retrieve all data
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = ['*'])
    {
        return $this->retrieve('all', func_get_args());
    }

    /**
     * Alias of all method
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get($columns = ['*'])
    {
        return $this->retrieve('get', func_get_args());
    }

    /**
     * Retrieve first data
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function first($columns = ['*'])
    {
        return $this->retrieve('first', func_get_args());
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
        return $this->retrieve('paginate', func_get_args());
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
        return $this->retrieve('simplePaginate', func_get_args());
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
        return $this->retrieve('find', func_get_args());
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
        return $this->retrieve('findOrFail', func_get_args());
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
        return $this->retrieve('findByField', func_get_args());
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
        return $this->retrieve('findByFieldFirst', func_get_args());
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
        return $this->retrieve('findWhere', func_get_args());
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
        return $this->retrieve('findWhereIn', func_get_args());
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
        return $this->retrieve('findWhereNotIn', func_get_args());
    }
}
