<?php

namespace App\Http\Controllers\Todo;

use App\Helpers\JsonResponse;
use App\Models\Todo;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Todo\TodoResource;
use App\Http\Requests\Todo\SearchRequest;
use App\Http\Requests\Todo\CreateRequest;
use App\Http\Requests\Todo\UpdateRequest;
use App\Notifications\TodoNotification;
use App\Repositories\Interfaces\ITodoRepository;
use Gate;
use Illuminate\Support\Facades\Cache;

class TodoController extends BaseController
{
    /**
     * Constructor for TodoController.
     *
     * @param ITodoRepository $repository
     */
    public function __construct(ITodoRepository $repository)
    {
        parent::__construct($repository);
    }


    /**
     * Display a listing of the resource.
     *
     * @param array $columns
     * @param array $conditions
     * @param int|bool $paginate
     * @return JsonResponse
     */

    public function all(SearchRequest $request)
    {
        $validated = $request->validated();

        //TODO: $cacheKey = 'todos_' . md5(serialize($validated));
        // $cachedTodos = Cache::get($cacheKey);

        // if ($cachedTodos) {
        //     return JsonResponse::respondSuccess('fetched_success_message', $cachedTodos);
        // }

        $parameters = [
            'resourceClass' => TodoResource::class,
            'indexColumns' => Todo::INDEX_COLUMNS,
            'search' => $validated['search'] ?? [],
            'options' => [
                'order' => $validated['order'] ?? [],
                'pagination' => $validated['pagination'] ?? ['status' => false],
            ]
        ];
        //TODO: Cache::put($cacheKey, $todos, now()->addMinutes(10));
        return parent::getModels(...array_values($parameters));

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function store(CreateRequest $request)
    {

        try {
            $todo = $this->repository->create($request->validated());
            auth()->user()->notify(instance: new TodoNotification($todo, 'created'));
            //TODO: Cache::forget('todos_*');
            return JsonResponse::respondSuccess('saved_success_message');
        } catch (\Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Todo $Todo
     * @return JsonResponse
     */
    public function show(Todo $resource)
    {
        Gate::authorize('view', $resource);
        return parent::showModel(TodoResource::class, $resource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Todo $Todo
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Todo $resource)
    {
        Gate::authorize('update', $resource);
        auth()->user()->notify(instance: new TodoNotification($resource, 'updated'));
        
        //TODO: Cache::forget('todos_*');
        return parent::updateModel($request->validated(), $resource->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Todo $Todo
     * @return JsonResponse
     */
    public function destroy(Todo $resource)
    {
        Gate::authorize('delete', $resource);
        return parent::destroyModel($resource->id);
    }


}
