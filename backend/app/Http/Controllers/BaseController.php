<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseController extends Controller
{

    public function __construct(protected $repository)
    {
    }


    /**
     * Display a listing of the resource.
     *
     * @return  App\Helpers\JsonResponse
     * @auth A.Soliman
     */
    protected function getModels($resourceCollection, $columns = ['*'], $search, $options)
    {
        try {
            $result = $this->repository->retrieve($columns, $search, $options);
            $data = new LengthAwarePaginator(
                $resourceCollection::collection($result['data']),
                $result['total'],
                $options['pagination']['perPage'] ?? 10,
                $options['pagination']['page'] ?? 1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            return JsonResponse::respondSuccess('retrieve_success_message', $data);
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Store resource.
     *
     * @return  App\Helpers\JsonResponse
     * @auth A.Soliman
     */
    protected function storeModel($payload)
    {
        try {
            $this->repository->create($payload);
            return JsonResponse::respondSuccess('saved_success_message');
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Display a resource.
     *
     * @return  App\Helpers\JsonResponse
     * @auth A.Soliman
     */
    protected function showModel($resource, $model)
    {
        try {
            return JsonResponse::respondSuccess('retrieve_success_message', new $resource($model));
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Update resource.
     *
     * @return  App\Helpers\JsonResponse
     * @auth A.Soliman
     */
    protected function updateModel($payload, $id)
    {
        try {
            $this->repository->update($payload, $id);
            return JsonResponse::respondSuccess('update_success_message');
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Delete resource.
     *
     * @return  App\Helpers\JsonResponse
     * @auth A.Soliman
     */
    protected function destroyModel($id)
    {
        try {
            $this->repository->delete($id);
            return JsonResponse::respondSuccess('delete_success_message');
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

}
