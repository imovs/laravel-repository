<?php

namespace Imovs\Repository\Searchable;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Imovs\Repository\Searchable\Contracts\Searchable;
use Imovs\Repository\Criteria\Contracts\Criteria as CriteriaContract;
use Imovs\Repository\Eloquent\Contracts\Repository as RepositoryContract;

/**
 * Trait SearchableCriteria
 *
 * @package Imovs\Repository\Criteria
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
class SearchableCriteria implements CriteriaContract
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var Builder|Model
     */
    protected $model;

    /**
     * @var \Imovs\Repository\Eloquent\Contracts\Repository
     */
    protected $repository;

    /**
     * Apply criteria in query repository
     *
     * @param   Builder|Model     $model
     * @param   RepositoryContract $repository
     *
     * @return mixed
     * @throws \Exception
     */
    public function apply($model, RepositoryContract $repository)
    {
        $this->request    = app(Request::class);
        $this->model        = $model;
        $this->repository  = $repository;

        if (!$repository instanceof Searchable) {
            return $model;
        }

        return $this
            ->search()
            ->order()
            ->with()
            ->get();
    }

    /**
     * Filters
     *
     * @return $this
     */
    protected function search()
    {
        $search = $this->request->get(config('repository.searchable.params.search'), null);

        if ($search && $this->repository->hasSearchableFields()) {

            $search = $this->resolveParams($search);

            $search = collect($search);
            $fields = $this->repository->getSearchableFields();

            $this->model = $this->model->where(function ($query) use ($search, $fields) {

                foreach ($fields as $field) {
                    if ($search->has($field) && $value = $search->get($field)) {

                        if (!is_array($value) && stripos($value, ',')) {
                            $value = explode(',', $value);
                        }

                        if (is_array($value)) {
                            $query->whereIn($field, $value);
                        } else if (is_string($value) && !ctype_digit($value)) {
                            $query->where($field, 'like', "%$value%");
                        } else {
                            $query->where($field, $value);
                        }
                    }
                }

                return $query;
            });
        }

        return $this;
    }

    /**
     * Order
     *
     * @return $this
     */
    protected function order()
    {
        $order = $this->request->get(config('repository.searchable.params.order'), null);
        $sort = $this->request->get(config('repository.searchable.params.sort'), 'asc');

        if ($order) {
            $this->model = $this->model->orderBy($order, $sort);
        }

        return $this;
    }

    /**
     * With relationships
     *
     * @return $this
     */
    protected function with()
    {
        $with = $this->request->get(config('repository.searchable.params.with'), null);

        if ($with) {
            $with = explode(';', $with);

            $this->model = $this->model->with($with);
        }

        return $this;
    }

    /**
     * Get query builder
     *
     * @return Builder|Model
     */
    public function get()
    {
        return $this->model;
    }

    /**
     * Resolve params
     *
     * @param  array|string $params
     * @return array
     */
    private function resolveParams($params)
    {
        if (is_string($params) && (stripos($params, ';') || stripos($params, ':'))) {
            $params = explode(';', $params);

            $search = [];

            foreach ($params as $param) {
                list($key, $value) = explode(':', $param);

                $search[$key] = $value;
            }

            return $search;
        }

        return $params;
    }
}
