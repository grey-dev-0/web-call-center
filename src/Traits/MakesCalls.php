<?php

namespace GreyZero\WebCallCenter\Traits;

trait MakesCalls{
    use EndsCalls;

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn(){
        return "customer.{$this->id}";
    }

    /**
     * The calls that the customer has initiated.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls(){
        return $this->hasMany(\GreyZero\WebCallCenter\Models\Call::class, 'customer_id');
    }

    /**
     * The agents that the customer has called.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function agents(){
        return $this->belongsToMany(config('web-call-center.agent_model'), 'calls', 'customer_id', 'agent_id')
            ->withPivot(['started_at', 'ended_at'])->as('calls');
    }

    /**
     * Adds this customer to the least occupied agent's queue of the given organization.
     *
     * @param int $organization Ther ID of the organization to be called.
     * @throws \Exception
     * @return ?\GreyZero\WebCallCenter\Models\Call
     */
    public function enqueue($organization){
        $organizationClass = config('web-call-center.organization_model');
        $organization = $organizationClass::find($organization);

        // Locking the least occupied agent caching to ensure its accuracy with simultaneous call requests.
        $semaphore = cache()->lock("wcc-o-{$organization->id}", 5);
        while(!$semaphore->get())
            usleep(25000);
        $agent_id = $organization->leastOccupiedAgent->id?? null;

        // Calls queueing is included within the locked process to avoid improper least occupied agent identification
        // by other processes or call requests.
        if(!is_null($agent_id) && is_null($call = $organization->calls()->whereCustomerId($this->id)->whereNull('ended_at')->first()))
            $call = $this->calls()->create(compact('agent_id'));
        $semaphore->forceRelease();
        return $call?? null;
    }
}
