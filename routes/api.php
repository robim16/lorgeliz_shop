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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => '/'], function ($router) {

    Route::group(['prefix' => '/categorias'], function (){
        Route::get('/default', 'Api\CategoryController@getProductos');
        Route::get('/productos/estado', 'Api\CategoryController@getProductosByState');
        Route::get('/productos/vendidos', 'Api\CategoryController@getProductosSales');
        Route::get('/productos/vistos', 'Api\CategoryController@getProductosVisitas');
        Route::get('/productos/orden', 'Api\CategoryController@getProductsByOrder');
        Route::get('/productos/tipo', 'Api\CategoryController@getProductsByTipo');
        Route::get('/productos/genero', 'Api\CategoryController@getProductsByGender');
        Route::get('/productos/keyword', 'Api\CategoryController@getProductsByKeyword');
    });
    
    
    Route::group(['prefix' => '/productos'], function (){
        Route::put('{id}/update/visitas', 'Api\ProductController@setVisitas');
    });


    Route::group(['prefix' => '/tallas'], function () {
        Route::get('/{id}', 'Api\TallaController@index');
    });


    // Route::group(['prefix' => '/devoluciones'], function () {
    //     Route::get('/verify', 'Api\DevolucionController@verify');
    // });
    
    
    //requiere auth
    Route::group(['prefix' => '/stock'], function (){
        Route::get('/verify', 'Api\StockController@verify');
    });

});


Route::middleware('auth:api')->group(function () {

    Route::group(['prefix' => '/pedidos'], function (){
        Route::get('/productos/{pedido}', 'Api\OrderController@productos');
    });

});


// Route::middleware('auth:api')->group(function () {

    Route::group(['prefix' => "/admin"], function ($router) {

        Route::group(['prefix' => '/tallas'], function () {
            Route::get('/{id}', 'Admin\Api\TallaController@index');
            Route::get('/tipos/get', 'Admin\Api\TallaController@fetchTallasByTipo');
        });

        Route::group(['prefix' => '/chats'], function () {
            Route::get('/', 'Admin\Api\ChatController@index');
        });

        Route::group(['prefix' => '/colores'], function () {
            Route::get('/{id}', 'Admin\Api\ColorController@index');
        });

        Route::group(['prefix' => '/clientes'], function () {
            Route::get('/', 'Admin\Api\ClienteController@index');
        });

        Route::group(['prefix' => '/imagenes'], function () {
            Route::delete('/{id}/delete', 'Admin\Api\ImagenController@destroy');
        });

        Route::group(['prefix' => '/notifications'], function () {
            Route::get('/', 'Admin\Api\NotificationController@index');
            Route::put('/{id}', 'Admin\Api\NotificationController@update');
        });

        Route::group(['prefix' => '/subcategorias'], function () {
            Route::get('/', 'Admin\Api\SubcategoryController@index');
        });

        Route::group(['prefix' => '/tipos'], function () {
            Route::get('/', 'Admin\Api\TipoProductoController@index');
        });


        Route::group(['prefix' => '/ventas'], function () {
            Route::get('/cliente/{cliente}', 'Admin\Api\VentasController@obtener');
        });

    });

// });