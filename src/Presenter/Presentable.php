<?php

namespace LaravelBundle\Repository\Presenter;

use LaravelBundle\Repository\Presenter\Contracts\Presenter;
use LaravelBundle\Repository\Exceptions\RepositoryException;

trait Presentable
{
    /**
     * Presenter
     *
     * @var \LaravelBundle\Repository\Presenter\Contracts\Presenter
     */
    protected $presenter;

    /**
     * Transform data
     *
     * @return Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    abstract public function presentable();

    /**
     * Specify presenter class
     *
     * @return string|null
     */
    public function presenter()
    {
        return null;
    }

    /**
     * Set Presenter
     *
     * @param $presenter
     *
     * @return $this
     */
    public function setPresenter(Presenter $presenter)
    {
        $this->makePresenter($presenter);

        return $this;
    }

    /**
     * @param Presenter $presenter
     *
     * @return \LaravelBundle\Repository\Presenter\Contracts\Presenter|null
     * @throws \LaravelBundle\Repository\Exceptions\RepositoryException
     */
    public function makePresenter(?Presenter $presenter)
    {
        $presenter =  $presenter ?? $this->presenter();

        if (! is_null($presenter)) {
            $this->presenter = is_string($presenter) ? $this->app->make($presenter) : $presenter;

            if (! $this->presenter instanceof Presenter) {
                throw new RepositoryException(
                    "Class {$presenter} must be an instance of LaravelBundle\\Repository\\Presenter\\Contracts\\Presenter"
                );
            }

            return $this->presenter;
        }

        return null;
    }
}
