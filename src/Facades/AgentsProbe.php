<?php

namespace GreyZero\WebCallCenter\Facades;

use Illuminate\Support\Facades\Facade;

class AgentsProbe extends Facade{
    /**
     * @inheritdoc
     */
    protected static function getFacadeAccessor(){
        return 'agents-probe';
    }
}
