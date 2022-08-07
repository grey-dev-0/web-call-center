<?php

namespace GreyZero\WebCallCenter\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider{
    /**
     * @inheritdoc
     */
    protected $listen = [
        \GreyZero\WebCallCenter\Events\CallCreated::class => [
            \GreyZero\WebCallCenter\Listeners\IncomingCall::class
        ],
        \GreyZero\WebCallCenter\Events\CallEnded::class => [
            \GreyZero\WebCallCenter\Listeners\ConcludedCall::class,
            \GreyZero\WebCallCenter\Listeners\RearrangeCallsQueue::class
        ]
    ];
}
