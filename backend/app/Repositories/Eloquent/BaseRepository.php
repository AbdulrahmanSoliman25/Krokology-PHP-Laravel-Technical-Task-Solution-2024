<?php

namespace App\Repositories\Eloquent;

use App\Exports\ModelExport;
use App\Helpers\Constants;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;
use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Maatwebsite\Excel\Facades\Excel;

abstract class BaseRepository implements IBaseRepository
{
    /**
     * @var App
     */
    private $app;

    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     * @param App $app
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Retrieve records with optional filtering, ordering, pagination, and search.
     *
     * @param array|null $columns
     * @param array $search
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    public function retrieve($columns = ['*'], $search, $options)
    {
        $query = $this->buildQuery($columns);

        if ($search && is_array($search)) {
            // Apply search criteria
            $this->applySearchCriteria($query, $search);
        }
        if ($options['order'] && is_array($options['order'])) {
            // Apply ordering
            $this->applyOrdering($query, $options['order']);
        }

        // Return paginated or regular results
        return $this->getResults($query, $options['pagination']);
    }

    /**
     * Build the query with specified columns, eager loading, and filters.
     *
     * @param array|null $columns
     * @param array $conditions
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function buildQuery($columns = ['*'])
    {
        return $this->model->where("user_id", auth()->user()->id)->select($columns);
    }

    /**
     * Apply search criteria to the query.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param array|null $search
     * @return void
     */
    protected function applySearchCriteria($query, $search)
    {
        $query->where(function ($query) use ($search) {
            foreach ($search as $criteria) {
                if (!empty($criteria['column']) && !empty($criteria['term'])) {
                    $query->orWhere($criteria['column'], 'LIKE', "{$criteria['term']}%");
                }
            }
        });
    }

    /**
     * Apply full-text search to the query.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param string|null $searchTerm
     * @param array $columns
     * @return void
     */
    protected function applyFullTextSearch($query, $searchTerm, $columns)
    {
        $query->where(function ($query) use ($searchTerm, $columns) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', "%$searchTerm%");
            }
        });
    }

    /**
     * Apply ordering to the query.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return void
     */
    protected function applyOrdering($query, $options)
    {
        foreach ($options as $option) {
            $query->orderBy($option['by'], $option['direction']);
        }
    }

    /**
     * Get results from the query.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param bool|int $paginate
     * @param int $perPage
     * @return mixed
     */
    protected function getResults($query, $pagination)
    {
        $total = $query->count();
        $data = $pagination['status'] ? $query->skip(($pagination['page'] - 1) * $pagination['perPage'])
            ->take($pagination['perPage'])->get()
            : $query->get();

        return ['total' => $total, 'data' => $data];
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param mixed $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * @param mixed $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param mixed $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return $this->model->select($columns)->where("user_id", auth()->user()->id)->where('id', $id)->first();
    }
    /**
     * @param string $attribute
     * @param mixed $value
     * @param array $relations
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = ['*'])
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * Specify Model class name.
     *
     * @return string
     */
    abstract function model();

    /**
     * Get search columns from the model.
     *
     * @return array
     */
    protected function getModelSearchColumns()
    {
        return $this->model::SEARCH_COLUMNS;
    }
    /**
     * @return Model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function makeModel(): Model
    {
        $model = $this->app->make($this->model());
        return $this->model = $model;
    }
}
