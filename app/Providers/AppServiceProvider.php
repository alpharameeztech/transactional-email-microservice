<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->bind('SendGrid', function ($app) {
            return new \App\Interfaces\EmailInterface\Implementations\SendGrid(env('SENDGRID_API_KEY'));
        });
    }
}
