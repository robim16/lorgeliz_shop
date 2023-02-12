<?php

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

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('home');
Route::get('/index', 'HomeController@productsIndex');
Route::get('/categorias', 'HomeController@categorias')->name('categorias');

Route::get('/checkout', 'HomeController@checkout')->name('checkout')->middleware('auth');


// api Route::get('/categorias/productos', 'HomeController@getProductos')->name('categorias.productos');
// Route::get('/categorias/productos/estado', 'HomeController@getProductosByState')->name('productos.estados');
// Route::get('/categorias/productos/vendidos', 'HomeController@getProductosSales')->name('productos.sale');
// Route::get('/categorias/productos/vistos', 'HomeController@getProductosVisitas')->name('productos.view');
// Route::get('/categorias/productos/orden', 'HomeController@getProductsByOrder')->name('productos.orden');
// Route::get('/categorias/productos/tipo', 'HomeController@getProductsByTipo')->name('productos.tipo');
// Route::get('/categorias/productos/genero', 'HomeController@getProductsByGenre')->name('productos.genero');
// Route::get('/categorias/productos/keyword', 'HomeController@getProductsByKeyword')->name('productos.keyword');

// Route::group(['prefix' => '/'], function (){
//     Route::get('/cuenta', 'UserController@index')->name('users.cuenta');
//     Route::put('/update', 'UserController@update')->name('users.update');
// });



Route::group(['prefix' => '/perfil'], function (){
    Route::put('/{id}', 'UserController@update')->name('users.update');
    Route::get('/{slug}', 'UserController@show')->name('users.show');
});


Route::group(['prefix' => '/contacto'], function (){
    Route::get('/', 'ContactoController@contacto')->name('contact');
    Route::post('/mail', 'ContactoController@sendMail')->name('contact.mail');
});


Route::group(['prefix' => '/chats'], function () {
    Route::get('/', 'ChatController@index');
    Route::get('/messages', 'ChatController@messages');
    // Route::put('/read/{chat}', 'ChatController@read_at');
    Route::put('/{chat}', 'ChatController@read_at');
    Route::post('/', 'ChatController@store')->name('chat.store');
});

Route::group(['prefix' => '/productos'], function() {
    //ruta api Route::put('{id}/update/visitas', 'ProductController@setVisitas')->name('productos.visitas');
    Route::get('/{slug}', 'ProductController@show')->name('productos.show');
    // Route::put('/visitas/update/{id}', 'ProductController@setVisitas')->name('product.visitas');
    // Route::get('/{slug}', 'ProductController@show')->name('producto.show');
});

// ruta api. Route::get('/tallas/productos/{id}', 'TallaController@getProductoTallas')->name('talla.productos');

Route::group(['prefix' => '/payments'], function (){
    Route::get('/epayco/response', 'PaymentController@response')->name('response');
});

// Route::get('/payments/epayco/response', 'PaymentController@response')->name('response');

Route::group(['prefix' => '/stock'], function (){
    Route::get('/verificar', 'StockController@verificarStock')->name('stock.verificar');
});

Route::group(['prefix' => '/pedidos'], function () {
    // Route::get('/', 'OrdersController@index')->name('pedidos.index');
    Route::get('/show/pdf/{id}', 'OrdersController@showPdf')->name('pedidos.show.pdf');
    Route::get('/factura/{id}', 'OrdersController@facturas')->name('pedidos.factura');
    // Route::get('/productos/{pedido}', 'OrdersController@productos');
});

Route::resource('/pedidos', 'OrdersController');

Route::group(['prefix' => '/cart'], function () {
    Route::get('/', 'CarController@index')->name('cart.index'); 
    Route::get('/user', 'CarController@cartUser')->name('cart.products'); 
    Route::get('/products', 'CarController@userCart')->name('cart.user');
    // Route::post('/store', 'CarController@store')->name('cart.store');
    Route::post('/', 'CarController@store')->name('cart.store');
    Route::post('/update', 'CarController@update')->name('cart.update');
    Route::post('/setCantidad', 'CarController@updateProduct')->name('cart.updateProduct');
    // Route::delete('/remove/{producto}', 'CarController@remove')->name('cart.remove');
    Route::delete('{producto}/remove', 'CarController@remove')->name('cart.remove');
    // Route::delete('/delete/{carrito}', 'CarController@destroy')->name('cart.destroy');
    Route::delete('{carrito}/delete', 'CarController@destroy')->name('cart.destroy');
    Route::get('/buscarCarrito', 'CarController@buscarCarrito')->name('cart.buscarCarrito');
});

