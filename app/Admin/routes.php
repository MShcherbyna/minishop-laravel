<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('inventory/products', ProductController::class);

    Route::group([
        'prefix' => 'sales'
    ], function(Router $router){
        $router->resource('/orders', OrderController::class);
        $router->resource('/customers', CustomerController::class);
    });
});
