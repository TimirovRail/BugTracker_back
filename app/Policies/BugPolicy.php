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
        // Разрешаем создание только менеджерам или администраторам
        return in_array($user->role, ['manager', 'admin']);
    }
}
