<?php

namespace LaravelBundle\Repository\Eloquent\Contracts;

/**
 * Interface Update
 *
 * @package LaravelBundle\Repository\Eloquent\Contracts
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
interface Update
{
    /**
     * Update a entity in repository by id
     *
     * @param array $attributes
     * @param       $id
     * @return mixed
     */
    public function update(array $attributes, $id);

    /**
     * Update or Create an entity in repository
     *
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values = []);
}
