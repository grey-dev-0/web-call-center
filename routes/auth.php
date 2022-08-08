<?php

Route::get('login', 'AuthController@getLogin')->name('wcc.login');
Route::post('login', 'AuthController@postLogin');
Route::get('logout', 'AuthController@getLogout')->name('wcc-logout');

Broadcast::channel('agent.{id}', fn($user, $id) => $user->authenticatable_type == 'agent' && $user->authenticatable->id == $id);
Broadcast::channel('customer.{id}', fn($user, $id) => $user->authenticatable_type == 'customer' && $user->authenticatable->id == $id);
Broadcast::channel('organization.{id}', fn($user, $id) => ($user->authenticatable->organization_id == $id)?
    ['id' => $user->authenticatable_id, 'name' => $user->authenticatable->name] : false);
