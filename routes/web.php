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

Route::get('post/{id}/subscribe', [
  'as' => 'post.subscribe',
  'uses' => 'PostController@subscribe'
]);

Route::get('answer/{id}/toggle_best', [
  'as' => 'answer.toggle_best',
  'uses' => 'AnswerController@toggleBest'
]);

Route::post('post/{id}/vote_up', [
  'as' => 'post.vote_up',
  'uses' => 'PostController@voteUp'
]);

Route::post('post/{id}/vote_down', [
  'as' => 'post.vote_down',
  'uses' => 'PostController@voteDown'
]);

Route::post('post/{id}/vote_cancel', [
  'as' => 'post.vote_cancel',
  'uses' => 'PostController@voteCancel'
]);


Route::post('answer/{id}/vote_up', [
  'as' => 'answer.vote_up',
  'uses' => 'AnswerController@voteUp'
]);

Route::post('answer/{id}/vote_down', [
  'as' => 'answer.vote_down',
  'uses' => 'AnswerController@voteDown'
]);

Route::post('answer/{id}/vote_cancel', [
  'as' => 'answer.vote_cancel',
  'uses' => 'AnswerController@voteCancel'
]);
