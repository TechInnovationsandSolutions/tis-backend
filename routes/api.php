<?php


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

use Illuminate\Support\Facades\Request;

Route::middleware('auth:api')->get('/user', function () {
    return dd('hi');
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
});

// Route::group(['middleware' => 'auth:api'], function () {
Route::get('products', 'ProductController@index');
Route::get('products/{product}', 'ProductController@show');

// });

Route::get('products/{product}/ratings', 'RatingController@index');
Route::get('ratings/{id}', 'RatingController@show');
Route::post('products/{product}/ratings', 'RatingController@store');
Route::put('ratings/{id}', 'RatingController@update');


Route::get('categories', 'CategoryController@index');
Route::post('categories', 'CategoryController@store');
Route::get('categories/{id}', 'CategoryController@show');
Route::put('categories/{id}', 'CategoryController@update');
Route::delete('categories/{id}', 'CategoryController@destroy');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('users', 'UserController@index');
    Route::post('users', 'UserController@store');
    Route::get('users/{id}', 'UserController@show');
    Route::put('users/{id}', 'UserController@update');
    Route::delete('users/{id}', 'UserController@destroy');

    Route::get('/cart', 'CartController@index');
    Route::post('/cart', 'CartController@store');
    Route::get('/cart/{id}', 'CartController@show');
    Route::put('/cart/{id}', 'CartController@update');
    Route::delete('/cart/{id}', 'CartController@destroy');

    Route::get('address', 'OrderAddressController@index');
    Route::post('address', 'OrderAddressController@store');
    Route::put('address/{id}', 'OrderAddressController@update');
    Route::delete('address/{id}', 'OrderAddressController@destroy');

    Route::put('products/{product}', 'ProductController@update');
    Route::delete('products/{product}', 'ProductController@destroy');
    Route::post('products', 'ProductController@store');

    Route::delete('ratings/{id}', 'RatingController@destroy');
});

// Route::get('/address-all', 'OrderAddressController@all');
Route::get('/ratings-all', 'RatingController@all');

// });
