<?php

namespace App\Http\Controllers\Api;

use App\ColorProducto;
use App\Http\Controllers\Controller;
use App\ProductoReferencia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    public function getProductos(Request $request)
    {
        //obtener todos los productos, en vista categorías
        if (!$request->ajax()) return redirect('/');

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
        if (!$request->ajax()) return redirect('/');

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
        if (!$request->ajax()) return redirect('/');

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
        if (!$request->ajax()) return redirect('/');

        $disponibles = ProductoReferencia::disponibles();

        $productos = ColorProducto::with(['producto.tipo:id,nombre','color:id,nombre','imagenes'])
        ->visitas()
        ->activo()
        ->whereIn('id', $disponibles)
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

        if (!$request->ajax()) return redirect('/');

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
        
        if (!$request->ajax()) return redirect('/');

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

    public function getProductsByGender(Request $request)
    {
       if (!$request->ajax()) return redirect('/');

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