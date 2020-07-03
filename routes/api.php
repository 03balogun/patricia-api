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

Route::post('register', 'UserController@register')->name('register');
Route::post('login', 'UserController@login')->name('login');

Route::middleware('auth:api')->group( function () {
    Route::resource('products', 'ProductController');
    Route::resource('categories', 'ProductCategoryController');
    Route::resource('cart', 'CartController');
});
