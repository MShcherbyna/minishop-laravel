<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::get('/', 'PageController@index')->name('home');
Route::get('/cart', 'CartController@index')->name('cart');
Route::delete('/remove/{id}', 'CartController@destroy')->name('cart.destroy');
Route::post('/cart', 'CartController@store')->name('cart.store');
Route::put('/cart/{id}', 'CartController@update')->name('cart.put');
Route::get('/checkout', 'CheckoutController@index')->name('checkout')->middleware('auth');
Route::get('/guest-checkout', 'CheckoutController@index')->name('guest.checkout');
Route::post('/checkout', 'CheckoutController@store')->name('checkout.store');
Route::get('/success', 'ConfirmController@index')->name('success');

Route::get('/clear', function(){
    \Cart::clear();
});

Route::get('/test', function(){

    var_dump(round(Cart::getTotal(), 2));
});


