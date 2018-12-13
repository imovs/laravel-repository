<?php

namespace Imovs\Repository\Eloquent\Contracts;

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
                              Update
{
    //
}
