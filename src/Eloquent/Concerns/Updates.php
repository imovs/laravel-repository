<?php

namespace Imovs\Repository\Eloquent\Concerns;

/**
 * Trait Queries
 *
 * @package Imovs\Repository\Eloquent\Concerns
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
trait Updates
{
    /**
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

        return $model;
    }
}
