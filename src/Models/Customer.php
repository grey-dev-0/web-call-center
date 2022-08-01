<?php

namespace GreyZero\WebCallCenter\Models;

use GreyZero\WebCallCenter\Traits\MakesCalls;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model{
    use MakesCalls;

    /**
     * @inheritdoc
     */
    protected $guarded = [];

    /**
     * @inheritdoc
     */
    public function __construct(array $attributes = []){
        $this->table = config('web-call-center.tables_prefix').'_customers';
        $this->incrementing = config('web-call-center.incremental_primary_keys.customers');

        parent::__construct($attributes);
    }

    /**
     * The agent's user account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function user(){
        return $this->morphOne(User::class, 'authenticatable');
    }
}
