<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('user-index', 'App\Policies\UserPolicy@index');
        $gate->define('user-admin', 'App\Policies\UserPolicy@admin');
        $gate->define('user-show', 'App\Policies\UserPolicy@show');
        $gate->define('user-inbox', 'App\Policies\UserPolicy@inbox');
        $gate->define('user-outbox', 'App\Policies\UserPolicy@outbox');
        $gate->define('user-draftbox', 'App\Policies\UserPolicy@draftbox');
        $gate->define('user-update', 'App\Policies\UserPolicy@update');
        $gate->define('user-destroy', 'App\Policies\UserPolicy@destroy');
    }
}
