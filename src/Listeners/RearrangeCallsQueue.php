<?php

namespace GreyZero\WebCallCenter\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;

class RearrangeCallsQueue implements ShouldQueue{
    /**
     * Handle the event, rearranges the calls queue of the given organization so that the oldest pending call of the busiest
     * agent is automatically re-assigned to the least occupied - or free - agent.
     *
     * @param \GreyZero\WebCallCenter\Events\CallEnded $event
     * @return void
     */
    public function handle(\GreyZero\WebCallCenter\Events\CallEnded $event){
        $organization = $event->call->agent->organization;
        if(!empty($mostOccupiedAgent = $organization->mostOccupiedAgent)){
            $oldestPendingCall = $mostOccupiedAgent->calls()->whereNull('started_at')->orderBy('created_at')->first();
            $leastOccupiedAgent = $organization->leastOccupiedAgent;
            if($leastOccupiedAgent->id != $mostOccupiedAgent->id)
                $oldestPendingCall->update(['agent_id' => $leastOccupiedAgent->id]);
        }
    }
}
