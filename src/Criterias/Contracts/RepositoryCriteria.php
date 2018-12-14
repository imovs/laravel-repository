<?php

namespace Imovs\Repository\Criterias\Contracts;

/**
 * Interface RepositoryCriteria
 *
 * @package \Imovs\Repository\Criterias\Contracts
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
interface RepositoryCriteria
{
    /**
     * Get Collection of Criteria
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCriterias();

    /**
     * Push Criteria for filter the query
     *
     * @param $criteria
     *
     * @return $this
     */
    public function pushCriteria($criteria);

    /**
     * Pop Criteria
     *
     * @param $criteria
     *
     * @return $this
     */
    public function popCriteria($criteria);

    /**
     * Find data by Criteria
     *
     * @param Criteria $criteria
     *
     * @return mixed
     */
    public function getByCriteria(Criteria $criteria);

    /**
     * Skip Criteria
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipCriteria($status = true);

    /**
     * Reset all Criterias
     *
     * @return $this
     */
    public function resetCriteria();
}
