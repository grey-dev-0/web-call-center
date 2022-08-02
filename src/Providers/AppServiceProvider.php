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
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'wcc');

        $this->publishes([__DIR__.'/../../database/seeders' => database_path('seeders')], 'seeder');
        $this->publishes([__DIR__.'/../../resources/views' => base_path('resources/views/vendor/wcc')], 'views');
        $this->publishes([__DIR__.'/../../public' => public_path('vendor/wcc')], 'assets');

        \Illuminate\Database\Eloquent\Relations\Relation::enforceMorphMap([
            'agent' => \GreyZero\WebCallCenter\Models\Agent::class,
            'customer' => \GreyZero\WebCallCenter\Models\Customer::class
        ]);

        if(config('web-call-center.middleware') == 'wcc'){
            config(['auth.providers.users' => [
                'driver' => 'eloquent',
                'model' => \GreyZero\WebCallCenter\Models\User::class
            ]]);
            $this->registerMiddleware();
        }
        $this->registerRoutes();
    }

    private function registerMiddleware(){
        \Route::aliasMiddleware('wcc', \GreyZero\WebCallCenter\AppMiddleware::class);
    }

    private function registerRoutes(){
        $namespace = 'GreyZero\WebCallCenter\Controllers';
        $router = \Route::middleware(config('web-call-center.middleware'));
        if(!is_null($prefix = config('web-call-center.prefix')))
            $router->prefix($prefix);
        $router->namespace($namespace)->group(__DIR__.'/../../routes/app.php');
        \Route::group((!empty($prefix)? compact('prefix') : []) + compact('namespace'), __DIR__.'/../../routes/auth.php');
    }
}
