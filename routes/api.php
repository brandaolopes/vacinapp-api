<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

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

Route::post('login', 'ApiController@login');
Route::post('/user', 'UserController@store');


// Rotas protegidas por autenticação:
Route::group(['middleware' => 'auth.jwt'], function () {

    Route::get('/users', 'UserController@index');
    Route::post('me', 'ApiController@me');

    Route::get('/users/{user}', 'UserController@show');
    Route::patch('/users/{user}', 'UserController@update');
    Route::delete('/users/{user}', 'UserController@destroy');
    Route::post('logout', 'ApiController@logout');
});

