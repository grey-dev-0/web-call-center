<?php

namespace GreyZero\WebCallCenter\Listeners;

class IncomingCall{
    /**
     * Handle the event.
     *
     * @param \GreyZero\WebCallCenter\Events\CallCreated $event
     * @return void
     */
    public function handle(\GreyZero\WebCallCenter\Events\CallCreated $event){
        $event->call->agent->notify();
    }
}
