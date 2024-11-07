<?php

namespace App\Http\Controllers\Admin;

use App\Carrito;
use App\ColorProducto;
use App\Events\AddProductEvent;
use App\Producto;
use App\ProductoReferencia;
use App\Http\Controllers\Controller;
use App\Http\Requests\StockRequest;
use App\Services\Admin\StockService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request, $busqueda = null)
    {


        // $productos = ProductoReferencia::whereHas('colorProducto', function (Builder $query) {
        //     $query->where('activo', 'Si');
        // })
        // ->with(['talla:id,nombre', 'colorProducto:id,color_id,producto_id,slug',
        //     'colorProducto.producto:id,nombre', 'colorProducto.color:id,nombre',
        //     'colorProducto.imagenes' => function($query) {
        //         $query->select('id', 'url', 'imageable_id');
        //     }
        // ])
        // ->where('stock', '>', '0')
        // ->when($busqueda, function ($query) use ($busqueda) {
        //     return $query->whereHas('colorProducto.producto', function (Builder $query) use($busqueda){
        //         $query->where('nombre','like',"%$busqueda%");
        //     })
        //     ->orWhereHas('colorProducto.color', function (Builder $query) use($busqueda){
        //         $query->where('nombre','like',"%$busqueda%");
        //     });
        // })
        // ->orderBy('color_producto_id')
        // ->paginate(5);


        
        return view('admin.stocks.index', compact('busqueda'));

    }



    public function store(StockRequest $request, StockService $stockService)
    {

        if (isset($request->validator) && $request->validator->fails()){
            return response()->json($request->validator->errors(), 422);
        }

        try {

            DB::beginTransaction();

            $colorproducto = $stockService->saveStock($request);


            DB::commit();
    
            $product = array();
            $product['data'] = array();
    
            $product['data'] = $colorproducto;
            
            broadcast(new AddProductEvent($product));

            $response = ['data' => 'success'];
            
            return response()->json($response);


        } catch (\Exception $e) {

            DB::rollBack();
            return $e;
        }

    }



    public function pdfInventarios()
    {
        try {

            $productos = ProductoReferencia::whereHas('colorProducto', function (Builder $query) {
                $query->where('activo', 'Si');
            })
                ->with(['talla', 'colorProducto'])
                ->where('stock', '>', '0')
                ->orderBy('color_producto_id')
                ->get();


            $count = $productos->count();

            $pdf = \PDF::loadView('admin.pdf.inventarios', ['productos' => $productos, 'count' => $count])
                ->setPaper('a4', 'landscape');

            return $pdf->download('inventarioproductos.pdf');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

    }

    public function loadStocks(Request $request)
    {
        $busqueda = $request->get('busqueda');

        $productos = ProductoReferencia::whereHas('colorProducto', function (Builder $query) {
            $query->where('activo', 'Si');
        })
        ->with(['talla:id,nombre', 'colorProducto:id,color_id,producto_id,slug',
            'colorProducto.producto:id,nombre', 'colorProducto.color:id,nombre',
            'colorProducto.imagenes' => function($query) {
                $query->select('id', 'url', 'imageable_id');
            }
        ])
        ->where('stock', '>', '0')
        ->when($busqueda, function ($query) use ($busqueda) {
            return $query->whereHas('colorProducto.producto', function (Builder $query) use($busqueda){
                $query->where('nombre','like',"%$busqueda%");
            })
            ->orWhereHas('colorProducto.color', function (Builder $query) use($busqueda){
                $query->where('nombre','like',"%$busqueda%");
            })
            ->orWhereHas('colorProducto', function (Builder $query) use($busqueda){
                $query->where('id', $busqueda);
            });
        })
        ->orderBy('color_producto_id')
        ->paginate(5);


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
