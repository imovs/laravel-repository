<?php

namespace Imovs\Repository\Eloquent\Concerns;

/**
 * Trait Deletes
 *
 * @package Imovs\Repository\Eloquent\Concerns
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
trait Deletes
{
    /**
     * Delete a entity by id
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $this->applyScope();
        $model = $this->find($id);
        $this->resetModel();

        return $model->delete();
    }

    /**
     * Delete multiple entities.
     *
     * @param array $where
     * @return bool
     */
    public function deleteWhere(array $where)
    {
        $this->applyScope();
        $this->applyConditions($where);
        $deleted = $this->model->delete();

        $this->resetModel();

        return $deleted;
    }
}
