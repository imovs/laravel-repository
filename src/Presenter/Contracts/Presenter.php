<?php

namespace LaravelBundle\Repository\Presenter\Contracts;

/**
 * Interface PresenterInterface
 *
 * @package \LaravelBundle\Repository\Presenter\Contracts
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
interface Presenter
{
    /**
     * Prepare data to present
     *
     * @param $data
     *
     * @return mixed
     */
    public function present($data);
}
