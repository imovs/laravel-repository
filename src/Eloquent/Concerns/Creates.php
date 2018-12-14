<?php

namespace Imovs\Repository\Eloquent\Concerns;

/**
 * Trait Creates
 *
 * @package Imovs\Repository\Eloquent\Concerns
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
trait Creates
{
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

        return $model;
    }

    /**
     * Retrieve first data or return new entity.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrNew(array $attributes)
    {
        $this->applyCriterias();
        $this->applyScope();

        $model = $this->model->firstOrNew($attributes);
        $this->resetModel();

        return $model;
    }
}
