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

Route::post('forgot/password', 'Auth\ForgotPasswordController')->name('password.reset');

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('update', 'AuthController@update');
        Route::get('show', 'AuthController@show');
         Route::post('update-password', 'UserController@changePassword');
        
    });
});

// Route::group(['middleware' => 'auth:api'], function () {
Route::get('/', function () {
    return 'API Live';
});
Route::get('products', 'ProductController@index');
Route::get('products/search', 'ProductController@search');
Route::get('products/tags', 'ProductController@findByTag');
Route::get('products/{product}', 'ProductController@show');


// });

Route::get('products/{product}/ratings', 'RatingController@index');
Route::get('ratings/{id}', 'RatingController@show');
Route::post('products/{product}/ratings', 'RatingController@store');
Route::put('ratings/{id}', 'RatingController@update');


Route::get('categories', 'CategoryController@index');
Route::get('categories/{id}', 'CategoryController@show');
Route::get('categories/{id}/products', 'CategoryController@products');

Route::group(['middleware' => ['auth:api', 'admin']], function () {
    Route::post('categories', 'CategoryController@store');
    Route::put('categories/{id}', 'CategoryController@update');
    Route::delete('categories/{id}', 'CategoryController@destroy');
    //user admin routes
    Route::delete('users/{id}', 'UserController@destroy');
    Route::get('users/{id}/orders', 'OrderController@userOrders');
    Route::get('users', 'UserController@index');
    //product admin routes

    Route::put('products/{product}', 'ProductController@update');
    Route::delete('products/{product}', 'ProductController@destroy');
    Route::post('products', 'ProductController@store');

    Route::delete('ratings/{id}', 'RatingController@destroy');

    //orders admin
    Route::get('orders-all', 'OrderController@all');
    Route::put('orders/{id}', 'OrderController@update');
    Route::delete('orders/{id}', 'OrderController@destroy');

    // Tags admin
    Route::get('tags', 'TagController@index');
    Route::post('tags', 'TagController@store');
    Route::get('tags/{id}', 'TagController@show');
    Route::put('tags/{id}', 'TagController@update');
    Route::delete('tags/{id}', 'TagController@destroy');
});
Route::group(['middleware' => 'auth:api'], function () {


     Route::post('users/update-password', 'UserController@updatePassword');
    Route::post('users', 'UserController@store');
    Route::get('users/{id}', 'UserController@show');
    Route::put('users/{id}', 'UserController@update');
   


    Route::get('/cart', 'CartController@index');
    Route::post('/cart', 'CartController@store');
    Route::get('/cart/{id}', 'CartController@show');
    Route::put('/cart/{id}', 'CartController@update');
    Route::delete('/cart/{id}', 'CartController@destroy');

    Route::get('address', 'OrderAddressController@index');
    Route::post('address', 'OrderAddressController@store');
    Route::put('address/{id}', 'OrderAddressController@update');
    Route::delete('address/{id}', 'OrderAddressController@destroy');


    Route::get('orders', 'OrderController@index');
    Route::post('orders', 'OrderController@store');
    Route::get('orders/{id}', 'OrderController@show');
});

// Route::get('/address-all', 'OrderAddressController@all');
Route::get('/ratings-all', 'RatingController@all');

// });
