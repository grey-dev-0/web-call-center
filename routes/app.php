<?php

Route::group(['prefix' => 'agent'], function(){
    Route::get('/', 'AgentsController@getIndex')->name('wcc-agent');
    Route::get('customers', 'AgentsController@getCustomers');
});

Route::group(['prefix' => 'customer'], function(){
    Route::get('/', 'CustomersController@getIndex')->name('wcc-customer');
    Route::get('organizations', 'CustomersController@getOrganizations');
    Route::post('call', 'CustomersController@postCall');
});
