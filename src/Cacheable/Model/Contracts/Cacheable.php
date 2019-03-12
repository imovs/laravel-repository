<?php

namespace Imovs\Repository\Cacheable\Model\Contracts;

/**
 * Interface Cacheable
 *
 * @package Imovs\Repository\Cacheable\Model\Contracts
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
interface Cacheable
{
    /**
     * Return the cache tags which would be used
     * by the model and model observer.
     *
     * @return mixed
     */
    public function cacheTags();
  }
