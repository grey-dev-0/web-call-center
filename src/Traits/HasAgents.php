<?php

namespace GreyZero\WebCallCenter\Traits;

trait HasAgents{
    /**
     * Agents who belong to this ogranization.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agents(){
        return $this->hasMany(config('web-call-center.agent_model'), config('web-call-center.organization_foreign_key'));
    }

    /**
     * Gets the least occupied agent in the organization i.e. the agent with the least pending calls - if exists.
     *
     * @throws \Exception
     * @return \GreyZero\WebCallCenter\Models\Agent|null
     */
    public function getLeastOccupiedAgentAttribute(){
        return (cache()->has($key = "wcc-o-{$this->id}-min"))? $this->agents()->find($key) :
            $this->agents()->withCount(['calls' => fn($calls) => $calls->whereNull('ended_at')])->orderBy('calls_count')->first();
    }
}
