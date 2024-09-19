<?php

namespace App\Policies;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TodoPolicy
{
    /**
     * Determine if the authenticated user can view the Todo.
     */
    public function view(User $user, Todo $todo)
    {
        return $user->id === $todo->user_id;
    }

    /**
     * Determine if the authenticated user can update the Todo.
     */
    public function update(User $user, Todo $todo)
    {
        return $user->id === $todo->user_id;
    }

    /**
     * Determine if the authenticated user can delete the Todo.
     */
    public function delete(User $user, Todo $todo)
    {
        return $user->id === $todo->user_id;
    }
}
