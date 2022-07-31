<?php

namespace GreyZero\WebCallCenter\Traits;

use GreyZero\WebCallCenter\Models\Call;

trait EndsCalls{
    /**
     * Mark the given call as ended by any of its participants.
     *
     * @param Call|int $call The call that has just been hung up.
     * @return Call|mixed
     */
    public function hangup($call){
        if(!$call instanceof Call)
            $call = Call::find($call);
        $call->update(['ended_at' => now()]);
        event(new \GreyZero\WebCallCenter\Events\CallEnded($call));
        return $call;
    }
}