Route::group(['prefix' => '/devoluciones'], function () {
    Route::get('/', 'DevolucionController@index')->name('devolucion.index');
    // rutas api Route::get('/verificar', 'DevolucionController@verificar');
    // Route::post('/store', 'DevolucionController@store')->name('devolucion.store');
    Route::post('/', 'DevolucionController@store')->name('devolucion.store');
    Route::get('/{id}', 'DevolucionController@show')->name('devolucion.show');
});

Route::group(['prefix' => '/ventas'], function () {
    Route::post('/epayco', 'VentaController@epayco_register')->name('venta.epayco');
    //Route::post('/epayco/confirm', 'VentaController@epaycoConfirm')->name('venta.confirmation'); //ruta para confirmación, de prueba
    // Route::post('/store', 'VentaController@store')->name('venta.store');
    Route::post('/', 'VentaController@store')->name('venta.store');
});

Route::group(['prefix' => '/notification'], function () {
    // Route::get('/client', 'NotificationController@clientNotification')->name('notification.client');
    Route::get('/', 'NotificationController@index')->name('notification.client');
    // Route::get('/cart/client', 'NotificationController@cartNotification')->name('notification.cart');
    // Route::put('/client/read/{id}', 'NotificationController@setClientRead')->name('notification.readClient');
    Route::put('/{id}', 'NotificationController@setClientRead')->name('notification.readClient');
});

