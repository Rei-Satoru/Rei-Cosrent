<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom validation: no_spaces
        Validator::extend('no_spaces', function ($attribute, $value, $parameters, $validator) {
            return strpos($value, ' ') === false;
        });

        // Custom validation: lowercase
        Validator::extend('lowercase', function ($attribute, $value, $parameters, $validator) {
            return $value === strtolower($value);
        });
    }
}
