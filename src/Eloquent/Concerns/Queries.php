<?php

namespace LaravelBundle\Repository\Eloquent\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait Queries
 *
 * @package LaravelBundle\Repository\Eloquent\Concerns
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
trait Queries
{
    /**
     * Sync relations
     *
     * @param $id
     * @param $relation
     * @param array $attributes
     * @return mixed
     */
    public function sync($id, $relation, $attributes)
    {
        return $this->find($id)->{$relation}()->sync($attributes);
    }

    /**
     * Toggle relations
     *
     * @param $id
     * @param $relation
     * @param array $attributes
     * @return mixed
     */
    public function toggle($id, $relation, $attributes)
    {
        return $this->find($id)->{$relation}()->toggle($attributes);
    }

    /**
     * Toggle relations
     *
     * @param $id
     * @param $relation
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $attributes
     * @return mixed
     */
    public function save($id, $relation, Model $model, $attributes)
    {
        return $this->find($id)->{$relation}()->save($model, $attributes);
    }

    /**
     * Toggle relations
     *
     * @param $id
     * @param $relation
     * @param $relationshipId
     * @param array $attributes
     * @return mixed
     */
    public function updateExistingPivot($id, $relation, $relationshipId, $attributes)
    {
        return $this->find($id)->{$relation}()->updateExistingPivot($relationshipId, $attributes);
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
     * Check if entity has relation
     *
     * @param string $relation
     * @return \LaravelBundle\Repository\Eloquent\Repository
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
     * @return \LaravelBundle\Repository\Eloquent\Repository
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
     * @return \LaravelBundle\Repository\Eloquent\Repository
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
     * @param \Closure $closure
     * @return \LaravelBundle\Repository\Eloquent\Repository
     */
    public function whereHas($relation, $closure)
    {
        $this->model = $this->model->whereHas($relation, $closure);

        return $this;
    }

    /**
     * Querying relationship absence - doesnt have
     *
     * @param string|array $relation
     * @return \LaravelBundle\Repository\Eloquent\Repository
     */
    public function doesntHave($relation)
    {
        $this->model = $this->model->doesntHave($relation);

        return $this;
    }

    /**
     * Querying relationship absence - where doesnt have
     *
     * @param string|array $relation
     * @param \Closure $closure
     * @return \LaravelBundle\Repository\Eloquent\Repository
     */
    public function whereDoesntHave($relation, $closure)
    {
        $this->model = $this->model->whereDoesntHave($relation, $closure);

        return $this;
    }

    /**
     * Set hidden fields
     *
     * @param array $fields
     * @return \LaravelBundle\Repository\Eloquent\Repository
     */
    public function hidden(array $fields)
    {
        $this->model->setHidden($fields);

        return $this;
    }

    /**
     * Order by
     * @param  string $column
     * @param  string $direction
     * @return \LaravelBundle\Repository\Eloquent\Repository
     */
    public function orderBy($column, $direction = 'asc')
    {
        $this->model = $this->model->orderBy($column, $direction);

        return $this;
    }

    /**
     * Set visible fields
     *
     * @param array $fields
     * @return \LaravelBundle\Repository\Eloquent\Repository
     */
    public function visible(array $fields)
    {
        $this->model->setVisible($fields);

        return $this;
    }

    /**
     * With trashed
     *
     * @return \LaravelBundle\Repository\Eloquent\Repository
     */
    public function withTrashed()
    {
        $this->model = $this->model->withTrashed();

        return $this;
    }

    /**
     * Without global scope
     *
     * @param  string|array $scope
     * @return \LaravelBundle\Repository\Eloquent\Repository
     */
    public function withoutGlobalScope($scope)
    {
        $this->model = $this->model->withoutGlobalScope($scope);

        return $this;
    }
}
