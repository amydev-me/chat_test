<?php


Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
//


Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', 'UserController@logout');
    Route::get('get-contacts', 'UserController@getContacts');
    Route::post('send-conversations', 'UserController@sendConversation');
    Route::get('get-conversations/{id}', 'UserController@loadConversations');
});