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
}
