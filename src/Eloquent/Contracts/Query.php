<?php

namespace Imovs\Repository\Eloquent\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface Query
 *
 * @package Imovs\Repository\Eloquent\Contracts
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
interface Query
{
    /**
     * Sync relations
     *
     * @param $id
     * @param $relation
     * @param $attributes
     * @return mixed
     */
    public function sync($id, $relation, $attributes);

    /**
     * Toggle relations
     *
     * @param $id
     * @param $relation
     * @param array $attributes
     * @return mixed
     */
    public function toggle($id, $relation, $attributes);

    /**
     * Toggle relations
     *
     * @param $id
     * @param $relation
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $attributes
     * @return mixed
     */
    public function save($id, $relation, Model $model, $attributes);

    /**
     * Toggle relations
     *
     * @param $id
     * @param $relation
     * @param $relationshipId
     * @param array $attributes
     * @return mixed
     */
    public function updateExistingPivot($id, $relation, $relationshipId, $attributes);

    /**
     * Sync relations without detaching
     *
     * @param $id
     * @param $relation
     * @param $attributes
     * @return mixed
     */
    public function syncWithoutDetaching($id, $relation, $attributes);

    /**
     * Check if entity has relation
     *
     * @param string $relation
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function has($relation);

    /**
     * Load relations
     *
     * @param array|string $relations
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function with($relations);

    /**
     * Add subselect queries to count the relations.
     *
     * @param  mixed $relations
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function withCount($relations);

    /**
     * Load relation with closure
     *
     * @param string $relation
     * @param closure $closure
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function whereHas($relation, $closure);

    /**
     * Querying relationship absence - doesnt have
     *
     * @param string|array $relation
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function doesntHave($relation);

    /**
     * Querying relationship absence - where doesnt have
     *
     * @param string|array $relation
     * @param \Closure $closure
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function whereDoesntHave($relation, $closure);

    /**
     * Set hidden fields
     *
     * @param array $fields
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function hidden(array $fields);

    /**
     * Order by
     * @param  string $column
     * @param  string $direction
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function orderBy($column, $direction = 'asc');

    /**
     * Set visible fields
     *
     * @param array $fields
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function visible(array $fields);

    /**
     * With trashed
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function withTrashed();

    /**
     * Without global scope
     *
     * @param  string|array $scope
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function withoutGlobalScope($scope);
}
