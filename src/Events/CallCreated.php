<?php

namespace GreyZero\WebCallCenter\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallCreated{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param \GreyZero\WebCallCenter\Models\Call $call The call that has just been requested by a customer.
     * @return void
     */
    public function __construct(public \GreyZero\WebCallCenter\Models\Call $call){}
}
