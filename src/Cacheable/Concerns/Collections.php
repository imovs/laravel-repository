<?php

namespace Imovs\Repository\Cacheable\Concerns;

/**
 * Trait Collections
 *
 * @package Imovs\Repository\Cacheable\Concerns
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
        return $this->retrieve('lists', func_get_args());
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
        return $this->retrieve('pluck', func_get_args());
    }
}
