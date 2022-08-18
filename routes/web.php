<?php 

use app\core\Route;

Route::get('/', 'MainController@index');
Route::get('/accounts', 'AccountController@index');

// ACCOUNTS API
Route::get('api/accounts', 'AccountApiController@index');
Route::post('api/accounts', 'AccountApiController@store');
Route::put('api/accounts/:id', 'AccountApiController@update');
Route::get('api/accounts/:id', 'AccountApiController@show');
Route::delete('api/accounts/:id', 'AccountApiController@delete');