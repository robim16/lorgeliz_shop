<?php

namespace App\Http\Controllers;

use App\Carrito;
use App\CarritoProducto;
use App\Cliente;
use App\ColorProducto;
use App\Configuracion;
use App\Events\UserCart;
use App\Producto;
use App\ProductoReferencia;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class CarController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('userCart');
    }

    //en desuso, esta función fue reemplazada por la siguiente
    public function index()
    {
        
        //$cliente = Cliente::where('user_id',auth()->user()->id)->firstOrFail();

        $cliente = auth()->user()->cliente;
       
        // $productos = Producto::
        // join('color_producto','productos.id', 'color_producto.producto_id')
        // ->join('imagenes', 'color_producto.id', 'imagenes.imageable_id')
        // ->join('colores', 'color_producto.color_id', 'colores.id') 
        // ->join('producto_referencia', 'color_producto.id', 'producto_referencia.color_producto_id')
        // ->join('tallas','producto_referencia.talla_id', 'tallas.id')
        // ->join('carrito_producto', 'carrito_producto.producto_referencia_id','producto_referencia.id')
        // ->join('carritos','carritos.id', 'carrito_producto.carrito_id')
        // ->select('productos.id as codigo','productos.nombre', 'productos.precio_actual', 
        // 'productos.descripcion_corta','color_producto.slug', 'carritos.total as total', 
        // 'carrito_producto.cantidad', 'carritos.id as carrito', 'colores.nombre as color', 
        // 'color_producto.id as cop','tallas.nombre as talla', 'producto_referencia.id as ref', 
        // 'producto_referencia.stock', 'imagenes.url as imagen')
        // ->where('carritos.estado', 1)
        // ->where('carritos.cliente_id', $cliente->id)
        // ->where('imagenes.imageable_type', 'App\ColorProducto')
        // ->groupBy('producto_referencia.id')
        // //->groupBy('color_producto.id')
        // ->get();

        $productos = CarritoProducto::whereHas('carrito',
        function (Builder $query) use ($cliente) {
           $query->where('estado', 1)
           ->where('cliente_id', $cliente->id);
        })
        ->with(['carrito', 'productoReferencia'])
        ->get();

        //return view('tienda.cart', compact('productos'));
        return view('tienda.cart');
    }

    //función para cargar el carrito en el componente cart
    public function cartUser(Request $request)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $cliente = Cliente::where('user_id',auth()->user()->id)->firstOrFail();
        // $cliente = auth()->user()->cliente->id;
       
        // $productos = Producto::
        // join('color_producto','productos.id', 'color_producto.producto_id')
        // ->join('imagenes', 'color_producto.id', 'imagenes.imageable_id')
        // ->join('colores', 'color_producto.color_id', 'colores.id') 
        // ->join('producto_referencia', 'color_producto.id', 'producto_referencia.color_producto_id')
        // ->join('tallas','producto_referencia.talla_id', 'tallas.id')
        // ->join('carrito_producto', 'carrito_producto.producto_referencia_id','producto_referencia.id')
        // ->join('carritos','carritos.id', 'carrito_producto.carrito_id')
        // ->select('productos.id as codigo','productos.nombre', 'productos.precio_actual',
        // 'productos.descripcion_corta','color_producto.slug', 'carritos.total as total',
        // 'carrito_producto.cantidad', 'carritos.id as carrito', 'colores.nombre as color',
        // 'color_producto.id as cop','tallas.nombre as talla', 'producto_referencia.id as ref',
        // 'producto_referencia.stock', 'imagenes.url as imagen')
        // ->where('carritos.estado', 1)
        // ->where('carritos.cliente_id', $cliente->id)
        // ->where('imagenes.imageable_type', 'App\ColorProducto')
        // ->groupBy('producto_referencia.id')
        // //->groupBy('color_producto.id')
        // ->get();

        try {
           
            $productos = CarritoProducto::whereHas('carrito',
                function (Builder $query) use ($cliente) {
                    $query->estado()
                    ->cliente($cliente->id);
                })
                ->with(['carrito', 'productoReferencia.colorProducto.color', 
                'productoReferencia.colorProducto.producto',
                'productoReferencia.talla', 'productoReferencia.colorProducto.imagenes'])
                ->get();
    
            return ['productos' => $productos];

        } catch (\Exception $e) {

            Log::debug('ha ocurrido un error al obtener el carrito del cliente:'.
                $cliente->id.'Error: '.json_encode($e));
        }
    }
    
    public function buscarCarrito(Request $request)
    {
        //obtener el carrito del cliente autenticado antes de agregar nuevo producto
        // if (!$request->ajax()) return redirect('/');
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}
        
        try {

            $carrito = Carrito::estado()
            //where('estado', '1')
            // ->where('cliente_id', auth()->user()->cliente->id)
            ->cliente(auth()->user()->cliente->id)
            ->first();
    
            return ['carrito' => $carrito];
           
        } catch (\Exception $e) {
            Log::debug('Error buscando el carrito'.json_encode($e));
        }
        
    }

    public function store(Request $request)
    {
        
        // if (!$request->ajax()) return redirect('/');
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {

            $carrito =  Carrito::estado()->cliente(auth()->user()->cliente->id)
                ->first();


            if (!$carrito) {
              
    
                DB::beginTransaction();
    
                
                $producto = ProductoReferencia::obtenerProducto($request->producto,$request->talla);
    
                // $precio = $producto[0]->precio_actual;
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
                // $carritoProducto->producto_referencia_id = $producto[0]->referencia;
                $carritoProducto->producto_referencia_id = $producto[0]->id;
                $carritoProducto->carrito_id = $carrito->id;
                $carritoProducto->cantidad = $cantidad;
    
                $carritoProducto->save();
    
                $cart =  $this->userCart($request); //calcular número de productos en el carrito
    
                broadcast(new UserCart($cart)); // notificar el evento
                
                DB::commit();

            } else {

               $this->update($request);
            }
            

        } catch (\Exception $e) {
            DB::rollBack();

            Log::debug('Error creando el carrito'.'Error:'.json_encode($e));

            // return $e;
          
        }

    }

    //función que se ejecuta al agregar un producto a un carrito existente
    public function update(Request $request)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {

            DB::beginTransaction();

            $carrito = Carrito::where('id', $request->carrito)->firstOrFail(); //se busca el carrito del usuario

            $producto = ProductoReferencia::obtenerProducto($request->producto,$request->talla);

           
            $subtotal = $carrito->subtotal;

            $envio = $carrito->envio;

            // $precio = $producto[0]->precio_actual;
            $precio = $producto[0]->colorProducto->producto->precio_actual;
            
            $carrito->subtotal = ($request->cantidad * $precio) + $subtotal; //se actualiza el subtotal del carrito


            $carrito->total = $envio + $carrito->subtotal;
            // $cart = CarritoProducto::where('carrito_id',$carrito->id)
            // ->where('producto_referencia_id',$producto[0]->referencia)// se comprueba si el producto ha sido agregado antes
            // ->first();

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
            else{ // si el producto no estaba en el carrito, se registra en carrito-producto

                $carritoProducto = new CarritoProducto();

                // $carritoProducto->producto_referencia_id = $producto[0]->referencia;
                $carritoProducto->producto_referencia_id = $producto[0]->id;
                $carritoProducto->carrito_id = $carrito->id;
                $carritoProducto->cantidad = $request->cantidad;

                $carritoProducto->save();
            }

            $carrito->save();

            DB::commit();

            $cart =  $this->userCart($request); //calcular número de productos en el carrito

            broadcast(new UserCart($cart)); // notificar el evento

            $response = ['data' => 'success'];

            return response()->json($response);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::debug('Error actualizando el carrito'.
                'Error:'.json_encode($e));
        }
        
    }


    public function destroy(Request $request)
    {
        // if (!$request->ajax()) return redirect('/cart');
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}
        
        try{

            DB::beginTransaction();

            $productos = CarritoProducto::where('carrito_id', $request->carrito)->get(); // se seleccionan todos los productos del carrito del usuario

            foreach ($productos as $producto) {
                $producto->delete(); // borramos todos los productos
            }

            $carrito = Carrito::where('id', $request->carrito)->first();

            $carrito->delete(); // se borra el carrito

            DB::commit();

            $cart =  $this->userCart($request); //calcular número de productos en el carrito

            broadcast(new UserCart($cart)); // notificar el evento

            $response = ['data' => 'success'];
            
            return response()->json($response);

        }

        catch (\Exception $e){
            DB::rollBack();

            Log::debug('Error eliminando el carrito'.'carrito:'.' '.json_encode($carrito).' '.
            'error:'.json_encode($e));
        }
    }

    public function updateProduct(Request $request)
    { 
        // se ejecuta al modificar la cantidad de producto en la página del carrito
        // if (!$request->ajax()) return redirect('/')
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		};
        
        try {
            
            DB::beginTransaction();

            $productos = $request->producto; 
            $operacion = $request->operacion;

            $carrito = Carrito::where('cliente_id', auth()->user()->cliente->id)
            ->estado()
            ->first();

            $total = 0;
            
            //$carrito_productos = CarritoProducto::where('carrito_id', $carrito->id)
            //->where('producto_referencia_id', $productos)
            ////->where('producto_referencia_id', $value) // se obtienen los productos del carrito en el array
            //->first();

            $carrito_producto = CarritoProducto::where('carrito_id', $carrito->id)
            ->where('producto_referencia_id', $productos)
            ->first();
            
            $cnt = $carrito_producto->cantidad; // se obtiene la cantidad actual

            // $producto = Producto::join('color_producto', 'productos.id', '=', 'color_producto.producto_id')
            // ->join('producto_referencia', 'color_producto.id', '=', 'producto_referencia.color_producto_id')
            // ->where('producto_referencia.id', $productos)
            // ->select('productos.precio_actual', 'producto_referencia.stock') // se obtiene el precio del producto que se envío
            // ->first();
            

            // if ($operacion == 1) {
            //     if (($cnt + 1) <= $producto->stock) {
            //         $carrito_producto->cantidad = $cnt + 1;
            //         $carrito_producto->save();

            //         $total = $producto->precio_actual;

            //     }else {
            //         $response = ['data' => 'error'];
            //         return response()->json($response); 
            //     }
                
            // } else {
            //     if ($cnt == 1) {
            //         $carrito_producto->delete();
            //     }else{
            //         $carrito_producto->cantidad = $cnt - 1;
            //         $carrito_producto->save();

            //     }
            //     $total = $producto->precio_actual * (-1);
            // }

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
            

            $carrito->subtotal += $total; //$carrito->total += $total se actualiza el subtotal del carrito

            $carrito->total = $carrito->subtotal + $carrito->envio; //$carrito->total += $total se actualiza el total del carrito
            $carrito->save();

            DB::commit();

            $cart =  $this->userCart($request); //calcular número de productos en el carrito
            
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
    

    //public function userCart(Request $request)
    public function userCart(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {

            if (auth()->user()) { // se obtiene el número de productos del carrito del usuario autenticado
    
                // $productos = DB::table('carrito_producto')
                // ->join('carritos','carritos.id', '=', 'carrito_producto.carrito_id')
                // ->where('carritos.cliente_id', auth()->user()->cliente->id)
                // ->where('carritos.estado', 1)
                // ->select( DB::raw('SUM(carrito_producto.cantidad) as cantidad'))
                // ->get();
    
                $productos = CarritoProducto::whereHas('carrito', function (Builder $query){
                //     $query->where('estado', 1)
                //    ->where('cliente_id', auth()->user()->cliente->id);
                    $query->cliente(auth()->user()->cliente->id)
                    ->estado();
                })
                ->selectRaw('SUM(cantidad) as cantidad')
                ->get();
    
                if ( $cantidad = $productos[0]->cantidad) {
                    //$cantidad = $productos[0]->cantidad;
                } else {
                    $cantidad = 0;
                }
            }
            else{
                $cantidad = 0;
            }
    
            //$response = ['data' => $cantidad];
            
            //return response()->json($response);
    
            return ['cantidad' => $cantidad];
           
        } catch (\Exception $e) {
            Log::debug('Error obteniendo el número de productos del carrito'.'error:'.' '.json_encode($e));
        }

    }

    public function remove(Request $request)
    {
        // if (!$request->ajax()) return redirect('/cart');
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {
           
            
            $carrito = Carrito::where('cliente_id', auth()->user()->cliente->id)
            ->estado()
            ->first();

            // cliente(auth()->user()->cliente->id)

            $car_producto = CarritoProducto::where('producto_referencia_id', $request->producto)
            ->where('carrito_id', $carrito->id) // se busca el producto que viene
            ->first();

            // $producto = Producto::join('color_producto', 'productos.id', 'color_producto.producto_id')
            // ->join('producto_referencia', 'color_producto.id', 'producto_referencia.color_producto_id')
            // ->where('producto_referencia.id', $request->producto) // se obtiene su precio
            // ->first();

            $producto = ProductoReferencia::where('id', $request->producto) // se obtiene su precio
            ->first();


            // $carrito->total = $carrito->total - ($producto->precio_actual * $car_producto->cantidad); // se resta al total, el subtotal del producto a remover

            // $carrito->total = $carrito->total - 
            // ($producto->colorProducto->producto->precio_actual * $car_producto->cantidad);

            $carrito->subtotal = $carrito->subtotal - 
            ($producto->colorProducto->producto->precio_actual * $car_producto->cantidad);

            $carrito->total =  $carrito->subtotal + $carrito->envio;

            $carrito->save();

            $car_producto->delete();

            $cart =  $this->userCart($request); //calcular número de productos en el carrito

            broadcast(new UserCart($cart)); // notificar el evento

            $response = ['data' => 'success'];
            
            return response()->json($response);

        } catch (\Exception $e) {
            Log::debug('Error eliminando el producto del carrito'.'carrito:'.' '.json_encode($carrito).
                'producto:'.' '.json_encode($car_producto).' '.
                'Error:'.json_encode($e));
        }
    }

}
