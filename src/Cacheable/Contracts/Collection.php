<?php

namespace Imovs\Repository\Cacheable\Contracts;

/**
 * Interface Collection
 *
 * @package Imovs\Repository\Cacheable\Contracts
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
interface Collection
{
    /**
     * Retrieve data array for populate field select.
     *
     * @param string $column
     * @param string|null $key
     * @return array
     */
    public function lists($column, $key = null);

    /**
     * Retrieve data array for populate field select.
     *
     * @param string $column
     * @param string|null $key
     * @return \Illuminate\Support\Collection
     */
    public function pluck($column, $key = null);
}
