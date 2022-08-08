<?php

namespace GreyZero\WebCallCenter\Traits;

use GreyZero\WebCallCenter\Models\Call;
use Illuminate\Notifications\Notifiable;

trait ReceivesCalls{
    use EndsCalls, Notifiable;

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn(){
        return "agent.{$this->id}";
    }

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
        return $this->hasMany(Call::class, 'agent_id');
    }

    /**
     * Picks up -answers- the given call for this agent.
     *
     * @param Call|int $call The instance or the ID of the call to be answered.
     * @return Call
     */
    public function pickUp($call){
        if(!$call instanceof Call)
            $call = Call::find($call);
        $call->update(['started_at' => now()]);
        return $call;
    }
}
