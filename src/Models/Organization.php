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

    /**
     * @inheritdoc
     */
    public function __construct(array $attributes = []){
        $this->table = config('web-call-center.tables_prefix').'_organizations';
        $this->incrementing = config('web-call-center.incremental_primary_keys.organizations');

        parent::__construct($attributes);
    }
}
