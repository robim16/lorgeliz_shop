<?php

namespace App\Http\Controllers;

use App\Carrito;
use App\Cliente;
use App\ColorProducto;
use App\Factura;
use App\Producto;
use App\ProductoReferencia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        try {

           
            $disponibles = ProductoReferencia::disponibles();
    
            $productoSlider = ColorProducto::whereHas('producto', 
                function (Builder $query) {
                    $query->where('slider_principal', 'Si');
                })
                ->with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
                // ->where('activo', 'Si')
                ->activo()
                ->whereIn('id', $disponibles)
                ->get();


            $producto_mas_visto = ColorProducto::with(['producto.tipo:id,nombre',
                'color:id,nombre','imagenes'])
                // ->where('visitas', '>', '0')
                // ->where('activo', 'Si')
                ->visitas()
                ->activo()
                ->whereIn('id', $disponibles)
                ->orderBy('visitas', 'DESC')
                ->take(5)
                ->get();


            $productos_vendidos = ColorProducto::with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
                ->join('producto_referencia', 'color_producto.id', 'producto_referencia.color_producto_id')
                ->join('producto_venta', 'producto_referencia.id', 'producto_venta.producto_referencia_id')
                // ->where('activo', 'Si')
                ->activo()
                ->where('producto_referencia.stock', '>', '0')
                ->select('color_producto.*', DB::raw('SUM(producto_venta.cantidad) as cantidad'))
                ->groupBy('color_producto.id')
                ->orderBy('cantidad', 'DESC')
                ->take(5)
                ->get();


            $productosoferta = ColorProducto::whereHas('producto', 
                function (Builder $query) {
                    $query->where('estado', '2');
                })
                ->with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
                // ->where('activo', 'Si')
                ->activo()
                ->whereIn('id', $disponibles)
                ->orderBy('id', 'DESC')
                ->take(5)
                ->get();

    
            return view('tienda.index', compact('productoSlider','producto_mas_visto', 
                'productos_vendidos', 'productosoferta'));

        } catch (\Exception $e) {

            Log::debug('Error en index de homeCtrl.Error: '.json_encode($e));
        }

    }



    // función para implementar index con ajax
    public function productsIndex(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $disponibles = ProductoReferencia::disponibles();

        // $slider = ColorProducto::whereHas('producto', function (Builder $query) {
        //     $query->where('slider_principal', 'Si');
        // })
        // ->with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
        // ->where('activo', 'Si')
        // ->whereIn('id', $disponibles)
        // ->get();

        $cantidad = 6 * $request->cantidad;

        // $nuevos = Producto::join('color_producto','productos.id', '=', 'color_producto.producto_id')
        // ->join('imagenes', 'color_producto.id', '=', 'imagenes.imageable_id')
        // ->join('colores', 'color_producto.color_id', '=', 'colores.id')
        // ->join('tipos', 'productos.tipo_id', '=', 'tipos.id')
        // ->join('producto_referencia', 'color_producto.id', '=', 'producto_referencia.color_producto_id')
        // ->select('productos.*', 'color_producto.slug as slug', 'color_producto.id as cop',
        // 'tipos.nombre as tipo', 'colores.nombre as color', 'imagenes.url as imagen')
        // ->where('productos.estado', '=', '1')
        // ->where('color_producto.activo', 'Si')
        // ->where('imagenes.imageable_type', 'App\ColorProducto')
        // ->where('producto_referencia.stock', '>', '0')
        // ->groupBy('color_producto.id')
        // ->orderBy('color_producto.producto_id', 'DESC')
        // ->take($cantidad)
        // ->get();

        try {
            
            $nuevos = ColorProducto::whereHas('producto', function (Builder $query) {
                $query->where('estado', '1');
            })
            ->with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
            // ->where('activo', 'Si')
            ->activo()
            ->whereIn('id', $disponibles)
            ->orderBy('id', 'DESC')
            ->take($cantidad)
            ->get();

            return ['nuevos' => $nuevos];


        } catch (\Exception $e) {

            Log::debug('Error en productosIndex de homeCtrl.Error: '.json_encode($e));
        }


        // $populares = ColorProducto::with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
        // ->where('visitas', '>', '0')
        // ->where('activo', 'Si')
        // ->whereIn('id', $disponibles)
        // ->orderBy('visitas', 'DESC')
        // ->take(5)
        // ->get();

        
        // $vendidos = ColorProducto::with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
        // ->join('producto_referencia', 'color_producto.id', 'producto_referencia.color_producto_id')
        // ->join('producto_venta', 'producto_referencia.id', 'producto_venta.producto_referencia_id')
        // ->where('activo', 'Si')
        // ->where('producto_referencia.stock', '>', '0')
        // ->select('color_producto.*', DB::raw('SUM(producto_venta.cantidad) as cantidad'))
        // ->groupBy('color_producto.id')
        // ->orderBy('cantidad', 'DESC')
        // ->take(5)
        // ->get();

        // $ofertas = ColorProducto::whereHas('producto', function (Builder $query) {
        //     $query->where('estado', '2');
        // })
        // ->with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
        // ->where('activo', 'Si')
        // ->whereIn('id', $disponibles)
        // ->orderBy('id', 'DESC')
        // ->take(5)
        // ->get();
       

        // return ['slider' => $slider, 'nuevos' => $nuevos, 'populares' => $populares, 'vendidos' => $vendidos, 'ofertas' => $ofertas];
       
    }



    public function categorias()
    {
        return view('tienda.categoria');
    }


    public function checkout()
    {

        try {

            $carrito = Carrito::with('cliente.user')->estado()
                ->cliente(auth()->user()->cliente->id)
                ->firstOrFail();
            
            return view('tienda.checkout', compact('carrito'));

        } catch (\Exception $e) {

            Log::debug('Error en función checkout de homeCtrl.Error: '.json_encode($e));
        }
        
        // ->where('estado', 1)
        // ->where('cliente_id', auth()->user()->cliente->id)
    }



    //todas las funciones asociadas a la página de categorías fueron 
    //implementadas en api/CategoryController
    public function getProductos(Request $request)
    {
        //obtener todos los productos, en vista categorías
        // if (!$request->ajax()) return redirect('/');
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}


        try {

            $disponibles = ProductoReferencia::disponibles();
    
            $productos = ColorProducto::with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
            // ->where('activo', 'Si')
            ->activo()
            ->whereIn('id', $disponibles)
            ->paginate(12);
    
    
            return [
                'pagination' => [
                    'total'        => $productos->total(),
                    'current_page' => $productos->currentPage(),
                    'per_page'     => $productos->perPage(),
                    'last_page'    => $productos->lastPage(),
                    'from'         => $productos->firstItem(),
                    'to'           => $productos->lastItem(),
                ],
                'productos' => $productos
            ];
            
        } catch (\Exception $e) {
            //throw $th;
        }

    }



    public function getProductosByState(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}


        $estado = $request->estado;


        try {
          
            $disponibles = ProductoReferencia::disponibles();
    
            $productos = ColorProducto::whereHas('producto', function (Builder $query) 
            use ($estado) {
                $query->where('estado', $estado);
            })
            ->with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
            // ->where('activo', 'Si')
            ->activo()
            ->whereIn('id', $disponibles)
            ->orderBy('id', 'DESC')
            ->paginate(12);
            
            return [
                'pagination' => [
                    'total'        => $productos->total(),
                    'current_page' => $productos->currentPage(),
                    'per_page'     => $productos->perPage(),
                    'last_page'    => $productos->lastPage(),
                    'from'         => $productos->firstItem(),
                    'to'           => $productos->lastItem(),
                ],
                'productos' => $productos
            ];


        } catch (\Exception $e) {
            //throw $th;
        }


    }



    public function getProductosSales(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

       

        try {


            $productos = ColorProducto::with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
                ->join('producto_referencia', 'color_producto.id', 'producto_referencia.color_producto_id')
                ->join('producto_venta', 'producto_referencia.id', 'producto_venta.producto_referencia_id')
                // ->where('activo', 'Si')
                ->activo()
                ->where('producto_referencia.stock', '>', '0')
                ->select('color_producto.*', DB::raw('SUM(producto_venta.cantidad) as cantidad'))
                ->groupBy('color_producto.id')
                ->orderBy('cantidad', 'DESC')
                ->paginate(12);
            
        
            return [
                'pagination' => [
                    'total'        => $productos->total(),
                    'current_page' => $productos->currentPage(),
                    'per_page'     => $productos->perPage(),
                    'last_page'    => $productos->lastPage(),
                    'from'         => $productos->firstItem(),
                    'to'           => $productos->lastItem(),
                ],
                'productos' => $productos
            ];
           
        } catch (\Exception $e) {
            //throw $th;
        }

        
    }



    public function getProductosVisitas(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {
            
            $disponibles = ProductoReferencia::disponibles();
    
            $productos = ColorProducto::with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
            // ->join('producto_referencia', 'color_producto.id', 'producto_referencia.color_producto_id')
            // ->where('visitas', '>', '0')
            ->visitas()
            ->activo()
            ->whereIn('id', $disponibles)
            // ->where('producto_referencia.stock', '>', '0')
            // ->groupBy('color_producto.id')
            ->orderBy('visitas', 'DESC')
            ->paginate(12);
            
            return [
                'pagination' => [
                    'total'        => $productos->total(),
                    'current_page' => $productos->currentPage(),
                    'per_page'     => $productos->perPage(),
                    'last_page'    => $productos->lastPage(),
                    'from'         => $productos->firstItem(),
                    'to'           => $productos->lastItem(),
                ],
                'productos' => $productos
            ];

        } catch (\Exception $e) {
            //throw $th;
        }

    }



    public function getProductsByOrder(Request $request)
    {

        // if (!$request->ajax()) return redirect('/');
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $criterio = $request->criterio;

        
        try {
           
            $disponibles = ProductoReferencia::disponibles();
    
            $productos = ColorProducto::join('productos', 'productos.id', 'color_producto.producto_id')
            ->with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
            // ->where('activo', 'Si')
            ->activo()
            ->whereIn('color_producto.id', $disponibles)
            ->select('color_producto.*')
            ->orderBy('productos.'.$criterio)
            ->paginate(12);
    
    
            return [
                'pagination' => [
                    'total'        => $productos->total(),
                    'current_page' => $productos->currentPage(),
                    'per_page'     => $productos->perPage(),
                    'last_page'    => $productos->lastPage(),
                    'from'         => $productos->firstItem(),
                    'to'           => $productos->lastItem(),
                ],
                'productos' => $productos
            ];

        } catch (\Exception $e) {
            //throw $th;
        }

    }



    public function getProductsByTipo(Request $request)
    {
        
        // if (!$request->ajax()) return redirect('/');
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $tipo = $request->tipo;
        

        try {
           
            $disponibles = ProductoReferencia::disponibles();
    
            $productos = ColorProducto::whereHas('producto', function (Builder $query) 
            use ($tipo) {
                $query->where('tipo_id', $tipo);
            })
            ->with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
            // ->where('activo', 'Si')
            ->activo()
            ->whereIn('id', $disponibles)
            ->paginate(12);
    
            return [
                'pagination' => [
                    'total'        => $productos->total(),
                    'current_page' => $productos->currentPage(),
                    'per_page'     => $productos->perPage(),
                    'last_page'    => $productos->lastPage(),
                    'from'         => $productos->firstItem(),
                    'to'           => $productos->lastItem(),
                ],
                'productos' => $productos
            ];

        } catch (\Exception $e) {
            //throw $th;
        }

    }


    
    public function getProductsByGenre(Request $request)
    {
    //    if (!$request->ajax()) return redirect('/');
        if ( ! request()->ajax()) {
            abort(401, 'Acceso denegado');
        }

        $genero = $request->genero;


        try {
            
            $disponibles = ProductoReferencia::disponibles();
    
            $productos = ColorProducto::whereHas('producto.tipo.subcategoria.categoria',
             function (Builder $query) use ($genero) {
                $query->where('nombre', $genero);
            })
            ->with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
            // ->where('activo', 'Si')
            ->activo()
            ->whereIn('id', $disponibles)
            ->paginate(12);
            
            return [
                'pagination' => [
                    'total'        => $productos->total(),
                    'current_page' => $productos->currentPage(),
                    'per_page'     => $productos->perPage(),
                    'last_page'    => $productos->lastPage(),
                    'from'         => $productos->firstItem(),
                    'to'           => $productos->lastItem(),
                ],
                'productos' => $productos
            ];

        } catch (\Exception $e) {
            //throw $th;
        }

    }



    public function getProductsByKeyword(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        $keyword = $request->keyword;


        try {
           
            $disponibles = ProductoReferencia::disponibles();
    
            $productos = ColorProducto::orWhereHas('producto',
            function (Builder $query) use ($keyword) {
               $query->where('nombre','like',"%$keyword%");
            })
            ->orWhereHas('producto.tipo.subcategoria.categoria',
            function (Builder $query) use ($keyword) {
               $query->where('nombre','like',"%$keyword%");
            })
            ->orWhereHas('producto.tipo',
            function (Builder $query) use ($keyword) {
               $query->where('nombre','like',"%$keyword%");
            })
            ->with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
            // ->where('activo', 'Si')
            ->activo()
            ->whereIn('id', $disponibles)
            ->paginate(12);
            
            return [
                'pagination' => [
                    'total'        => $productos->total(),
                    'current_page' => $productos->currentPage(),
                    'per_page'     => $productos->perPage(),
                    'last_page'    => $productos->lastPage(),
                    'from'         => $productos->firstItem(),
                    'to'           => $productos->lastItem(),
                ],
                'productos' => $productos
            ];
            
        } catch (\Exception $e) {
            //throw $th;
        }

    }

}
