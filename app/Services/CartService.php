<?php

namespace App\Services;

use App\Carrito;
use App\CarritoProducto;
use App\Configuracion;
use App\Events\UserCart;
use App\ProductoReferencia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartService
{
    public function create_cart(Request $request)
    {
        try {

            if (auth()->user()) {
                
                $carrito =  Carrito::estado()->cliente(auth()->user()->cliente->id)
                    ->first();
    
                if (!$carrito) {
                    
        
                    DB::beginTransaction();
        
                    
                    $producto = ProductoReferencia::obtenerProducto($request->producto,$request->talla);
        
                    $configuracion = Configuracion::where('nit', '78900765')->first();
        
                    $precio = $producto[0]->colorProducto->producto->precio_actual;
    
                    $cantidad = $request->cantidad;
    
    
                    $subtotal = $cantidad * $precio;
                    $envio = $configuracion->costo_envio;
        
                    $carrito = new Carrito();
                    $carrito->fecha = \Carbon\Carbon::now();
                    $carrito->subtotal = $subtotal;
                    $carrito->envio = $envio;
                    $carrito->total = $subtotal + $envio;
                    $carrito->cliente_id = auth()->user()->cliente->id;
                    $carrito->estado = '1';
        
                    $carrito->save();
        
    
                    $carritoProducto = new CarritoProducto();
                    $carritoProducto->producto_referencia_id = $producto[0]->id;
                    $carritoProducto->carrito_id = $carrito->id;
                    $carritoProducto->cantidad = $cantidad;
        
                    $carritoProducto->save();
        
                    $cart =  $this->user_cart($request); //calcular número de productos en el carrito
        
                    broadcast(new UserCart($cart)); // notificar el evento
                    
                    DB::commit();
    
                } else {
    
                    $this->update_cart($request);
                }

            } else {

                $producto = ProductoReferencia::obtenerProducto($request->producto,$request->talla);
               
                $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
                $productFound = false;
                foreach ($cartItems as &$item) {
                    if ($item['product_id'] === $producto[0]->id) {
                        $item['quantity'] += $request->cantidad;
                        $productFound = true;
                        break;
                    }
                }
                if (!$productFound) {
                    $cartItems[] = [
                        'user_id' => null,
                        'product_id' => $producto[0]->id,
                        'quantity' => $request->cantidad,
                    ];
                }
                Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);
            }
            

        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug('Error creando el carrito'.'Error:'.json_encode($e));
        }

    }


    public function update_cart(Request $request)
    {
        try {

            DB::beginTransaction();

            $carrito = Carrito::where('id', $request->carrito)->firstOrFail(); //se busca el carrito del usuario

            $producto = ProductoReferencia::obtenerProducto($request->producto,$request->talla);

           
            $subtotal = $carrito->subtotal;

            $envio = $carrito->envio;

            $precio = $producto[0]->colorProducto->producto->precio_actual;
            
            $carrito->subtotal = ($request->cantidad * $precio) + $subtotal; //se actualiza el subtotal del carrito


            $carrito->total = $envio + $carrito->subtotal;
           

            $cart = CarritoProducto::where('carrito_id',$carrito->id)
                ->where('producto_referencia_id',$producto[0]->id)// se comprueba si el producto ha sido agregado antes
                ->first();

            if ($cart) {

                $nuevaCantidad = $cart->cantidad + $request->cantidad; // si se encuentra, la cantidad se actualiza

                if ($nuevaCantidad > $producto[0]->stock) { // se comprueba que la nueva cantidad < stock del producto

                    $response = ['data' => 'error', 'carrito' => $cart->cantidad, 'stock' => $producto[0]->stock];
                    return response()->json($response); // en caso de ser mayor se devuelve un error
                }
                else{
                    $cart->cantidad = $nuevaCantidad;
                    $cart->save();
                }
            }
            else{ 
                // si el producto no estaba en el carrito, se registra en carrito-producto

                $carritoProducto = new CarritoProducto();

                $carritoProducto->producto_referencia_id = $producto[0]->id;
                $carritoProducto->carrito_id = $carrito->id;
                $carritoProducto->cantidad = $request->cantidad;

                $carritoProducto->save();
            }

            $carrito->save();

            DB::commit();

            $cart =  $this->user_cart($request); //calcular número de productos en el carrito

            broadcast(new UserCart($cart)); // notificar el evento

            $response = ['data' => 'success'];

            return response()->json($response);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::debug('Error actualizando el carrito'.
                'Error:'.json_encode($e));
        }
    }


    public function user_cart()
    {
        try {

            if (auth()->user()) { // se obtiene el número de productos del carrito del usuario autenticado
    
                $productos = CarritoProducto::whereHas('carrito', function (Builder $query){
                    $query->estado()
                        ->cliente(auth()->user()->cliente->id);
                })
                ->selectRaw('SUM(cantidad) as cantidad')
                ->get();
    
                if ($cantidad = $productos[0]->cantidad) {
                   
                } else {
                    $cantidad = 0;
                }
            }
            else{
                $cantidad = 0;
            }
    
            return ['cantidad' => $cantidad];
           
        } catch (\Exception $e) {
            Log::debug('Error obteniendo el número de productos del carrito'.'Error:'.' '.json_encode($e));
        }
    }


    public function update_product(Request $request)
    {
        try {
            
            DB::beginTransaction();

            $productos = $request->producto; 
            $operacion = $request->operacion;

            $carrito = Carrito::where('cliente_id', auth()->user()->cliente->id)
                ->estado()
                ->first();

            $total = 0;
            
        

            $carrito_producto = CarritoProducto::where('carrito_id', $carrito->id)
                ->where('producto_referencia_id', $productos)
                ->first();
            
            $cnt = $carrito_producto->cantidad; // se obtiene la cantidad actual


            if ($operacion == 1) {
                if (($cnt + 1) <=   $carrito_producto->productoReferencia->stock) {
                    $carrito_producto->cantidad = $cnt + 1;
                    $carrito_producto->save();

                    $total = $carrito_producto->productoReferencia->colorProducto->producto->precio_actual;

                }else {
                    $response = ['data' => 'error'];
                    return response()->json($response); 
                }
                
            } else {

                if ($cnt == 1) {
                    $carrito_producto->delete();
                }else{
                    $carrito_producto->cantidad = $cnt - 1;
                    $carrito_producto->save();

                }
                $total = $carrito_producto->productoReferencia->colorProducto->producto->precio_actual * (-1);
            }
            

            $carrito->subtotal += $total; //se actualiza el subtotal del carrito

            $carrito->total = $carrito->subtotal + $carrito->envio; //se actualiza el total del carrito
            $carrito->save();

            DB::commit();

            $cart =  $this->user_cart($request); //calcular número de productos en el carrito
            
            broadcast(new UserCart($cart)); // notificar el evento

            $response = ['data' => 'success'];
            
            return response()->json($response);

        } catch (\Exception $e){
            DB::rollBack();

            Log::debug('Error modificando el producto en el carrito'.'carrito:'.' '.
                json_encode($carrito).'producto:'.' '.json_encode($carrito_producto).' '.
                'error:'.json_encode($e));
        }
    }
}