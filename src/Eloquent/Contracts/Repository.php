<?php

namespace Imovs\Repository\Eloquent\Contracts;

use Imovs\Repository\Criteria\Contracts\RepositoryCriteria;

/**
 * Interface Repository
 *
 * @package Imovs\Repository\Eloquent\Contracts
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
interface Repository extends  Collection,
                              Create,
                              Delete,
                              Find,
                              Query,
                              Update,
                              RepositoryCriteria
{
    //
}
