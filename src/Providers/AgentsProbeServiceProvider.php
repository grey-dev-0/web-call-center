<?php

namespace GreyZero\WebCallCenter\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class AgentsProbeServiceProvider extends ServiceProvider implements DeferrableProvider{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(){
        $this->app->singleton('agents-probe', fn() => new \GreyZero\WebCallCenter\Services\AgentsProbe(new \Pusher\Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options'),
        )));
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(){
        //
    }

    /**
     * @inheritdoc
     */
    public function provides(){
        return ['agents-probe'];
    }
}
