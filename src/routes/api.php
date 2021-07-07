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
Route::get('/login', 'Api\AuthController@getLogin')->name('get.login');
Route::post('/login', 'Api\AuthController@login')->name('login');
Route::get('/sftp', 'Api\SftpController@test')->name('api.sftp.index');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->middleware('auth:api')->group(function () {
    Route::get('/products', 'ProductController@index')->name('api.product.index');
    Route::post('/store-product', 'ProductController@store')->name('api.product.store');
    Route::get('/product/{identifier}', 'ProductController@getProductByIdentifier')->name('api.product.find');


});
