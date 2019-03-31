<?php

namespace Imovs\Repository\Searchable;

/**
 * Trait SearchableRepository
 *
 * @package Imovs\Repository\Cacheable
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
trait SearchableRepository
{
    /**
     * Get searchable fields
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSearchableFields()
    {
        if (property_exists($this, 'searchableFields')) {
            return collect((array) $this->searchableFields);
        }

        return collect([]);
    }

    /**
     * Check searchable fields
     *
     * @return array
     */
    public function hasSearchableFields()
    {
        return $this->getSearchableFields()->isNotEmpty();
    }
}
