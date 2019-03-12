<?php

namespace Imovs\Repository\Cacheable\Model;

use Imovs\Repository\Cacheable\Model\Contracts\Cacheable as Model;

class Observer
{
    /**
     * Tell the cacheable service to flush all cache
     * that related to the given model.
     *
     * @param \Suitmedia\Cacheable\Contracts\Cacheable $model
     *
     * @return void
     */
    protected function flushCache(Model $model)
    {
        \Cacheable::flush($model->cacheTags());
    }

    /**
     * Saved event handler.
     *
     * @param \Suitmedia\Cacheable\Contracts\Cacheable $model
     *
     * @return void
     */
    public function saved(Model $model)
    {
        $this->flushCache($model);
    }

    /**
     * Saved event handler.
     *
     * @param \Suitmedia\Cacheable\Contracts\Cacheable $model
     *
     * @return void
     */
    public function created(Model $model)
    {
        $this->flushCache($model);
    }

    /**
     * Saved event handler.
     *
     * @param \Suitmedia\Cacheable\Contracts\Cacheable $model
     *
     * @return void
     */
    public function updated(Model $model)
    {
        $this->flushCache($model);
    }

    /**
     * Deleted event handler.
     *
     * @param \Suitmedia\Cacheable\Contracts\Cacheable $model
     *
     * @return void
     */
    public function deleted(Model $model)
    {
        $this->flushCache($model);
    }

    /**
     * Restored event handler.
     *
     * @param \Suitmedia\Cacheable\Contracts\Cacheable $model
     *
     * @return void
     */
    public function restored(Model $model)
    {
        $this->flushCache($model);
    }
}
