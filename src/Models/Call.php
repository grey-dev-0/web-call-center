<?php

namespace GreyZero\WebCallCenter\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model{
    /**
     * @inheritdoc
     */
    protected $guarded = [];

    /**
     * @inheritdoc
     */
    protected $dates = ['started_at', 'ended_at'];

    /**
     * @inheritdoc
     */
    protected $dispatchesEvents = [
        'created' => \GreyZero\WebCallCenter\Events\CallCreated::class
    ];

    /**
     * @inheritdoc
     */
    public function __construct(array $attributes = []){
        $this->table = config('web-call-center.tables_prefix').'_calls';

        parent::__construct($attributes);
    }

    /**
     * The customer who initiated the call.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(){
        return $this->belongsTo(config('web-call-center.customer_model'));
    }

    /**
     * The agent who'd get the call.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent(){
        return $this->belongsTo(config('web-call-center.agent_model'));
    }
}
