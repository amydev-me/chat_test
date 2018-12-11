<?php


Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
//
Route::post('test', 'UserController@test');

Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', 'UserController@logout')->name('logout');
});