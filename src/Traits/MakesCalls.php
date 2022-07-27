<?php

namespace GreyZero\WebCallCenter\Traits;

trait MakesCalls{
    /**
     * The calls that the customer has initiated.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls(){
        return $this->hasMany(\GreyZero\WebCallCenter\Models\Call::class);
    }

    /**
     * The agents that the customer has called.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function agents(){
        return $this->morphedByMany(config('web-call-center.agent_model'), 'agent', 'calls', 'customer_id')
            ->withPivot(['started_at', 'ended_at'])->as('calls');
    }

    /**
     * Makes a call request to a free agent within the given organization.
     *
     * @param \GreyZero\WebCallCenter\Models\Organization|int $organization The instance or ID of the organization to be called.
     * @return void
     */
    public function call($organization){

    }
}
