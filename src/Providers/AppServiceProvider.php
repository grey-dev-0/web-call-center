<?php

namespace GreyZero\WebCallCenter\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        $this->mergeConfigFrom(__DIR__ . '/../../config/web-call-center.php', 'web-call-center');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([__DIR__.'/../../database/seeders' => database_path('seeders')], 'seeder');
    }
}
