<?php

namespace Imovs\Repository\Eloquent\Concerns;

/**
 * Trait Collections
 *
 * @package Imovs\Repository\Eloquent\Concerns
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
trait Collections
{
    /**
     * Retrieve data array for populate field select.
     *
     * @param string $column
     * @param string|null $key
     * @return array
     */
    public function lists($column, $key = null)
    {
        return $this->pluck($column, $key)->toArray();
    }

    /**
     * Retrieve data array for populate field select.
     *
     * @param string $column
     * @param string|null $key
     * @return \Illuminate\Support\Collection
     */
    public function pluck($column, $key = null)
    {
        return $this->model->pluck($column, $key);
    }
}
