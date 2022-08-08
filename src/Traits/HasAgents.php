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
     * All calls received by this organization.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function calls(){
        return $this->hasManyThrough(\GreyZero\WebCallCenter\Models\Call::class, \GreyZero\WebCallCenter\Models\Agent::class);
    }

    /**
     * Gets the least occupied agent in the organization i.e. the agent with the least pending calls - if exists.
     *
     * @throws \Exception
     * @return \GreyZero\WebCallCenter\Models\Agent|null
     */
    public function getLeastOccupiedAgentAttribute(){
        $onlineAgentIds = \AgentsProbe::getOnlineAgents($this->id);
        return $this->agents()->withCount(['calls' => fn($calls) => $calls->whereNull('ended_at')])->orderBy('calls_count')->first();
    }

    /**
     * Gets the most occupied agent in the organization i.e. the agent with the most pending calls - if exists.
     *
     * @throws \Exception
     * @return \GreyZero\WebCallCenter\Models\Agent|null
     */
    public function getMostOccupiedAgentAttribute(){
        return $this->agents()->withCount(['calls' => fn($calls) => $calls->whereNull('ended_at')])->having('calls_count', '>', 1)
            ->orderBy('calls_count', 'desc')->first();
    }
}
