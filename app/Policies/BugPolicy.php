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
        return $user->role === 'admin' || $user->role === 'developer';
    }
}
