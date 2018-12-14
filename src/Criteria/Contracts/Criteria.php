<?php

namespace Imovs\Repository\Criteria\Contracts;

use Illuminate\Database\Eloquent\Model;
use Imovs\Repository\Eloquent\Contracts\Repository;

/**
 * Interface Criteria
 *
 * @package \Imovs\Repository\Criteria\Contracts
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
interface Criteria
{
    /**
     * Apply criteria in query repository
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \Imovs\Repository\Eloquent\Contracts\Repository $repository
     *
     * @return mixed
     */
    public function apply(Model $model, Repository $repository);
}
