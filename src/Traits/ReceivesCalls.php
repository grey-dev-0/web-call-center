<?php

namespace GreyZero\WebCallCenter\Traits;

trait ReceivesCalls{
    /**
     * The organization that the calls receiving agent belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization(){
        return $this->belongsTo(config('web-call-center.organization_model'),
            config('web-call-center.organization_foreign_key'));
    }

    /**
     * Call received by this agent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls(){
        return $this->hasMany(\GreyZero\WebCallCenter\Models\Call::class, 'agent_id');
    }

    /**
     * Picks up -answers- the given call for this agent.
     *
     * @param \GreyZero\WebCallCenter\Models\Call|int $call The instance or the ID of the call to be answered.
     * @return void
     */
    public function pickUp($call){

    }
}
