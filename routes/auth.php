<?php

Route::get('login', 'AuthController@getLogin')->name('wcc.login');
Route::post('login', 'AuthController@postLogin');
Route::get('logout', 'AuthController@getLogout');
