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

Route::namespace('Api')->group(
    function () {
        Route::get('/clients', 'ClientController@index')->name('clients.index');
        
        Route::resource('payments', 'PaymentController', [
            'only' => ['index', 'store']
        ]);
    }
);
