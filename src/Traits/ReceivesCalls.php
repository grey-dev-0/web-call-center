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
     * Picks up -answers- the given call for this agent.
     *
     * @param \GreyZero\WebCallCenter\Models\Call|int $call The instance or the ID of the call to be answered.
     * @return void
     */
    public function pickUp($call){

    }
}
