<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Validator::extend("no_spaces", function ($attribute, $value, $parameters, $validator) {
            return strpos($value, " ") === false;
        });

        Validator::extend("lowercase", function ($attribute, $value, $parameters, $validator) {
            return $value === strtolower($value);
        });
    }
}