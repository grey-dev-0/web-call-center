<?php

namespace GreyZero\WebCallCenter\Models;

use GreyZero\WebCallCenter\Traits\HasAgents;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model{
    use HasAgents;

    /**
     * @inheritdoc
     */
    protected $guarded = [];
}
