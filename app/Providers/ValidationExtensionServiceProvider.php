<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;

class ValidationExtensionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Create additional rules for the application
         * See App\Repositories\Repository for SELECT rules
         */
        Validator::extend('fields', 'App\Validations\ValidationRules@validateFields');
        Validator::extend('sort', 'App\Validations\ValidationRules@validatesort');
        Validator::extend('where', 'App\Validations\ValidationRules@validateWhere');
        Validator::extend('between_select', 'App\Validations\ValidationRules@validateBetween');
        Validator::extend('limit', 'App\Validations\ValidationRules@validateLimit');
        Validator::extend('skip', 'App\Validations\ValidationRules@validateSkip');
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
