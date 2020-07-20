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
Route::post('/login', 'Auth\LoginController@login');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/outlets', 'API\OutletController@index');
    Route::post('/outlets', 'API\OutletController@store');
    Route::get('/outlets/{id}/edit', 'API\OutletController@edit');
    Route::put('/outlets/{id}', 'API\OutletController@update');
    Route::delete('/outlets/{id}', 'API\OutletController@destroy');
    Route::get('/outlets/nopage', 'API\OutletController@indexNoPage');

    Route::get('/user', 'API\UserController@index');
    Route::post('/user', 'API\UserController@store');
    Route::get('/user/{id}/edit', 'API\UserController@edit');
    Route::post('/user/{id}', 'API\UserController@update')->name('user.update');
    Route::delete('/user/{id}', 'API\UserController@destroy');

    Route::get('/product', 'API\ProductController@index');
    Route::post('/product', 'API\ProductController@store');
    Route::get('/product/{id}/edit', 'API\ProductController@edit');
    Route::put('/product/{id}', 'API\ProductController@update');
    Route::delete('/product/{id}', 'API\ProductController@destroy');

    Route::get('/type-product', 'API\TypeController@index');
    Route::post('/type-product', 'API\TypeController@store');
});
