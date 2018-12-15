<?php


Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');

Route::middleware(['auth:api'])->group(function () {
    Route::get('logout', 'UserController@logout');
//    Route::get('get-contacts', 'UserController@getContacts');
//    Route::get('get-groups', 'UserController@getGroups');
//    Route::post('send-to-group', 'UserController@sendToGroup');
    Route::post('send-message', 'UserController@sendMessage');
    Route::get('get-conversations', 'UserController@getConversations');
//    Route::post('create-group','UserController@createGroup');
});