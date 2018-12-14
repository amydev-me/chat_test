<?php


Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
//


Route::middleware(['auth:api'])->group(function () {
    Route::get('logout', 'UserController@logout');
    Route::get('get-contacts', 'UserController@getContacts');
    Route::post('send-conversations', 'UserController@sendConversation');
    Route::get('get-conversations/{id}', 'UserController@loadConversations');

    Route::post('create-groups','UserController@createGroup');
});