<?php

namespace Imovs\Repository\Cacheable\Model;

trait Cacheable
{
    /**
     * Boot the Cacheable trait by attaching
     * a new observer to the current model.
     *
     * @return void
     */
    public static function bootCacheable()
    {
        static::observe(app(Observer::class));
    }

    /**
     * Generate cache tags automatically
     * based on the model class name.
     *
     * @return array
     */
    public function cacheTags()
    {
        return [ class_basename(get_class($this)) ];
    }
}
