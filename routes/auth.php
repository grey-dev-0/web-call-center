<?php

Route::get('login', 'AuthController@getLogin')->name('wcc.login');
Route::post('login', 'AuthController@postLogin');
Route::get('logout', 'AuthController@getLogout')->name('wcc-logout');

Broadcast::channel('agent.{id}', fn($agent, $id) => $agent->id == $id);
Broadcast::channel('customer.{id}', fn($customer, $id) => $customer->id == $id);
