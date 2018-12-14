<?php

namespace Imovs\Repository\Criteria;

use Illuminate\Support\Collection;
use Imovs\Repository\Exceptions\RepositoryException;
use Imovs\Repository\Criteria\Contracts\Criteria as CriteriaContract;

/**
 * Trait RepositoryCriteria
 *
 * @package Imovs\Repository\Criteria
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
 trait RepositoryCriteria
{
    /**
     * Collection of Criteria
     *
     * @var \Illuminate\Support\Collection
     */
    protected $criterias;

    /**
     * Skip criteria
     *
     * @var bool
     */
    protected $skipCriteria = false;


    /**
     * Get Collection of criterias
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCriterias()
    {
        return $this->criterias;
    }

    /**
     * Push Criteria for filter the query
     *
     * @param $criteria
     *
     * @return \Imovs\Repository\Eloquent\Repository
     * @throws \Imovs\Repository\Exceptions\RepositoryException
     */
    public function pushCriteria($criteria)
    {
        if (is_string($criteria)) {
            $criteria = new $criteria;
        }

        if (!$criteria instanceof CriteriaContract) {
            throw new RepositoryException("Class " . get_class($criteria) . " must be an instance of Imovs\\Repository\\Criteria\\Contracts\\Criteria");
        }

        $this->criterias->push($criteria);

        return $this;
    }

    /**
     * Pop Criteria
     *
     * @param $criteria
     *
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function popCriteria($criteria)
    {
        $this->criterias = $this->criterias->reject(function ($item) use ($criteria) {
            if (is_object($item) && is_string($criteria)) {
                return get_class($item) === $criteria;
            }

            if (is_string($item) && is_object($criteria)) {
                return $item === get_class($criteria);
            }

            return get_class($item) === get_class($criteria);
        });

        return $this;
    }

    /**
     * Find data by Criteria
     *
     * @param \Imovs\Repository\Criteria\Contracts\Criteria $criteria
     *
     * @return \Illuminate\Support\Collection
     */
    public function getByCriteria(CriteriaContract $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);

        $data = $this->model->get();

        $this->resetModel();

        return $data;
    }

    /**
     * Skip Criteria
     *
     * @param bool $status
     *
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;

        return $this;
    }

    /**
     * Reset all Criterias
     *
     * @return \Imovs\Repository\Eloquent\Repository
     */
    public function resetCriteria()
    {
        $this->criterias = new Collection();

        return $this;
    }

    /**
     * Apply criteria in current Query
     *
     * @return \Imovs\Repository\Eloquent\Repository
     */
    protected function applyCriterias()
    {
        if ($this->skipCriteria === true) {
            return $this;
        }

        if ($this->criterias->isNotEmpty()) {
            foreach ($this->criterias as $criteria) {
                if ($criteria instanceof CriteriaContract) {
                    $this->model = $criteria->apply($this->model, $this);
                }
            }
        }

        return $this;
    }
}
