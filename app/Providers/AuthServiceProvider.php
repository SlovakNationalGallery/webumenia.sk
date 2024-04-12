<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('administer', function ($user) {
            return $user->can_administer;
        });

        Gate::define('edit', function ($user) {
            return $user->can_edit;
        });

        Gate::define('publish', function ($user) {
            return $user->can_publish;
        });

        Gate::define('import', function ($user) {
            return $user->can_import;
        });

        Gate::before(function ($user) {
            if ($user->can_administer) {
                return true;
            }
        });
    }
}
