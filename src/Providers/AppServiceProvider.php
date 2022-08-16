<?php

namespace GreyZero\WebCallCenter\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider{
    /**
     * @var string $namespace Router's namespace of the web call center application.
     */
    private $namespace = 'GreyZero\WebCallCenter\Controllers';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        $this->mergeConfigFrom(__DIR__.'/../../config/web-call-center.php', 'web-call-center');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'wcc');

        if($this->app->runningInConsole()){
            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
            $this->publishes([__DIR__.'/../../config/web-call-center.php' => config_path('web-call-center.php')], 'config');
            $this->publishes([__DIR__.'/../../database/seeders' => database_path('seeders')], 'seeder');
            $this->publishes([__DIR__.'/../../resources/views' => base_path('resources/views/vendor/wcc')], 'views');
            $this->publishes([__DIR__.'/../../public' => public_path('vendor/wcc')], 'assets');
        }

        \Illuminate\Database\Eloquent\Relations\Relation::enforceMorphMap([
            'agent' => config('web-call-center.agent_model'),
            'customer' => config('web-call-center.customer_model')
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

    /**
     * Registers web call center routes for web and external API users e.g. mobile applications.
     *
     * @return void
     */
    private function registerRoutes(){
        $this->registerApplicationRoutes();
        $this->registerApplicationRoutes(false);
        $this->registerAuthenticationRoutes();
    }

    /**
     * Registers user authentication routes for accessing web call center.
     *
     * @return void
     */
    private function registerAuthenticationRoutes(){
        $prefix = config('web-call-center.prefix');
        \Route::group((!empty($prefix)? compact('prefix') : []) + ['namespace' => $this->namespace, 'middleware' => 'web'],
            __DIR__.'/../../routes/auth.php');
    }

    /**
     * Registers the application routes of web call center.
     *
     * @param bool $web Determines whether to register application's routes for web or external API usage.
     * @return void
     */
    private function registerApplicationRoutes($web = true){
        $prefix = config('web-call-center.prefix');
        if($web)
            $middleware = ['web', config('web-call-center.middleware')];
        else{
            $middleware = ['api', 'auth:sanctum'];
            $prefix = is_null($prefix)? 'api' : "api/$prefix";
        }
        $router = \Route::middleware($middleware);
        if(!is_null($prefix))
            $router->prefix($prefix);
        $router->namespace($this->namespace)->group(__DIR__.'/../../routes/app.php');
    }
}
