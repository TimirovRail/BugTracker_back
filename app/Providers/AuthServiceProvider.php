<?php

namespace App\Providers;


// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Bug;
use App\Policies\BugPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Bug::class => BugPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
