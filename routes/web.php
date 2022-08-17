<?php 

use app\core\Route;

Route::get('/', 'MainController@index');
Route::get('/accounts', 'AccountController@index');