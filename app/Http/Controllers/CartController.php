<?php

namespace App\Http\Controllers;

use App\Carrito;
use App\CarritoProducto;
use App\Cliente;
// use App\Configuracion;
use App\Events\UserCart;
use App\ProductoReferencia;
use App\Services\CartService;
// use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Log;

class CartController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('userCart', 'store');
    }

  
    public function index()
    {
        $cliente = auth()->user()->cliente;
        return view('tienda.cart');
    }

    //*función para cargar el carrito en el componente cart
    public function cartUser(Request $request)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $cliente = Cliente::where('user_id',auth()->user()->id)->firstOrFail();
       

        try {
           
            $productos = CarritoProducto::whereHas('carrito',
                function (Builder $query) use ($cliente) {
                    $query->estado()
                    ->cliente($cliente->id);
                })
                ->with(['carrito', 'productoReferencia.colorProducto.color', 
                    'productoReferencia.colorProducto.producto',
                    'productoReferencia.talla', 'productoReferencia.colorProducto.imagenes'
                ])
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
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}
        
        try {

            $carrito = Carrito::estado()
                ->cliente(auth()->user()->cliente->id)
                ->first();
    
            return ['carrito' => $carrito];
           
        } catch (\Exception $e) {
            Log::debug('Error buscando el carrito'.json_encode($e));
        }
        
    }


    public function store(Request $request, CartService $cartService)
    {
        
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $cartService->create_cart($request);

        $response = ['data' => 'success'];

        return response()->json($response);

    }


    //función que se ejecuta al agregar un producto a un carrito existente
    public function update(Request $request, CartService $cartService)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

      
        return $cartService->update_cart($request);
    }


    public function updateProduct(Request $request, CartService $cartService)
    { 
        // se ejecuta al modificar la cantidad de producto en la página del carrito
        if ( ! request()->ajax()) {
            abort(401, 'Acceso denegado');
        };
        
        return $cartService->update_product($request);
        
    }


    public function userCart(Request $request, CartService $cartService)
    {

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

      
        return $cartService->user_cart();

    }


    public function destroy(Request $request, CartService $cartService)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}
        
        return $cartService->destroy($request);
    }

    
    public function remove(Request $request, CartService $cartService)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        return $cartService->remove_product($request);
    }

}
