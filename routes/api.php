<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//
// Authentication
Route::post('register', 'Api\Auth\RegisterController@register');
Route::post('login', 'Api\Auth\LoginController@login');
Route::post('logout', 'Api\Auth\LoginController@logout');

//
// Posts
Route::get('posts', 'Api\PostController@index');
Route::get('posts/{post}', 'Api\PostController@show');
Route::group(['middleware' => 'auth:api'], function() {
    Route::post('posts/{post}', 'Api\PostController@store');
    Route::put('posts/{post}', 'Api\PostController@update');
    Route::delete('posts/{post}', 'Api\PostController@delete');
});
