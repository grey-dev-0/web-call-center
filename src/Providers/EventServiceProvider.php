<?php

namespace GreyZero\WebCallCenter\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider{
    /**
     * @inheritdoc
     */
    protected $listen = [
        \GreyZero\WebCallCenter\Events\CallCreated::class => []
    ];
}
