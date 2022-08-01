<?php

namespace GreyZero\WebCallCenter\Models;

use GreyZero\WebCallCenter\Traits\ReceivesCalls;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model{
    use ReceivesCalls;

    /**
     * @inheritdoc
     */
    protected $guarded = [];

    /**
     * @inheritdoc
     */
    public function __construct(array $attributes = []){
        $this->table = config('web-call-center.tables_prefix').'_agents';
        $this->incrementing = config('web-call-center.incremental_primary_keys.agents');

        parent::__construct($attributes);
    }
}
