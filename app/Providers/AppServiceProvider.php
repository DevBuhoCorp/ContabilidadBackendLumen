<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {
        if (true) {
            \DB::listen(function ($query) {
                \Log::info(
                    $query->sql, $query->bindings, $query->time
                );
            });
        }
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
