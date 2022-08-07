<?php

namespace GreyZero\WebCallCenter\Listeners;

class ConcludedCall{
    /**
     * Handle the event.
     *
     * @param \GreyZero\WebCallCenter\Events\CallEnded $event
     * @return void
     */
    public function handle(\GreyZero\WebCallCenter\Events\CallEnded $event){
        if(auth()->user()->authenticatable_type == 'customer')
            $event->call->agent->notify(new \GreyZero\WebCallCenter\Notifications\HungUpCall($event->call));
    }
}
