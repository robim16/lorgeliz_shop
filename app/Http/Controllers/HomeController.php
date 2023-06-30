<?php

namespace App\Http\Controllers;

use App\Carrito;
use App\ColorProducto;
use App\ProductoReferencia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $disponibles = ProductoReferencia::disponibles();

        $productoSlider = ColorProducto::whereHas('producto', function (Builder $query) {
            $query->where('slider_principal', 'Si');
        })
            ->select('id', 'producto_id', 'slug')
            ->with([
                'producto:id,nombre,precio_actual,tipo_id', 'producto.tipo:id,nombre', 'color:id,nombre',
                'imagenes' => function ($query) {
                    $query->select('id', 'url', 'imageable_id');
                }
            ])
            // ->where('activo', 'Si')
            ->activo()
            ->whereIn('id', $disponibles)
            ->get();



        $producto_mas_visto = ColorProducto::with([
            'producto:id,nombre,precio_actual,tipo_id',
            'producto.tipo:id,nombre', 'color:id,nombre',
            'imagenes' => function ($query) {
                $query->select('id', 'url', 'imageable_id');
            }
        ])
            // ->where('visitas', '>', '0')
            // ->where('activo', 'Si')
            ->visitas()
            ->activo()
            ->whereIn('id', $disponibles)
            ->orderBy('visitas', 'DESC')
            ->take(5)
            ->get();



        $productos_vendidos = ColorProducto::with([
            'producto:id,nombre,precio_actual,tipo_id',
            'producto.tipo:id,nombre', 'color:id,nombre',
            'imagenes' => function ($query) {
                $query->select('id', 'url', 'imageable_id');
            }
        ])
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



        $productosoferta = ColorProducto::whereHas('producto', function (Builder $query) {
            $query->where('estado', '2');
        })
            ->with([
                'producto:id,nombre,precio_actual,precio_anterior,porcentaje_descuento,tipo_id',
                'producto.tipo:id,nombre', 'color:id,nombre',
                'imagenes' => function ($query) {
                    $query->select('id', 'url', 'imageable_id');
                }
            ])
            // ->where('activo', 'Si')
            ->activo()
            ->whereIn('id', $disponibles)
            ->orderBy('id', 'DESC')
            ->take(5)
            ->get();

        return view('tienda.index', compact(
            'productoSlider',
            'producto_mas_visto',
            'productos_vendidos',
            'productosoferta'
        ));
    }


    // función para implementar index con ajax
    public function productsIndex(Request $request)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $disponibles = ProductoReferencia::disponibles();

        
        $cantidad = 6 * $request->cantidad;




        $nuevos = ColorProducto::whereHas('producto', function (Builder $query) {
            $query->where('estado', '1');
        })
        ->with([
            'producto:id,nombre,precio_actual,tipo_id', 'producto.tipo:id,nombre', 'color:id,nombre',
            'imagenes' => function ($query) {
                $query->select('id', 'url', 'imageable_id');
            }
        ])
            // ->where('activo', 'Si')
            ->activo()
            ->whereIn('id', $disponibles)
            ->orderBy('id', 'DESC')
            ->take($cantidad)
            ->get();

      
        return ['nuevos' => $nuevos];
    }
    

    public function categorias()
    {
        return view('tienda.categoria');
    }


    public function checkout()
    {
        $carrito = Carrito::with('cliente.user')
        ->estado()
            ->cliente(auth()->user()->cliente->id)
            ->firstOrFail();

        // ->where('estado', 1)
        // ->where('cliente_id', auth()->user()->cliente->id)
        return view('tienda.checkout', compact('carrito'));
    }

    //todas las funciones asociadas a la página de categorías fueron 
    //implementadas en api/CategoryController
    public function getProductos(Request $request)
    {
        //obtener todos los productos, en vista categorías
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

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
    }


    public function getProductosByState(Request $request)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $estado = $request->estado;

       

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

    }

    public function getProductosSales(Request $request)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

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
        
    }


    public function getProductosVisitas(Request $request)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}


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

    }


    public function getProductsByOrder(Request $request)
    {

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $criterio = $request->criterio;

       
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
    }


    public function getProductsByTipo(Request $request)
    {
        
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $tipo = $request->tipo;
        

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

    }


    public function getProductsByGenre(Request $request)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $genero = $request->genero;


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

    }


    public function getProductsByKeyword(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        $keyword = $request->keyword;


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

    }
}
