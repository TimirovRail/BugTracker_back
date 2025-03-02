<?php

namespace App\Policies;

use App\Models\User;

class BugPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function create(User $user)
    {
        // Разрешить создание ошибки только пользователям с определенной ролью
        return $user->role === 'admin' || $user->role === 'developer';
    }
}
