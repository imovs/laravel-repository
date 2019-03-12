<?php

namespace Imovs\Repository\Cacheable\Contracts;

/**
 * Interface Cacheable
 *
 * @package Imovs\Repository\Cacheable\Contracts
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
interface Cacheable extends Collection,
                            Find
{
    /**
     * Sync relations
     *
     * @param $id
     * @param $relation
     * @param $attributes
     * @return mixed
     */
    public function retrieve($method, $args);

}
