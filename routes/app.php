<?php

Route::group(['prefix' => 'agent'], function(){
    Route::get('/', 'AgentsController@getIndex');
});
