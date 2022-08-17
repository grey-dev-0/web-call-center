<?php

return function($web = true){
    return function() use($web){
        Route::group(['prefix' => 'agent'], function() use($web){
            $agentsRoute = Route::get('/', 'AgentsController@getIndex');
            if($web)
                $agentsRoute->name('wcc-agent');
            Route::get('customers', 'AgentsController@getCustomers');
            Route::get('pick/{call}', 'AgentsController@getPick');
            Route::get('hangup/{call}', 'AgentsController@getHangup');
            Route::get('rtc', 'AuthController@getRtcToken');
        });

        Route::group(['prefix' => 'customer'], function() use($web){
            $customersRoute = Route::get('/', 'CustomersController@getIndex');
            if($web)
                $customersRoute->name('wcc-customer');
            Route::get('organizations', 'CustomersController@getOrganizations');
            Route::post('call', 'CustomersController@postCall');
            Route::get('hangup/{organization}', 'CustomersController@getHangup');
            Route::get('rtc', 'AuthController@getRtcToken');
        });
    };
};
