<?php

namespace App\Repositories\Eloquent;

use App\Models\Todo;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\ITodoRepository;


class TodoRepository extends BaseRepository implements ITodoRepository
{
    public function model()
    {
        return Todo::class;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return auth()->user()->todos()->create($data);
    }
}
