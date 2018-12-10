<?php


Route::post('login', 'UserController@login');
//
Route::post('test', 'UserController@test');
//
//Route::middleware(['auth:api'])->group(function () {
//    Route::get('list','UserController@getData');
//    Route::post('logout', 'UserController@logout')->name('logout');
//});