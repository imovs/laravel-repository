<?php

namespace Imovs\Repository\Eloquent\Contracts;

/**
 * Interface Delete
 *
 * @package Imovs\Repository\Eloquent\Contracts
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
interface Delete
{
    /**
     * Delete a entity by id
     *
     * @param $id
     * @return bool
     */
    public function delete($id);

    /**
     * Delete multiple entities.
     *
     * @param array $where
     * @return bool
     */
    public function deleteWhere(array $where);
}
