<?php

Route::get('/', ['as' => 'posts', 'uses' => 'PostController@index']);

Auth::routes();

Route::resource('post', 'PostController', ['except' => [
  'index'
]]);

Route::resource('post.answer', 'AnswerController', [
  'except' => [
    'index',
    'show',
    'create'
]]);

Route::get('answer/{id}/toggle_best', [
  'as' => 'answer.toggle_best',
  'uses' => 'AnswerController@toggle_best'
]);
