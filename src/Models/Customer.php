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

        parent::__construct($attributes);
    }
}
