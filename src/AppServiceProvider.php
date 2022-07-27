<?php

namespace GreyZero\WebCallCenter;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        $this->mergeConfigFrom(__DIR__.'/../config/web-call-center.php', 'web-call-center');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){
        //
    }
}
