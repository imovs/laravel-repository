<?php

namespace LaravelBundle\Repository\Eloquent\Contracts;

/**
 * Interface Create
 *
 * @package LaravelBundle\Repository\Eloquent\Contracts
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
interface Create
{
    /**
     * Save a new entity
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes);

    /**
     * Retrieve first data or create new entity
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $attributes);

    /**
     * Retrieve first data or return new entity
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrNew(array $attributes);
}
