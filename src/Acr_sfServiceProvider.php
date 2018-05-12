<?php

namespace Acr\sf;

use Acr\sf\Controllers\SfController;
use Illuminate\Support\ServiceProvider;

class Acr_sfServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__ . "/routes.php";
        $this->loadViewsFrom(__DIR__ . '/views', 'Acr_sfv');
        $this->publishes([
            __DIR__ . '/config/acr_sf.php' => config_path('acr_sf.php')
        ], 'config');
        /* $this->publishes([
             __DIR__ . '/Public/Fonts/' => base_path('/public_html/acr/blog'),
         ], 'public');*/
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('AcrSf', function () {
            return new SfController();
        });
    }
}