Route::group(['prefix' => "/admin", "middleware" => [sprintf("role:%s", \App\Role::ADMIN)]], function() {
    Route::get('/', 'Admin\AdminController@index')->name('admin');

    Route::group(['prefix' => '/chats'], function () {
        Route::get('/', 'Admin\ChatController@index')->name('chats.admin');
        Route::get('/get', 'Admin\ChatController@chatsAjax')->name('chats.admin.index');
        Route::post('/', 'Admin\ChatController@store')->name('chat.admin.store');
        Route::get('/messages', 'Admin\ChatController@lastMessage')->name('chat.to-admin');
        Route::put('/read/{chat}', 'Admin\ChatController@readMessage')->name('chat.read');
        Route::get('/{cliente}', 'Admin\ChatController@show')->name('chats.admin.show');
    });

    Route::group(['prefix' => '/dashboard'], function () {
        Route::get('/', 'Admin\DashboardController@index')->name('dashboard');
        Route::get('/ventas', 'Admin\DashboardController@loadVentas')->name('dashboard.ventas');
    });

    Route::group(['prefix' => '/notification'], function () {
        Route::get('/', 'Admin\NotificationController@index')->name('admin.notification.index');
        // Route::put('/read/{id}', 'Admin\NotificationController@setRead')->name('admin.notification.read');
        Route::put('/{id}', 'Admin\NotificationController@update')->name('admin.notification.update');
    });

    Route::group(['prefix' => '/informes'], function () {
        Route::get('/ventas', 'Admin\InformesController@informeVentas')->name('informes.ventas');
        Route::get('/ventas/listado/{mes}', 'Admin\InformesController@mostrarVentas')->name('listado.ventas');
        Route::get('/productos', 'Admin\InformesController@ventaProductos')->name('informes.productos');
        Route::get('/clientes', 'Admin\InformesController@informeClientes')->name('informes.clientes');
        Route::get('/saldos', 'Admin\InformesController@informe_saldos_clientes')->name('informes.saldos');
        Route::get('/saldos/{cliente}', 'Admin\InformesController@facturasPendientesCliente')->name('informes.saldos.cliente');
        Route::get('/pagos', 'Admin\InformesController@informePagos')->name('informes.pagos');
        Route::get('/pagos/listado/{mes}', 'Admin\InformesController@mostrarPagos')->name('listado.pagos');
        Route::get('/pdf/ventas', 'Admin\InformesController@pdfInformeVentas')->name('informes.ventaspdf');
        Route::get('/pdf/pagos', 'Admin\InformesController@pdfInformePagos')->name('informes.pagospdf');
        Route::get('/pdf/productos', 'Admin\InformesController@pdfInformeProductos')->name('informes.productospdf');
        Route::get('/pdf/clientes', 'Admin\InformesController@pdfInformeClientes')->name('informes.clientespdf');
        Route::get('/pdf/ventas/mes', 'Admin\InformesController@pdfVentaShow')->name('informes.ventashowpdf');
        Route::get('/pdf/pagos/mes', 'Admin\InformesController@pdfPagosShow')->name('informes.pagoshowpdf');
        Route::get('/pdf/saldos', 'Admin\InformesController@informeSaldosClientesPdf')->name('informes.saldospdf');
    });

    Route::group(['prefix' => '/devoluciones'], function () {
        Route::get('/', 'Admin\DevolucionController@index')->name('admin.devolucion.index');
        Route::get('/listado', 'Admin\DevolucionController@pdfListarDevoluciones')->name('devolucion.listado');
        // Route::put('/update', 'Admin\DevolucionController@update')->name('devolucion.update');
        Route::put('/', 'Admin\DevolucionController@update')->name('devolucion.update');
        // Route::get('/{id}', 'Admin\DevolucionController@show')->name('admin.devolucion.show');
        Route::get('/{devolucione}', 'Admin\DevolucionController@show')->name('admin.devolucion.show');
    });
    
    Route::group(['prefix' => '/payments'], function (){
        Route::get('/', 'Admin\PaymentController@index')->name('payments.index');
        Route::get('/{id}/pdf', 'Admin\PaymentController@printPay')->name('payments.pdf');
        // Route::get('/payment/{id}', 'Admin\PaymentController@printPay')->name('payments.pdf');
        Route::get('/list', 'Admin\PaymentController@pdfPagosReporte')->name('payments.list');
       // Route::get('/epayco/response', 'PaymentController@response')->name('response');
        Route::post('/cancel/{pago}', 'Admin\PaymentController@anular')->name('payments.cancel');
    });

    Route::group(['prefix' => '/productos'], function () {
        //Route::get('/', 'ProductController@index')->name('product.index');
        Route::get('/{id}/colores', 'Admin\ProductController@product')->name('product.colors');
        Route::get('/color/{slug}', 'Admin\ProductController@showColor')->name('product.showColor');
        //Route::get('/create', 'ProductController@create')->name('product.create');
        //Route::post('/', 'ProductController@store')->name('product.store');
        Route::post('/newColor', 'Admin\ProductController@storeColor')->name('product.storeColor');
        Route::post('{id}/activate', 'Admin\ProductController@activate')->name('product.activate');
        //Route::get('/{id}/edit', 'ProductController@edit')->name('product.edit');
        Route::get('/editar/{slug}', 'Admin\ProductController@editColor')->name('product.editColor');
        //Route::put('/{producto}/update', 'ProductController@update')->name('product.update');
        Route::put('/update/{slug}', 'Admin\ProductController@updateColor')->name('product.updateColor');
        //Route::delete('{id}/delete', 'ProductController@destroy')->name('product.destroy');
        //ruta api Route::delete('/eliminar/imagen/{id}','Admin\ProductController@eliminarImagen')->name('product.eliminarimagen');
        Route::get('/add_color/{id}', 'Admin\ProductController@createColor')->name('product.color');
       
        //Route::get('/{id}', 'ProductController@show')->name('product.show');
    });
    
    Route::group(['prefix' => '/proveedores'], function () {
        Route::get('/', 'Admin\ProveedorController@index')->name('proveedor.index');
        Route::get('/create', 'Admin\ProveedorController@create')->name('proveedor.create');
        Route::post('/', 'Admin\ProveedorController@store')->name('proveedor.store');
        Route::get('/{slug}/edit', 'Admin\ProveedorController@edit')->name('proveedor.edit');
        Route::put('/{proveedor}/update', 'Admin\ProveedorController@update')->name('proveedor.update');
        Route::delete('/{proveedor}/delete', 'Admin\ProveedorController@destroy')->name('proveedor.destroy');
        Route::get('/{proveedor}', 'Admin\ProveedorController@show')->name('proveedor.show');
    });
    
    Route::group(['prefix' => '/stock'], function (){
        Route::get('/', 'Admin\StockController@index')->name('stock.index');
        Route::get('/listado', 'Admin\StockController@pdfInventarios')->name('stock.listadopdf');
        Route::post('/', 'Admin\StockController@store')->name('stock.store');
    });

    Route::group(['prefix' => '/tallas'], function (){
        //rutas admin/api
        // Route::get('/{id}', 'Admin\TallaController@getTalla')->name('talla.get');
        // Route::get('/tipos/get', 'Admin\TallaController@tallasTipoId')->name('talla.tipos');
        Route::post('/', 'Admin\TallaController@store')->name('talla.store');
    });

    Route::group(['prefix' => '/ventas'], function () {
        Route::get('/', 'Admin\VentaController@index')->name('venta.index');
        Route::put('/anular/{venta}', 'Admin\VentaController@anular')->name('venta.anular');
        Route::put('/pagar/{venta}', 'Admin\VentaController@registrarPago')->name('venta.pagar');
        //Route::post('/epayco', 'VentaController@epayco_register')->name('venta.epayco');
        //Route::post('/store', 'VentaController@store')->name('venta.store');
        Route::get('/factura/{id}', 'Admin\VentaController@facturaVentaAdmin')->name('venta.factura');
        Route::get('/listado','Admin\VentaController@listadoVentasPdf')->name('venta.listado');
        Route::get('/{venta}', 'Admin\VentaController@show')->name('venta.show');
    });
    
    Route::group(['prefix' => '/pedidos'], function () {
        Route::get('/', 'Admin\OrdersController@index')->name('admin.pedidos.index');
        Route::get('/pedido/pdf/{id}', 'Admin\OrdersController@imprimirPedido')->name('admin.pedidos.imprimir');
        Route::get('/listado/pdf', 'Admin\OrdersController@reportePedidosPdf')->name('admin.pedidos.reporte');
        // Route::put('/update', 'Admin\OrdersController@update')->name('admin.pedidos.update');
        Route::put('/', 'Admin\OrdersController@update')->name('admin.pedidos.update');
        Route::get('/{id}', 'Admin\OrdersController@show')->name('admin.pedidos.show');
    });

    Route::group(['prefix' => '/clientes'], function (){
        Route::get('/', 'Admin\ClienteController@index')->name('cliente.index');
        Route::get('/listado', 'Admin\ClienteController@pdfListadoClientes')->name('cliente.listado');
        // api Route::get('/chat', 'Admin\ClienteController@clientesChat')->name('cliente.chat');
        Route::post('/send_message', 'Admin\ClienteController@sendMessage')->name('cliente.message');
        Route::get('/{id}', 'Admin\ClienteController@show')->name('cliente.show');
    });

    //Route::group(['prefix' => '/categorias'], function () {
    
        //Route::get('/', 'CategoryController@index')->name('category.index');
        //Route::get('/create', 'CategoryController@create')->name('category.create');
        //Route::post('/', 'CategoryController@store')->name('category.store');
        //Route::get('/{slug}/edit', 'CategoryController@edit')->name('category.edit');
        //Route::put('/{categoria}/update', 'CategoryController@update')->name('category.update');
        //Route::delete('/{categoria}/delete', 'CategoryController@destroy')->name('category.destroy');
        //Route::get('/{categoria}', 'CategoryController@show')->name('category.show');
    
    //});

    //Route::group(['prefix' => '/subcategorias'], function () {
    
        //Route::get('/', 'SubcategoryController@index')->name('subcategory.index');
        //Route::get('/create', 'SubcategoryController@create')->name('subcategory.create');
        //Route::post('/', 'SubcategoryController@store')->name('subcategory.store');
        //Route::get('/{slug}/edit', 'SubcategoryController@edit')->name('subcategory.edit');
        //Route::put('/{subcategoria}/update', 'SubcategoryController@update')->name('subcategory.update');
        //Route::delete('/{subcategoria}/delete', 'SubcategoryController@destroy')->name('subcategory.destroy');
        //Route::get('/getSubcategoria', 'SubcategoryController@getSubcategoria')->name('subcategory.get');
        //Route::get('/{subcategoria}', 'SubcategoryController@show')->name('subcategory.show');
       
    //});

    //Route::group(['prefix' => '/tipo_producto'], function () {

        //Route::get('/', 'TipoProductoController@index')->name('tipo.index');
        //Route::get('/create', 'TipoProductoController@create')->name('tipo.create');
        //Route::post('/', 'TipoProductoController@store')->name('tipo.store');
        //Route::get('/{slug}/edit', 'TipoProductoController@edit')->name('tipo.edit');
        //Route::put('/{tipo}/update', 'TipoProductoController@update')->name('tipo.update');
        //Route::delete('/{tipo}/delete', 'TipoProductoController@destroy')->name('tipo.destroy');
        //Route::get('/list', 'TipoProductoController@getTipo')->name('tipo.get');
        //Route::get('/{tipo}', 'TipoProductoController@show')->name('tipo.show');

    //});

    // Route::resource('/proveedores', 'Admin\ProveedorController');

    Route::resource('/product', 'Admin\ProductController');

    
    // ruta api. Route::get('/subcategory/getSubcategoria', 'Admin\SubcategoryController@getSubcategoria')->name('subcategory.get');
    Route::resource('/subcategory', 'Admin\SubcategoryController');

    //ruta api Route::get('/tipos/lista', 'Admin\TipoProductoController@getTipo')->name('tipo.get');
    Route::resource('/tipo', 'Admin\TipoProductoController');

    Route::resource('/category', 'Admin\CategoryController');

    // ruta api. Route::get('/colores/get/{id}', 'Admin\ColorController@getColores')->name('colores.get');
    //Route::get('/colores/get', 'ColorController@getColores')->name('colores.get');
    Route::resource('/color', 'Admin\ColorController');

    Route::resource('/configuracion', 'Admin\ConfiguracionController');


    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

    Route::resource('/configuracion', 'Admin\ConfiguracionController');

    Route::resource('/envios', 'Admin\EnviosController');

});

//Route::resource('/car', 'CarController');

Route::get('cancelar/{ruta}', function($ruta) {
    session()->flash('message', ['danger', ("Acción cancelada")]);
    return redirect()->route($ruta);
})->name('cancelar');

Auth::routes();

