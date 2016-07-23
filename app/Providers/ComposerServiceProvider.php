<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{  
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $shared = [];

            if ($user = session('weblogin')) {

                $user->load('handleable');

                $shared['user'] = $user;
                $shared['skin'] = 'skin-red';
            }
            
            $view->with($shared);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
