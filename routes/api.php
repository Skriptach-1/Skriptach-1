<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'Api\UserController@authenticate');
Route::post('register', 'Api\UserController@register');
Route::get('user', 'Api\UserController@getUser');
Route::get('question', 'Api\UserController@getQuestion');
Route::post('question', 'Api\UserController@postQuestion');
