<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('mac', function ($attribute, $value, $parameters, $validator) {
            return (preg_match('/^([a-fA-F0-9]{2}[:|\-]?){5}[a-fA-F0-9]{2}$/', $value) == 1);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
