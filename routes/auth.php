<?php

Route::get('login', 'AuthController@getLogin');
Route::post('login', 'AuthController@postLogin');
