<?php

namespace LaravelBundle\Repository\Presenter;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use LaravelBundle\Repository\Presenter\Contracts\Presenter as PresenterContract;

/**
 * Class Presenter
 *
 * @package LaravelBundle\Repository\Presenter
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
abstract class Presenter implements PresenterContract
{
    /**
     * @var
     */
    protected $resource = null;

    /**
     * Specify resource class
     *
     * @return string
     */
    abstract public function resource();

    /**
     * Prepare data to present
     *
     * @param $data
     *
     * @return Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function present($data)
    {
        $resource = $this->resource();

        if ($data instanceof Collection || $data instanceof AbstractPaginator) {
            return $resource::collection($data);
        }

        return new $resource($data);
    }
}
