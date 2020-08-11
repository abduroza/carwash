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

    Route::get('/operators', 'API\UserController@index'); //khusus operator
    Route::post('/user', 'API\UserController@store');
    Route::get('/user/{id}/edit', 'API\UserController@edit');
    Route::post('/operator/{id}', 'API\UserController@update')->name('operator.update'); //khusus operator
    Route::delete('/user/{id}', 'API\UserController@destroy');
    Route::get('/user-lists', 'API\UserController@userLists')->name('user.index');
    Route::get('/user-authenticated', 'API\UserController@getUserLogin')->name('user.authenticated');
    Route::post('/user/{id}', 'API\UserController@updateUser')->name('user.update'); //selain operator

    Route::get('/product', 'API\ProductController@index');
    Route::post('/product', 'API\ProductController@store');
    Route::get('/product/{id}/edit', 'API\ProductController@edit');
    Route::put('/product/{id}', 'API\ProductController@update');
    Route::delete('/product/{id}', 'API\ProductController@destroy');

    Route::get('/type-product', 'API\TypeController@index');
    Route::post('/type-product', 'API\TypeController@store');
    Route::get('/type-product/{id}', 'API\TypeController@showTypeForTransaction');
    Route::get('/type-product/{id}/edit', 'API\TypeController@showProductForTrx');

    Route::get('/expense', 'API\ExpenseController@index');
    Route::post('/expense', 'API\ExpenseController@store');
    Route::get('/expense/{id}/edit', 'API\ExpenseController@edit');
    Route::put('/expense/{id}', 'API\ExpenseController@update');
    Route::delete('/expense/{id}', 'API\ExpenseController@destroy');
    Route::post('/expense/accept', 'API\ExpenseController@accept')->name('expense.accept');
    Route::post('/expense/reject', 'API\ExpenseController@reject')->name('expense.reject');

    Route::get('/notification', 'API\NotificationController@index');
    Route::post('/notification', 'API\NotificationController@store');

    Route::get('/customer', 'API\CustomerController@index');
    Route::post('/customer', 'API\CustomerController@store');
    Route::get('/customer/{id}/edit', 'API\CustomerController@edit');
    Route::put('/customer/{id}', 'API\CustomerController@update');
    Route::delete('/customer/{id}', 'API\CustomerController@destroy');

    Route::get('/transaction', 'API\OrderController@index');
    Route::post('/transaction', 'API\OrderController@store');
    Route::get('/transaction/{id}/view', 'API\OrderController@view');
    Route::post('/transaction/payment', 'API\OrderController@makePayment');
    Route::post('/transaction/complete-item', 'API\OrderController@completeItem');

    Route::get('/chart', 'API\DashboardController@chart');
    
});
