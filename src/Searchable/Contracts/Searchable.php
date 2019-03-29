<?php

namespace Imovs\Repository\Searchable\Contracts;

/**
 * Interface Searchable
 *
 * @package Imovs\Repository\Searchable\Contracts
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
interface Searchable
{
    /**
     * Get searchable fields
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSearchableFields();

    /**
     * Check searchable fields
     *
     * @return array
     */
    public function hasSearchableFields();

}
